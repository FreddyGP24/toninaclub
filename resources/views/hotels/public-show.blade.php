@extends('layouts.app')
@section('title', $hotel->name)
@section('content')
<a href="{{ route('home') }}">← Regresar</a>
<div class="heading">
    <div>
        <span class="badge">{{ $hotel->city }}, {{ $hotel->state }}</span>
        <h1>{{ $hotel->name }}</h1>
        <p>{{ $hotel->description }}</p>
        <p><strong>Dirección:</strong> {{ $hotel->formatted_address }}</p>
        <p><strong>Teléfono:</strong> {{ $hotel->phone }}</p>
    </div>
</div>

@if($hotel->latitude && $hotel->longitude)
    <div id="single-map" class="map"></div>
@endif

<h2>Habitaciones</h2>
<div class="grid">
@forelse($hotel->rooms as $room)
    <article class="card">
        <span class="badge">{{ $room->type }}</span>
        <h3>Habitación {{ $room->room_number }}</h3>
        <p>{{ $room->description }}</p>
        <p>Capacidad: {{ $room->capacity }}</p>
        <h3>${{ number_format((float)$room->price_per_night,2) }} / noche</h3>
        @auth
            @if(auth()->user()->isClient())
                <a class="btn" href="{{ route('reservations.create',[$hotel,$room]) }}">Apartar</a>
            @endif
        @else
            <a class="btn" href="{{ route('login') }}">Inicia sesión para reservar</a>
        @endauth
    </article>
@empty
    <div class="card">No hay habitaciones disponibles.</div>
@endforelse
</div>
@endsection

@if($hotel->latitude && $hotel->longitude)
@push('scripts')
<script>
window.initSingleMap = async function () {
    const position = {lat:Number(@json($hotel->latitude)), lng:Number(@json($hotel->longitude))};
    const {Map} = await google.maps.importLibrary('maps');
    const {AdvancedMarkerElement} = await google.maps.importLibrary('marker');
    const map = new Map(document.getElementById('single-map'), {
        center: position, zoom: 16,
        mapId: @json(config('services.google_maps.map_id'))
    });
    new AdvancedMarkerElement({map, position, title:@json($hotel->name)});
};
</script>
<script async src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.browser_key') }}&loading=async&callback=initSingleMap&language=es&region=MX"></script>
@endpush
@endif
