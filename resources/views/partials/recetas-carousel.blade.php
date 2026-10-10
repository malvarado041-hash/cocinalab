@props(['titulo', 'recetas', 'carruselId'])

@if ($recetas->isNotEmpty())
<div class="recetas-carousel-section" aria-label="{{ $titulo }}">
    <h2 class="recetas-carousel-title">{{ $titulo }}</h2>
    <div class="recetas-carousel" id="{{ $carruselId }}" role="region" aria-roledescription="carousel" aria-label="{{ $titulo }}">
        <div class="recetas-carousel-track" data-track>
            @foreach ($recetas as $r)
                <article class="receta-card">
                    <a href="{{ route('recetas.show', ['id' => $r->id]) }}" class="receta-card-link">
                        <img src="{{ $r->portada }}" alt="{{ $r->Nombre }}" loading="lazy" class="receta-card-img">
                        <div class="receta-card-body">
                            <span class="receta-card-tipo">{{ $r->TipoC }}</span>
                            <h3 class="receta-card-nombre">{{ $r->Nombre }}</h3>
                            <span class="receta-card-ver">Ver receta →</span>
                        </div>
                    </a>
                </article>
            @endforeach
        </div>
        <button class="recetas-carousel-btn recetas-carousel-btn--prev" aria-label="Anterior" data-prev>
            <i class="fas fa-chevron-left" aria-hidden="true"></i>
        </button>
        <button class="recetas-carousel-btn recetas-carousel-btn--next" aria-label="Siguiente" data-next>
            <i class="fas fa-chevron-right" aria-hidden="true"></i>
        </button>
    </div>
</div>
@endif
