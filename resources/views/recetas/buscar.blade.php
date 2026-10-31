@extends('layouts.app')
@section('title', 'Buscar recetas')
@section('content')
<section class="recetas-resultado">
<h2>Resultados para «{{ $buscar }}»</h2>
<p>{{ $recetas->count() }} coincidencias</p>
<div class="resultados-grid">
@forelse($recetas as $r)
<article class="receta-card"><a href="{{ route('recetas.show', ['id' => $r->id]) }}" class="receta-card-link">
<img class="receta-card-img" src="{{ $r->portada }}" alt="{{ $r->Nombre }}" loading="lazy">
<div class="receta-card-body"><span class="receta-card-tipo">{{ $r->TipoC }}</span><h3 class="receta-card-nombre">{{ $r->Nombre }}</h3><span class="receta-card-ver">Ver receta →</span></div></a></article>
@empty <p role="status">No se encontraron recetas. Prueba con otro nombre.</p> @endforelse
</div></section>
@endsection
