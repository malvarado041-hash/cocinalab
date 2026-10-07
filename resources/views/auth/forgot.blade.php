@extends('layouts.app')
@section('title', 'Olvidé mi contraseña')
@section('content')
<div class="window-notice">
    <div class="content">
        <div class="auth-brand">
            <img src="{{ asset('img/logo.png') }}" alt="CocinaLab" class="auth-logo">
        </div>
        @if($errors->any())
        @include('genericos.feedback.alertgenerico', ['type' => 'danger', 'message' => $errors->first(), 'dismiss' => true])
        @endif
        <form class="auth-form" action="{{ route('password.forgot.post') }}" method="POST">
            @csrf
            @include('genericos.formularios.inputsgenerico', [
                'name' => 'Usuario',
                'label' => 'Usuario',
                'value' => old('Usuario'),
                'required' => true,
            ])
            @include('genericos.formularios.btnicongenerico', [
                'type' => 'submit',
                'label' => 'Solicitar cambio',
                'icon' => asset('img/logo.png'),
            ])
        </form>
        <p class="auth-switch"><a href="{{ route('login') }}">Volver al inicio de sesión</a></p>
    </div>
</div>
@include('partials.carousel')
@endsection
