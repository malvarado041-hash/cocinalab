@extends('layouts.app')
@section('title', 'Login')
@section('content')
<style>
.window-notice{background:rgba(33,41,52,.85);left:0;bottom:0;right:0;top:0;display:flex;position:fixed;z-index:999;}
.window-notice .content{background:#fff;border-radius:2px;box-shadow:0 1px 3px rgba(33,41,52,.75);box-sizing:content-box;display:flex;flex-direction:column;margin:auto;max-width:600px;min-width:320px!important;overflow:hidden;position:relative;width:100%;padding:2rem;font-size:1.3rem;}
</style>
<div class="window-notice"><div class="content"><div class="login">
<h1>Login</h1>
@if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
<form action="{{ route('login.post') }}" method="POST">
@csrf
<label>Usuario</label>
<input type="text" name="Usuario" placeholder="Usuario" required value="{{ old('Usuario') }}">
<label>Contraseña</label>
<input type="password" name="contrasena" placeholder="Contraseña" required>
<input type="submit" value="Acceder">
</form>
<center><a href="{{ route('register') }}"><button>Registrate</button></a></center>
</div></div></div>
@include('partials.carousel')
@endsection
