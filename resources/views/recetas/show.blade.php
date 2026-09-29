@extends('layouts.app')
@section('title', $receta->Nombre)
@section('content')
<style>
.procedimiento{background-color:#fff;padding:30px;width:60%;margin:50px auto;border-radius:10px;box-shadow:0px 0px 10px #333;}
.boton{background-color:#a66328;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;display:inline-block;margin-top:20px;}
</style>
<div class="procedimiento">
<h2>{{ $receta->Nombre }}</h2>
<p>{!! nl2br(e($receta->Procedimiento)) !!}</p>
<a class="boton" href="{{ route('recetas.index') }}">Volver a Recetas</a>
</div>
@endsection
