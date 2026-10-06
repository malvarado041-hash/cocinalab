@extends('layouts.app')
@section('title', $receta->Nombre)
@section('content')
<div class="receta-detalle">
    <h2>{{ $receta->Nombre }}</h2>
    <p>{!! nl2br(e($receta->Procedimiento)) !!}</p>
    <a class="receta-volver" href="{{ route('recetas.index') }}">Volver a Recetas</a>
</div>
@endsection
