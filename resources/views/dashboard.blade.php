@extends('layouts.app')
@section('title', 'Panel')
@section('content')
<section class="hero">
    <span class="badge">{{ auth()->user()->role->label() }}</span>
    <h1>Hola, {{ auth()->user()->name }}</h1>
    <p>Selecciona una opción para continuar.</p>
</section>
<div class="grid">
    <a class="card" href="{{ route('home') }}"><h2>Buscar hoteles</h2></a>
    <a class="card" href="{{ route('hotels.map') }}"><h2>Mapa de hoteles</h2></a>
    @if(auth()->user()->isClient())
        <a class="card" href="{{ route('reservations.index') }}"><h2>Mis reservaciones</h2></a>
    @else
        <a class="card" href="{{ route('panel.hotels.index') }}"><h2>Administrar hoteles</h2></a>
    @endif
</div>
@endsection
