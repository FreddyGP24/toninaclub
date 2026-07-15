@extends('layouts.app')
@section('title','Editar habitación')
@section('content')
<section class="form-card"><h1>Editar habitación {{ $room->room_number }}</h1>
<form method="POST" action="{{ route('panel.rooms.update',[$hotel,$room]) }}">@csrf @method('PUT') @include('rooms._form')<button class="btn">Guardar</button></form>
</section>
@endsection
