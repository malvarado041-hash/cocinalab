@extends('layouts.app')
@section('title', 'Registro')
@section('content')
@if($errors->any())
<div class="auth-toast" role="alert" aria-live="polite">
    @include('genericos.feedback.alertgenerico', ['type' => 'danger', 'message' => $errors->first(), 'dismiss' => true])
</div>
@endif
<div class="window-notice">
    <div class="content">
        <div class="auth-brand">
            <img src="{{ asset('img/logo.png') }}" alt="CocinaLab" class="auth-logo">
        </div>
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
            <a href="{{ route('login') }}" class="auth-register-btn">Iniciar sesión</a>
        </form>
    </div>
</div>
@include('partials.carousel')
@endsection