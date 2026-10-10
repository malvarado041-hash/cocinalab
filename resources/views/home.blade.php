@extends('layouts.app')
@section('title', 'CocinaLab')
@section('content')
@include('partials.carousel')

@if (($destacadas ?? collect())->isNotEmpty())
    @include('partials.recetas-carousel', ['titulo' => 'Catálogo de recetas', 'recetas' => $destacadas, 'carruselId' => 'home-catalogo'])
@else
    <p class="empty-state">Aún no hay recetas en el catálogo.</p>
@endif

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
