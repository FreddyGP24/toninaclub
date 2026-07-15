<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Hotel;
use App\Models\Room;
use App\Repositories\RoomRepository;
use App\Services\HotelService;
use App\Services\RoomService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function __construct(
        private readonly RoomRepository $rooms,
        private readonly RoomService $service,
        private readonly HotelService $hotels
    ) {
    }

    public function index(Request $request, Hotel $hotel): View
    {
        $this->hotels->ensureCanManage($request->user(), $hotel);
        $rooms = $this->rooms->getForHotel($hotel);
        return view('rooms.index', compact('hotel', 'rooms'));
    }

    public function create(Request $request, Hotel $hotel): View
    {
        $this->hotels->ensureCanManage($request->user(), $hotel);
        return view('rooms.create', compact('hotel'));
    }

    public function store(StoreRoomRequest $request, Hotel $hotel): RedirectResponse
    {
        $this->service->create($hotel, $request->validated(), $request->user());
        return redirect()->route('panel.rooms.index', $hotel)->with('success', 'Habitación creada.');
    }

    public function edit(Request $request, Hotel $hotel, Room $room): View
    {
        $this->service->ensureBelongs($room, $hotel);
        $this->hotels->ensureCanManage($request->user(), $hotel);
        return view('rooms.edit', compact('hotel', 'room'));
    }

    public function update(UpdateRoomRequest $request, Hotel $hotel, Room $room): RedirectResponse
    {
        $this->service->update($hotel, $room, $request->validated(), $request->user());
        return redirect()->route('panel.rooms.index', $hotel)->with('success', 'Habitación actualizada.');
    }

    public function destroy(Request $request, Hotel $hotel, Room $room): RedirectResponse
    {
        $this->service->deactivate($hotel, $room, $request->user());
        return back()->with('success', 'Habitación desactivada.');
    }
}
