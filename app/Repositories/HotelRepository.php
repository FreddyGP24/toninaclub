<?php

namespace App\Repositories;

use App\Enums\ReservationStatus;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class HotelRepository
{
    public function paginatePublic(array $filters, int $perPage = 9): LengthAwarePaginator
    {
        $query = Hotel::query()
            ->where('active', true)
            ->with(['rooms' => fn ($q) => $q->where('active', true)->orderBy('price_per_night')]);

        if (! empty($filters['q'])) {
            $term = trim($filters['q']);
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('city', 'like', "%{$term}%")
                    ->orWhere('state', 'like', "%{$term}%");
            });
        }

        if (! empty($filters['check_in']) && ! empty($filters['check_out'])) {
            $statuses = array_map(
                fn (ReservationStatus $status) => $status->value,
                ReservationStatus::active()
            );
            $guests = (int) ($filters['guests'] ?? 1);
            $checkIn = $filters['check_in'];
            $checkOut = $filters['check_out'];

            $query->whereHas('rooms', function ($roomQuery) use (
                $statuses,
                $guests,
                $checkIn,
                $checkOut
            ) {
                $roomQuery->where('active', true)
                    ->where('capacity', '>=', $guests)
                    ->whereDoesntHave('reservations', function ($reservationQuery) use (
                        $statuses,
                        $checkIn,
                        $checkOut
                    ) {
                        $reservationQuery->whereIn('status', $statuses)
                            ->where('check_in', '<', $checkOut)
                            ->where('check_out', '>', $checkIn);
                    });
            });
        }

        return $query->orderBy('name')->paginate($perPage)->withQueryString();
    }

    public function getActiveForMap(): Collection
    {
        return Hotel::query()
            ->where('active', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with(['rooms' => fn ($q) => $q->where('active', true)->orderBy('price_per_night')])
            ->orderBy('name')
            ->get();
    }

    public function paginateForUser(User $user, int $perPage = 10): LengthAwarePaginator
    {
        return Hotel::query()
            ->when(! $user->isAdmin(), fn ($q) => $q->where('owner_id', $user->id))
            ->withCount('rooms')
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data): Hotel
    {
        return Hotel::create($data);
    }

    public function update(Hotel $hotel, array $data): Hotel
    {
        $hotel->update($data);
        return $hotel->refresh();
    }

    public function slugExists(string $slug, ?int $ignoreId = null): bool
    {
        return Hotel::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))
            ->exists();
    }

    public function hasFutureActiveReservations(Hotel $hotel): bool
    {
        $statuses = array_map(
            fn (ReservationStatus $status) => $status->value,
            ReservationStatus::active()
        );

        return $hotel->rooms()
            ->whereHas('reservations', fn ($q) => $q
                ->whereIn('status', $statuses)
                ->where('check_out', '>', now()->toDateString()))
            ->exists();
    }
}
