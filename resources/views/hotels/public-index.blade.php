@extends('layouts.app')
@section('title', 'Hoteles')
@section('content')
<section class="hero">
    <h1>Encuentra tu próximo hospedaje</h1>
    <p>Consulta hoteles, habitaciones, precios y ubicación.</p>
</section>

<form method="GET" class="search">
    <label>Hotel o lugar
        <input name="q" value="{{ request('q') }}" placeholder="Nombre, ciudad o estado">
    </label>
    <label>Entrada
        <input type="date" name="check_in" value="{{ request('check_in') }}">
    </label>
    <label>Salida
        <input type="date" name="check_out" value="{{ request('check_out') }}">
    </label>
    <label>Huéspedes
        <input type="number" name="guests" min="1" max="20" value="{{ request('guests',1) }}">
    </label>
    <button class="btn" type="submit">Buscar</button>
</form>

<div class="heading"><h2>Hoteles disponibles</h2><a href="{{ route('hotels.map') }}">Ver mapa</a></div>
<div class="grid">
@forelse($hotels as $hotel)
    <article class="card">
        <span class="badge">{{ $hotel->city }}, {{ $hotel->state }}</span>
        <h3>{{ $hotel->name }}</h3>
        <p>{{ \Illuminate\Support\Str::limit($hotel->description, 130) }}</p>
        @php($price = $hotel->rooms->min('price_per_night'))
        <p><strong>{{ $price ? '$'.number_format((float)$price,2).' por noche' : 'Sin habitaciones' }}</strong></p>
        <a class="btn" href="{{ route('hotels.show',$hotel) }}">Ver hotel</a>
    </article>
@empty
    <div class="card">No se encontraron hoteles.</div>
@endforelse
</div>
<div class="pagination">{{ $hotels->links() }}</div>
@endsection
