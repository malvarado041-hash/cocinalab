@extends('layouts.app')
@section('title', 'Registro')
@section('content')
<div class="window-notice">
    <div class="content">
        <div class="auth-brand">
            <img src="{{ asset('img/logo.png') }}" alt="CocinaLab">
            <h1>Crea tu cuenta</h1>
            <p>Únete a CocinaLab y descubre nuevas recetas cada día</p>
        </div>
        @if($errors->any())
        <div class="auth-alert">
            @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
        </div>
        @endif
        <form class="auth-form" action="{{ route('register.post') }}" method="POST">
            @csrf
            <label for="txtNombre">Nombre</label>
            <input type="text" name="txtNombre" id="txtNombre" placeholder="Tu nombre" value="{{ old('txtNombre') }}" required>
            <label for="txtCorreo">Correo</label>
            <input type="email" name="txtCorreo" id="txtCorreo" placeholder="correo@ejemplo.com" value="{{ old('txtCorreo') }}" required>
            <label for="contrasena">Contraseña</label>
            <input type="password" name="contrasena" id="contrasena" placeholder="••••••••" required>
            <input class="auth-submit" type="submit" value="Registrarme">
        </form>
        <p class="auth-switch">¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a></p>
    </div>
</div>
@include('partials.carousel')
@endsection