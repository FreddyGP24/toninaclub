@extends('layouts.app')
@section('title','Habitaciones')
@section('content')
<div class="heading"><h1>Habitaciones de {{ $hotel->name }}</h1><a class="btn" href="{{ route('panel.rooms.create',$hotel) }}">Agregar</a></div>
<div class="table"><table><thead><tr><th>Número</th><th>Tipo</th><th>Capacidad</th><th>Precio</th><th>Estado</th><th>Acciones</th></tr></thead><tbody>
@forelse($rooms as $room)
<tr><td>{{ $room->room_number }}</td><td>{{ $room->type }}</td><td>{{ $room->capacity }}</td><td>${{ number_format((float)$room->price_per_night,2) }}</td><td>{{ $room->active?'Activa':'Inactiva' }}</td><td>
<a href="{{ route('panel.rooms.edit',[$hotel,$room]) }}">Editar</a>
@if($room->active)<form class="inline" method="POST" action="{{ route('panel.rooms.destroy',[$hotel,$room]) }}">@csrf @method('DELETE') <button class="link danger">Desactivar</button></form>@endif
</td></tr>
@empty<tr><td colspan="6">No hay habitaciones.</td></tr>@endforelse
</tbody></table></div>
@endsection
