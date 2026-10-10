@extends('layouts.app')
@section('title', 'Filtrar')
@section('content')
<div class="recetas-resultado">
    <h2>Recetas de tipo '{{ $tipo }}' que contienen '{{ $ingrediente }}':</h2>
    @if ($recetas->isNotEmpty())
        <div class="recetas-carousel-section">
            <div class="recetas-carousel-track" style="flex-wrap: wrap;">
                @foreach ($recetas as $r)
                    <article class="receta-card">
                        <a href="{{ route('recetas.show', ['id' => $r->id]) }}" class="receta-card-link">
                            <img src="{{ $r->portada }}" alt="{{ $r->Nombre }}" loading="lazy" class="receta-card-img">
                            <div class="receta-card-body">
                                <span class="receta-card-tipo">{{ $r->TipoC ?? $tipo }}</span>
                                <h3 class="receta-card-nombre">{{ $r->Nombre }}</h3>
                                <span class="receta-card-ver">Ver receta →</span>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>
        </div>
    @else
        <p class="empty-state">No se encontraron recetas con ese ingrediente en esta categoría.</p>
    @endif
</div>
@endsection
