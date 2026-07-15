@extends('layouts.app')
@section('title','Registrar hotel')
@section('content')
<section class="form-card"><h1>Registrar hotel</h1><p>Google Geocoding obtendrá las coordenadas de la dirección.</p>
<form method="POST" action="{{ route('panel.hotels.store') }}">@csrf
@include('hotels._form')
<button class="btn" type="submit">Guardar hotel</button>
</form></section>
@endsection
