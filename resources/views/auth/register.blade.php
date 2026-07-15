@extends('layouts.app')
@section('title', 'Registro')
@section('content')
<section class="form-card narrow">
    <h1>Crear cuenta</h1>
    <form method="POST" action="{{ route('register.store') }}">
        @csrf
        <label>Nombre completo
            <input name="name" value="{{ old('name') }}" required>
        </label>
        <label>Correo
            <input type="email" name="email" value="{{ old('email') }}" required>
        </label>
        <label>Tipo de usuario
            <select name="role" required>
                <option value="">Selecciona</option>
                <option value="cliente" @selected(old('role')==='cliente')>Cliente</option>
                <option value="propietario" @selected(old('role')==='propietario')>Propietario</option>
            </select>
        </label>
        <label>Contraseña
            <input type="password" name="password" required minlength="8">
        </label>
        <label>Confirmar contraseña
            <input type="password" name="password_confirmation" required minlength="8">
        </label>
        <button class="btn block" type="submit">Registrarme</button>
    </form>
</section>
@endsection
