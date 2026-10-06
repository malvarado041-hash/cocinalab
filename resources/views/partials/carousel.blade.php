<div class="carousel-section" aria-label="Recetas destacadas">
    <h2 class="carousel-title">Para ti</h2>

    <div class="carousel" role="region" aria-roledescription="carousel" aria-label="Recetas destacadas">
        <div class="carousel-track" id="carouselTrack">
            <div class="carousel-slide">
                <img src="{{ asset('img/01.jpg') }}" alt="Chiles Rellenos" loading="lazy">
            </div>
            <div class="carousel-slide">
                <img src="{{ asset('img/02.jpg') }}" alt="Chicago" loading="lazy">
            </div>
            <div class="carousel-slide">
                <img src="{{ asset('img/03.jpg') }}" alt="New York" loading="lazy">
            </div>
        </div>

        <button class="carousel-btn carousel-btn--prev" aria-label="Anterior" data-carousel-prev>
            <i class="fas fa-chevron-left" aria-hidden="true"></i>
        </button>
        <button class="carousel-btn carousel-btn--next" aria-label="Siguiente" data-carousel-next>
            <i class="fas fa-chevron-right" aria-hidden="true"></i>
        </button>

        <div class="carousel-indicators" aria-label="Seleccionar slide" role="tablist">
            <button class="carousel-indicator active" data-carousel-index="0" role="tab" aria-selected="true" aria-label="Slide 1"></button>
            <button class="carousel-indicator" data-carousel-index="1" role="tab" aria-selected="false" aria-label="Slide 2"></button>
            <button class="carousel-indicator" data-carousel-index="2" role="tab" aria-selected="false" aria-label="Slide 3"></button>
        </div>
    </div>
</div>

<style>
.carousel-section {
    max-width: 1200px;
    margin: 0 auto 2rem;
    padding: 0 1rem;
}

.carousel-title {
    font-size: clamp(1.5rem, 3.5vw, 2rem);
    font-weight: 700;
    color: #2b2b2b;
    margin: 0 0 1rem;
    text-align: center;
}

.carousel {
    position: relative;
    border-radius: 16px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}

.carousel-track {
    display: flex;
    transition: transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    will-change: transform;
}

.carousel-slide {
    flex: 0 0 100%;
    min-width: 0;
}

.carousel-slide img {
    display: block;
    width: 100%;
    height: auto;
    aspect-ratio: 16 / 9;
    object-fit: cover;
}

.carousel-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    border: none;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.9);
    color: #2b2b2b;
    font-size: 1.25rem;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
    z-index: 10;
}

.carousel-btn:hover {
    background: #fff;
    transform: translateY(-50%) scale(1.1);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
}

.carousel-btn:active {
    transform: translateY(-50%) scale(0.95);
}

.carousel-btn:focus-visible {
    outline: 3px solid #ed7d1e;
    outline-offset: 2px;
}

.carousel-btn--prev {
    left: 12px;
}

.carousel-btn--next {
    right: 12px;
}

.carousel-indicators {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    padding: 1rem;
    background: linear-gradient(180deg, transparent 0%, rgba(0, 0, 0, 0.1) 100%);
}

.carousel-indicator {
    width: 10px;
    height: 10px;
    border: none;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.6);
    cursor: pointer;
    transition: background 0.2s, transform 0.2s;
}

.carousel-indicator:hover {
    background: #fff;
    transform: scale(1.2);
}

.carousel-indicator.active {
    background: #fff;
    box-shadow: 0 0 0 2px #ed7d1e;
}

@media (max-width: 480px) {
    .carousel-section {
        padding: 0 0.5rem;
        margin-bottom: 1.5rem;
    }

    .carousel-btn {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }

    .carousel-btn--prev {
        left: 8px;
    }

    .carousel-btn--next {
        right: 8px;
    }

    .carousel-indicators {
        padding: 0.75rem;
        gap: 0.375rem;
    }

    .carousel-indicator {
        width: 8px;
        height: 8px;
    }
}

@media (min-width: 769px) {
    .carousel-slide img {
        aspect-ratio: 21 / 9;
    }
}
</style>

<script>
(function() {
    const track = document.getElementById('carouselTrack');
    if (!track) return;

    const slides = track.querySelectorAll('.carousel-slide');
    const prevBtn = track.closest('.carousel').querySelector('[data-carousel-prev]');
    const nextBtn = track.closest('.carousel').querySelector('[data-carousel-next]');
    const indicators = track.closest('.carousel').querySelectorAll('[data-carousel-index]');

    let currentIndex = 0;
    const slideCount = slides.length;
    let autoSlideInterval;

    function goToSlide(index) {
        currentIndex = (index + slideCount) % slideCount;
        track.style.transform = `translateX(-${currentIndex * 100}%)`;

        indicators.forEach((indicator, i) => {
            indicator.classList.toggle('active', i === currentIndex);
            indicator.setAttribute('aria-selected', i === currentIndex);
        });
    }

    function nextSlide() {
        goToSlide(currentIndex + 1);
    }

    function prevSlide() {
        goToSlide(currentIndex - 1);
    }

    function startAutoSlide() {
        autoSlideInterval = setInterval(nextSlide, 5000);
    }

    function stopAutoSlide() {
        clearInterval(autoSlideInterval);
    }

    prevBtn?.addEventListener('click', () => {
        prevSlide();
        stopAutoSlide();
        startAutoSlide();
    });

    nextBtn?.addEventListener('click', () => {
        nextSlide();
        stopAutoSlide();
        startAutoSlide();
    });

    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            goToSlide(index);
            stopAutoSlide();
            startAutoSlide();
        });
    });

    const carousel = track.closest('.carousel');
    carousel?.addEventListener('mouseenter', stopAutoSlide);
    carousel?.addEventListener('mouseleave', startAutoSlide);

    carousel?.addEventListener('touchstart', stopAutoSlide, { passive: true });
    carousel?.addEventListener('touchend', startAutoSlide);

    startAutoSlide();

    document.addEventListener('visibilitychange', () => {
        if (document.hidden) stopAutoSlide();
        else startAutoSlide();
    });
})();
</script>