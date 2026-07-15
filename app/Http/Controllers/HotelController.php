<?php

namespace App\Http\Controllers;

use App\Exceptions\GeocodingException;
use App\Http\Requests\StoreHotelRequest;
use App\Http\Requests\UpdateHotelRequest;
use App\Models\Hotel;
use App\Repositories\HotelRepository;
use App\Services\HotelService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HotelController extends Controller
{
    public function __construct(
        private readonly HotelRepository $hotels,
        private readonly HotelService $service
    ) {
    }

    public function index(Request $request): View
    {
        $hotels = $this->hotels->paginateForUser($request->user());
        return view('hotels.index', compact('hotels'));
    }

    public function create(): View
    {
        return view('hotels.create');
    }

    public function store(StoreHotelRequest $request): RedirectResponse
    {
        try {
            $hotel = $this->service->create($request->validated(), $request->user());
        } catch (GeocodingException $e) {
            return back()->withInput()->withErrors(['address_line' => $e->getMessage()]);
        }

        return redirect()->route('panel.rooms.index', $hotel)
            ->with('success', 'Hotel registrado. Agrega habitaciones.');
    }

    public function edit(Request $request, Hotel $hotel): View
    {
        $this->service->ensureCanManage($request->user(), $hotel);
        return view('hotels.edit', compact('hotel'));
    }

    public function update(UpdateHotelRequest $request, Hotel $hotel): RedirectResponse
    {
        try {
            $this->service->update($hotel, $request->validated(), $request->user());
        } catch (GeocodingException $e) {
            return back()->withInput()->withErrors(['address_line' => $e->getMessage()]);
        }

        return back()->with('success', 'Hotel actualizado.');
    }

    public function destroy(Request $request, Hotel $hotel): RedirectResponse
    {
        $this->service->deactivate($hotel, $request->user());
        return back()->with('success', 'Hotel desactivado.');
    }
}
