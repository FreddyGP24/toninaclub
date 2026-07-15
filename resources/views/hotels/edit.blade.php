@extends('layouts.app')
@section('title','Editar hotel')
@section('content')
<section class="form-card"><h1>Editar {{ $hotel->name }}</h1>
<form method="POST" action="{{ route('panel.hotels.update',$hotel) }}">@csrf @method('PUT')
@include('hotels._form')
<button class="btn" type="submit">Guardar cambios</button>
</form></section>
@endsection
