@extends('layouts.app')
@section('title', 'Login')
@section('content')
<div class="window-notice">
    <div class="content">
        <div class="auth-brand">
            <img src="{{ asset('img/logo.png') }}" alt="CocinaLab">
            <h1>Bienvenido a CocinaLab</h1>
            <p>Ingresa para explorar las mejores recetas</p>
        </div>
        @if($errors->any())
        <div class="auth-alert">{{ $errors->first() }}</div>
        @endif
        <form class="auth-form" action="{{ route('login.post') }}" method="POST">
            @csrf
            <label for="Usuario">Usuario</label>
            <input type="text" name="Usuario" id="Usuario" placeholder="Tu usuario" required value="{{ old('Usuario') }}">
            <label for="contrasena">Contraseña</label>
            <input type="password" name="contrasena" id="contrasena" placeholder="••••••••" required>
            <input class="auth-submit" type="submit" value="Acceder">
        </form>
        <p class="auth-switch">¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate</a></p>
    </div>
</div>
@include('partials.carousel')
@endsection