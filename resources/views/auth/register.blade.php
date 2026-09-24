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
        <div class="gen-alert gen-alert-danger">
            @foreach($errors->all() as $e)
            <div>{{ $e }}</div>
            @endforeach
        </div>
        @endif
        <form class="auth-form" action="{{ route('register.post') }}" method="POST">
            @csrf
            @include('genericos.inputsgenerico', [
                'name' => 'txtNombre',
                'label' => 'Nombre',
                'value' => old('txtNombre'),
                'required' => true,
            ])
            @include('genericos.inputsgenerico', [
                'type' => 'email',
                'name' => 'txtCorreo',
                'label' => 'Correo',
                'value' => old('txtCorreo'),
                'required' => true,
            ])
            @include('genericos.inputsgenerico', [
                'type' => 'password',
                'name' => 'contrasena',
                'label' => 'Contraseña',
                'required' => true,
            ])
            @include('genericos.btnsgenerico', [
                'type' => 'submit',
                'label' => 'Registrarme',
            ])
        </form>
        <p class="auth-switch">¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a></p>
    </div>
</div>
@include('partials.carousel')
@endsection