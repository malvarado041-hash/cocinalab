@extends('layouts.app')
@section('title', 'Recetas')
@section('content')
<div class="ingredients-menu" aria-label="Filtrar recetas por ingrediente">
    @forelse($ingredientesPorTipo as $tipo => $ingredientes)
    <details class="ingredient-group" name="tipo-receta">
        <summary class="ingredient-type">
            <span>{{ $tipo }}</span>
            <span class="ingredient-count" aria-label="{{ count($ingredientes) }} ingredientes">{{ count($ingredientes) }}</span>
        </summary>
        <div class="ingredient-list">
            <ul class="ingredient-list-inner">
                @foreach($ingredientes as $ingrediente)
                <li>
                    <a href="{{ route('recetas.filtrar', ['tipo' => $tipo, 'ingrediente' => $ingrediente]) }}">
                        {{ $ingrediente }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    </details>
    @empty
    <p class="empty-state">No hay ingredientes para mostrar.</p>
    @endforelse
</div>
@endsection
