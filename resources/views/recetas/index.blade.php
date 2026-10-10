@extends('layouts.app')
@section('title', 'Recetas')
@section('content')
@foreach ($catalogoPorTipo ?? [] as $tipo => $lista)
    @include('partials.recetas-carousel', ['titulo' => $tipo, 'recetas' => $lista, 'carruselId' => 'catalogo-' . $tipo])
@endforeach

<div class="ingredients-menu" aria-label="Filtrar recetas por ingrediente">
    <h2 class="recetas-carousel-title">Filtrar por ingrediente</h2>
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

<script>
(function() {
    document.querySelectorAll('.recetas-carousel').forEach(function(car) {
        const track = car.querySelector('[data-track]');
        const prev = car.querySelector('[data-prev]');
        const next = car.querySelector('[data-next]');
        function step() {
            const card = track.querySelector('.receta-card');
            return card ? card.getBoundingClientRect().width + 16 : 280;
        }
        prev?.addEventListener('click', () => track.scrollBy({ left: -step(), behavior: 'smooth' }));
        next?.addEventListener('click', () => track.scrollBy({ left: step(), behavior: 'smooth' }));
    });
})();
</script>
@endsection
