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
        @include('genericos.feedback.alertgenerico', ['type' => 'danger', 'message' => $errors->first(), 'dismiss' => true])
        @endif
        <form class="auth-form" action="{{ route('register.post') }}" method="POST">
            @csrf
            @include('genericos.formularios.inputsgenerico', [
                'name' => 'txtNombre',
                'label' => 'Nombre',
                'value' => old('txtNombre'),
                'required' => true,
            ])
            @include('genericos.formularios.inputsgenerico', [
                'type' => 'email',
                'name' => 'txtCorreo',
                'label' => 'Correo',
                'value' => old('txtCorreo'),
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
                'label' => 'Registrarme',
                'icon' => asset('img/logo.png'),
            ])
        </form>
        <p class="auth-switch">¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a></p>
    </div>
</div>
@include('partials.carousel')
@endsection