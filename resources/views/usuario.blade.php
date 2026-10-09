@extends('layouts.app')
@section('title', 'Usuario')
@section('content')
<div class="usuario-card">
    <h1>Bienvenido</h1>
    <h2>Información del usuario</h2>
    <dl>
        <div class="usuario-dato">
            <dt>Usuario</dt>
            <dd>{{ auth()->user()->name }}</dd>
        </div>
        <div class="usuario-dato">
            <dt>Correo</dt>
            <dd>{{ auth()->user()->email }}</dd>
        </div>
    </dl>
</div>
@endsection
