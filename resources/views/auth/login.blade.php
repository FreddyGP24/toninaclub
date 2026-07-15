@extends('layouts.app')
@section('title', 'Iniciar sesión')
@section('content')
<section class="form-card narrow">
    <h1>Iniciar sesión</h1>
    <form method="POST" action="{{ route('login.store') }}">
        @csrf
        <label>Correo
            <input type="email" name="email" value="{{ old('email') }}" required autofocus>
        </label>
        <label>Contraseña
            <input type="password" name="password" required>
        </label>
        <label class="check"><input type="checkbox" name="remember" value="1"> Recordarme</label>
        <button class="btn block" type="submit">Ingresar</button>
    </form>
    <p><a href="{{ route('register') }}">Crear una cuenta</a></p>
</section>
@endsection
