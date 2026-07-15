<?php

namespace App\Http\Controllers;

use App\Enums\ReservationStatus;
use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationStatusRequest;
use App\Models\Hotel;
use App\Models\Reservation;
use App\Models\Room;
use App\Repositories\ReservationRepository;
use App\Services\ReservationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function __construct(
        private readonly ReservationRepository $reservations,
        private readonly ReservationService $service
    ) {
    }

    public function index(Request $request): View
    {
        $reservations = $this->reservations->paginateForClient($request->user());
        return view('reservations.index', compact('reservations'));
    }

    public function create(Hotel $hotel, Room $room): View
    {
        abort_unless($hotel->active && $room->active && $room->hotel_id === $hotel->id, 404);
        return view('reservations.create', compact('hotel', 'room'));
    }

    public function store(
        StoreReservationRequest $request,
        Hotel $hotel,
        Room $room
    ): RedirectResponse {
        abort_unless($room->hotel_id === $hotel->id, 404);
        $this->service->create($request->user(), $room, $request->validated());

        return redirect()->route('reservations.index')
            ->with('success', 'Reservación creada y pendiente de confirmación.');
    }

    public function cancel(Request $request, Reservation $reservation): RedirectResponse
    {
        $this->service->cancel($reservation, $request->user());
        return back()->with('success', 'Reservación cancelada.');
    }

    public function ownerIndex(Request $request): View
    {
        $reservations = $this->reservations->paginateForOwner($request->user());
        return view('reservations.owner-index', compact('reservations'));
    }

    public function updateStatus(
        UpdateReservationStatusRequest $request,
        Reservation $reservation
    ): RedirectResponse {
        $this->service->updateStatus(
            $reservation,
            ReservationStatus::from($request->validated('status')),
            $request->user()
        );

        return back()->with('success', 'Estado actualizado.');
    }
}
