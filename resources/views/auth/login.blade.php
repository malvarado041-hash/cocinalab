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
        @include('genericos.feedback.alertgenerico', ['type' => 'danger', 'message' => $errors->first(), 'dismiss' => true])
        @endif
        <form class="auth-form" action="{{ route('login.post') }}" method="POST">
            @csrf
            @include('genericos.formularios.inputsgenerico', [
                'name' => 'Usuario',
                'label' => 'Usuario',
                'value' => old('Usuario'),
                'required' => true,
            ])
            @include('genericos.formularios.inputsgenerico', [
                'type' => 'password',
                'name' => 'contrasena',
                'label' => 'Contraseña',
                'required' => true,
            ])
            @include('genericos.formularios.btnicongenerico', [
                'type' => 'submit',
                'label' => 'Acceder',
                'icon' => asset('img/logo.png'),
            ])
        </form>
        <p class="auth-switch">¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate</a></p>
    </div>
</div>
@include('partials.carousel')
@endsection