@extends('layouts.app')
@section('title', 'Registro')
@section('content')
<style>
.window-notice{background:rgba(33,41,52,.85);left:0;bottom:0;right:0;top:0;display:flex;position:fixed;z-index:999;}
.window-notice .content{background:#fff;border-radius:2px;box-shadow:0 1px 3px rgba(33,41,52,.75);box-sizing:content-box;display:flex;flex-direction:column;margin:auto;max-width:600px;min-width:320px!important;overflow:hidden;position:relative;width:100%;padding:2rem;font-size:1.3rem;}
</style>
<div class="window-notice"><div class="content">
@if($errors->any())<div class="alert alert-danger">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>@endif
<form action="{{ route('register.post') }}" method="POST">
@csrf
<center><table>
<tr><td>Nombre:</td><td><input type="text" name="txtNombre" value="{{ old('txtNombre') }}" required></td></tr>
<tr><td>Correo:</td><td><input type="email" name="txtCorreo" value="{{ old('txtCorreo') }}" required></td></tr>
<tr><td>Contraseña:</td><td><input type="password" name="contrasena" required></td></tr>
<tr><td colspan="2"><input type="submit" value="Agregar"></td></tr>
</table></center>
</form>
<center><a href="{{ route('login') }}"><button>Cancelar</button></a></center>
</div></div>
@include('partials.carousel')
@endsection
