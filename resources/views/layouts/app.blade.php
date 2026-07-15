<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'HotelReserva')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<header>
    <div class="container nav">
        <a class="brand" href="{{ route('home') }}">HotelReserva</a>
        <nav>
            <a href="{{ route('home') }}">Hoteles</a>
            <a href="{{ route('hotels.map') }}">Mapa</a>
            @auth
                @if(auth()->user()->isClient())
                    <a href="{{ route('reservations.index') }}">Mis reservaciones</a>
                @else
                    <a href="{{ route('panel.hotels.index') }}">Mis hoteles</a>
                    <a href="{{ route('panel.reservations.index') }}">Reservaciones</a>
                @endif
                <a href="{{ route('dashboard') }}">Panel</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button class="link" type="submit">Salir</button>
                </form>
            @else
                <a href="{{ route('login') }}">Ingresar</a>
                <a class="btn small" href="{{ route('register') }}">Registrarse</a>
            @endauth
        </nav>
    </div>
</header>

<main class="container">
    @if(session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert error">
            <strong>Revisa la información:</strong>
            <ul>
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</main>

@stack('scripts')
</body>
</html>
