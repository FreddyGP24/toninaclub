<?php

namespace App\Repositories;

use App\Enums\ReservationStatus;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Database\Eloquent\Collection;

class RoomRepository
{
    public function getForHotel(Hotel $hotel): Collection
    {
        return $hotel->rooms()->orderBy('room_number')->get();
    }

    public function create(array $data): Room
    {
        return Room::create($data);
    }

    public function update(Room $room, array $data): Room
    {
        $room->update($data);
        return $room->refresh();
    }

    public function hasFutureActiveReservations(Room $room): bool
    {
        $statuses = array_map(
            fn (ReservationStatus $status) => $status->value,
            ReservationStatus::active()
        );

        return $room->reservations()
            ->whereIn('status', $statuses)
            ->where('check_out', '>', now()->toDateString())
            ->exists();
    }
}
