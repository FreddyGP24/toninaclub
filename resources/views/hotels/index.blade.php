@extends('layouts.app')
@section('title', 'Mis hoteles')
@section('content')
<div class="heading"><h1>Hoteles registrados</h1><a class="btn" href="{{ route('panel.hotels.create') }}">Registrar hotel</a></div>
<div class="table"><table>
<thead><tr><th>Hotel</th><th>Ubicación</th><th>Habitaciones</th><th>Estado</th><th>Acciones</th></tr></thead>
<tbody>
@forelse($hotels as $hotel)
<tr>
<td>{{ $hotel->name }}</td><td>{{ $hotel->city }}, {{ $hotel->state }}</td>
<td>{{ $hotel->rooms_count }}</td><td>{{ $hotel->active?'Activo':'Inactivo' }}</td>
<td>
<a href="{{ route('panel.hotels.edit',$hotel) }}">Editar</a> ·
<a href="{{ route('panel.rooms.index',$hotel) }}">Habitaciones</a> ·
<a href="{{ route('hotels.show',$hotel) }}">Ver</a>
@if($hotel->active)
<form class="inline" method="POST" action="{{ route('panel.hotels.destroy',$hotel) }}">@csrf @method('DELETE') <button class="link danger">Desactivar</button></form>
@endif
</td>
</tr>
@empty<tr><td colspan="5">No hay hoteles.</td></tr>@endforelse
</tbody></table></div>
{{ $hotels->links() }}
@endsection
