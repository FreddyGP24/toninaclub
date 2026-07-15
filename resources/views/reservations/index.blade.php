@extends('layouts.app')
@section('title', 'Mis reservaciones')
@section('content')
    <h1>Mis reservaciones</h1>
    <div class="table">
        <table>
            <thead>
                <tr>
                    <th>Hotel</th>
                    <th>Fechas</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservations as $reservation)
                    <tr>
                        <td>{{ $reservation->room->hotel->name }}<br>Hab. {{ $reservation->room->room_number }}</td>
                        <td>{{ $reservation->check_in->format('d/m/Y') }} - {{ $reservation->check_out->format('d/m/Y') }}</td>
                        <td>${{ number_format((float) $reservation->total, 2) }}</td>
                        <td>{{ $reservation->status->label() }}</td>
                        <td>
                            @if(in_array($reservation->status, \App\Enums\ReservationStatus::active(), true))
                                <form method="POST" action="{{ route('reservations.cancel', $reservation) }}">@csrf
                                    @method('PATCH')<button class="link danger">Cancelar</button></form>
                            @endif
                        </td>
                    </tr>
                @empty<tr>
                    <td colspan="5">No tienes reservaciones.</td>
                </tr>@endforelse
            </tbody>
        </table>
    </div>{{ $reservations->links() }}
@endsection