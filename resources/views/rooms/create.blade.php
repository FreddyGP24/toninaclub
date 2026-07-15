@extends('layouts.app')
@section('title','Agregar habitación')
@section('content')
<section class="form-card"><h1>Agregar habitación a {{ $hotel->name }}</h1>
<form method="POST" action="{{ route('panel.rooms.store',$hotel) }}">@csrf @include('rooms._form')<button class="btn">Guardar</button></form>
</section>
@endsection
