@extends('layouts.app')
@section('title','Reservar')
@section('content')
<section class="form-card narrow"><h1>Apartar habitación {{ $room->room_number }}</h1><p>{{ $hotel->name }} · ${{ number_format((float)$room->price_per_night,2) }} por noche</p>
<form method="POST" action="{{ route('reservations.store',[$hotel,$room]) }}">@csrf
<label>Entrada<input type="date" name="check_in" min="{{ now()->toDateString() }}" value="{{ old('check_in') }}" required></label>
<label>Salida<input type="date" name="check_out" min="{{ now()->addDay()->toDateString() }}" value="{{ old('check_out') }}" required></label>
<label>Huéspedes<input type="number" name="guests" min="1" max="{{ $room->capacity }}" value="{{ old('guests',1) }}" required></label>
<label>Notas<textarea name="notes">{{ old('notes') }}</textarea></label>
<button class="btn block">Confirmar apartado</button>
</form></section>
@endsection
