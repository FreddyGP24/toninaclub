<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchHotelRequest;
use App\Models\Hotel;
use App\Repositories\HotelRepository;
use Illuminate\View\View;

class PublicHotelController extends Controller
{
    public function __construct(private readonly HotelRepository $hotels)
    {
    }

    public function index(SearchHotelRequest $request): View
    {
        $hotels = $this->hotels->paginatePublic($request->validated());
        return view('hotels.public-index', compact('hotels'));
    }

    public function show(Hotel $hotel): View
    {
        abort_unless($hotel->active, 404);
        $hotel->load(['rooms' => fn ($q) => $q->where('active', true)->orderBy('price_per_night')]);

        return view('hotels.public-show', compact('hotel'));
    }

    public function map(): View
    {
        $hotels = $this->hotels->getActiveForMap()->map(fn (Hotel $hotel) => [
            'name' => $hotel->name,
            'address' => $hotel->formatted_address,
            'latitude' => $hotel->latitude,
            'longitude' => $hotel->longitude,
            'price' => optional($hotel->rooms->first())->price_per_night,
            'url' => route('hotels.show', $hotel),
        ]);

        return view('hotels.map', compact('hotels'));
    }
}
