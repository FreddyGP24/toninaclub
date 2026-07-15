<?php

namespace App\Repositories;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ReservationRepository
{
    public function hasOverlap(int $roomId, string $checkIn, string $checkOut): bool
    {
        $statuses = array_map(
            fn (ReservationStatus $status) => $status->value,
            ReservationStatus::active()
        );

        return Reservation::query()
            ->where('room_id', $roomId)
            ->whereIn('status', $statuses)
            ->where('check_in', '<', $checkOut)
            ->where('check_out', '>', $checkIn)
            ->exists();
    }

    public function create(array $data): Reservation
    {
        return Reservation::create($data);
    }

    public function paginateForClient(User $user, int $perPage = 10): LengthAwarePaginator
    {
        return Reservation::query()
            ->where('user_id', $user->id)
            ->with(['room.hotel'])
            ->latest()
            ->paginate($perPage);
    }

    public function paginateForOwner(User $user, int $perPage = 15): LengthAwarePaginator
    {
        return Reservation::query()
            ->with(['client', 'room.hotel'])
            ->when(! $user->isAdmin(), fn ($q) => $q->whereHas(
                'room.hotel',
                fn ($hotelQuery) => $hotelQuery->where('owner_id', $user->id)
            ))
            ->latest()
            ->paginate($perPage);
    }

    public function updateStatus(
        Reservation $reservation,
        ReservationStatus $status
    ): Reservation {
        $reservation->update(['status' => $status->value]);
        return $reservation->refresh();
    }

    public function lockRoom(int $roomId): Room
    {
        return Room::query()->with('hotel')->lockForUpdate()->findOrFail($roomId);
    }
}
