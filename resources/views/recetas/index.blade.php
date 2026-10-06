@extends('layouts.app')
@section('title', 'Recetas')
@section('content')
<div class="ingredients-menu" aria-label="Filtrar recetas por ingrediente">
    @forelse($ingredientesPorTipo as $tipo => $ingredientes)
    <details class="ingredient-group">
        <summary class="ingredient-type">{{ $tipo }}</summary>
        <ul class="ingredient-list">
            @foreach($ingredientes as $ingrediente)
            <li>
                <a href="{{ route('recetas.filtrar', ['tipo' => $tipo, 'ingrediente' => $ingrediente]) }}">
                    {{ $ingrediente }}
                </a>
            </li>
            @endforeach
        </ul>
    </details>
    @empty
    <p class="empty-state">No hay ingredientes para mostrar.</p>
    @endforelse
</div>
@endsection