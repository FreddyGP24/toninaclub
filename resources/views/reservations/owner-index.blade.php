@extends('layouts.app')
@section('title', 'Reservaciones recibidas')
@section('content')
<h1>Reservaciones recibidas</h1>
<div class="table">
    <table>
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Hotel / habitación</th>
                <th>Fechas</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Actualizar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reservations as $r)
            <tr>
                <td>{{ $r->client->name }}<br>{{ $r->client->email }}</td>
                <td>{{ $r->room->hotel->name }} / {{ $r->room->room_number }}</td>
                <td>{{ $r->check_in->format('d/m/Y') }} - {{ $r->check_out->format('d/m/Y') }}</td>
                <td>${{ number_format((float) $r->total, 2) }}</td>
                <td>{{ $r->status->label() }}</td>
                <td>
                    @php($options = $r->status === \App\Enums\ReservationStatus::PENDING ? [\App\Enums\ReservationStatus::CONFIRMED, \App\Enums\ReservationStatus::CANCELLED] : ($r->status === \App\Enums\ReservationStatus::CONFIRMED ? [\App\Enums\ReservationStatus::COMPLETED, \App\Enums\ReservationStatus::CANCELLED] : []))
                    @if($options)
                        <form method="POST" action="{{ route('panel.reservations.status', $r) }}">@csrf
                            @method('PATCH')<select name="status">@foreach($options as $o)<option value="{{ $o->value }}">
                            {{ $o->label() }}</option>@endforeach</select><button class="btn small">Guardar</button>
                    </form>@endif
                </td>
            </tr>
            @empty<tr>
                <td colspan="6">No hay reservaciones.</td>
            </tr>@endforelse
        </tbody>
    </table>
</div>{{ $reservations->links() }}
@endsection