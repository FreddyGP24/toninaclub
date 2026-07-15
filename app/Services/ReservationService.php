<?php

namespace App\Services;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use App\Repositories\ReservationRepository;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReservationService
{
    public function __construct(
        private readonly ReservationRepository $reservations
    ) {
    }

    public function create(User $user, Room $room, array $data): Reservation
    {
        abort_unless($user->isClient(), 403);

        $checkIn = CarbonImmutable::parse($data['check_in']);
        $checkOut = CarbonImmutable::parse($data['check_out']);

        return DB::transaction(function () use ($user, $room, $data, $checkIn, $checkOut) {
            $lockedRoom = $this->reservations->lockRoom($room->id);

            if (! $lockedRoom->active || ! $lockedRoom->hotel->active) {
                throw ValidationException::withMessages([
                    'room' => 'La habitación no está disponible.',
                ]);
            }

            if ((int) $data['guests'] > $lockedRoom->capacity) {
                throw ValidationException::withMessages([
                    'guests' => 'La cantidad de huéspedes supera la capacidad.',
                ]);
            }

            if ($this->reservations->hasOverlap(
                $lockedRoom->id,
                $checkIn->toDateString(),
                $checkOut->toDateString()
            )) {
                throw ValidationException::withMessages([
                    'check_in' => 'La habitación ya está reservada en esas fechas.',
                ]);
            }

            $nights = $checkIn->diffInDays($checkOut);
            $price = (float) $lockedRoom->price_per_night;

            return $this->reservations->create([
                'user_id' => $user->id,
                'room_id' => $lockedRoom->id,
                'check_in' => $checkIn->toDateString(),
                'check_out' => $checkOut->toDateString(),
                'guests' => (int) $data['guests'],
                'price_per_night' => $price,
                'nights' => $nights,
                'total' => round($price * $nights, 2),
                'status' => ReservationStatus::PENDING->value,
                'notes' => $data['notes'] ?? null,
            ]);
        });
    }

    public function cancel(Reservation $reservation, User $user): Reservation
    {
        abort_unless($reservation->user_id === $user->id, 403);

        if (! in_array($reservation->status, ReservationStatus::active(), true)) {
            throw ValidationException::withMessages([
                'reservation' => 'Esta reservación ya no puede cancelarse.',
            ]);
        }

        return $this->reservations->updateStatus(
            $reservation,
            ReservationStatus::CANCELLED
        );
    }

    public function updateStatus(
        Reservation $reservation,
        ReservationStatus $next,
        User $user
    ): Reservation {
        $reservation->loadMissing('room.hotel');

        abort_unless(
            $user->isAdmin() || $reservation->room->hotel->owner_id === $user->id,
            403
        );

        if (! $reservation->status->canTransitionTo($next)) {
            throw ValidationException::withMessages([
                'status' => 'El cambio de estado no es válido.',
            ]);
        }

        return $this->reservations->updateStatus($reservation, $next);
    }
}
