<?php

namespace App\Services;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\User;
use App\Repositories\RoomRepository;
use Illuminate\Validation\ValidationException;

class RoomService
{
    public function __construct(
        private readonly RoomRepository $rooms,
        private readonly HotelService $hotels
    ) {
    }

    public function create(Hotel $hotel, array $data, User $user): Room
    {
        $this->hotels->ensureCanManage($user, $hotel);

        return $this->rooms->create([
            ...$data,
            'hotel_id' => $hotel->id,
            'amenities' => $data['amenities'] ?? [],
            'active' => (bool) ($data['active'] ?? true),
        ]);
    }

    public function update(Hotel $hotel, Room $room, array $data, User $user): Room
    {
        $this->ensureBelongs($room, $hotel);
        $this->hotels->ensureCanManage($user, $hotel);
        $data['amenities'] = $data['amenities'] ?? [];
        return $this->rooms->update($room, $data);
    }

    public function deactivate(Hotel $hotel, Room $room, User $user): void
    {
        $this->ensureBelongs($room, $hotel);
        $this->hotels->ensureCanManage($user, $hotel);

        if ($this->rooms->hasFutureActiveReservations($room)) {
            throw ValidationException::withMessages([
                'room' => 'No puedes desactivar una habitación con reservaciones vigentes.',
            ]);
        }

        $this->rooms->update($room, ['active' => false]);
    }

    public function ensureBelongs(Room $room, Hotel $hotel): void
    {
        abort_unless($room->hotel_id === $hotel->id, 404);
    }
}
