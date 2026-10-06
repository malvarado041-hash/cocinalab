<!DOCTYPE html>
<html lang="es" @if(request()->routeIs('login', 'register', 'password.forgot')) data-force-light="1" @endif>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CocinaLab')</title>
    <link rel="stylesheet" href="{{ asset('css/boton.css') }}?v=2">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v=3">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}?v=2">
    <link rel="stylesheet" href="{{ asset('css/genericos.css') }}?v=2">
    <link rel="stylesheet" href="{{ asset('css/site-dark.css') }}?v=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        (function() {
            try {
                if (document.documentElement.hasAttribute('data-force-light')) {
                    document.documentElement.classList.remove('dark');
                    return;
                }
                var theme = localStorage.getItem('theme');
                if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                }
            } catch (error) {}
        })();
    </script>
</head>
<body style="background-color:#B9B9B9;">
<div class="contenedor">
    <header class="site-header">
        <div class="header-brand">
            <img src="{{ asset('img/logo.png') }}" alt="CocinaLab" class="logo-img">
            <h1 class="site-title">CocinaLab</h1>
        </div>

        @auth
        <form action="{{ route('recetas.show') }}" method="GET" class="search-form" role="search">
            <label for="buscar" class="sr-only">Buscar recetas</label>
            <input type="search" id="buscar" class="buscador" name="buscar" placeholder="Buscar recetas...">
            <button type="submit" class="btn-search" aria-label="Buscar"><i class="fas fa-search"></i></button>
        </form>
        @endauth

        <nav class="main-nav" aria-label="Navegación principal">
            <button class="nav-toggle" aria-expanded="false" aria-controls="nav-menu" aria-label="Abrir menú" data-nav-toggle>
                <span class="nav-toggle-icon" aria-hidden="true"></span>
            </button>

            <div class="nav-overlay" data-nav-overlay aria-hidden="true"></div>

            <ul class="btnlist" id="nav-menu" role="list">
                <li class="drawer-only" aria-hidden="false">
                    <div class="drawer-head">
                        <span class="drawer-brand">
                            <img src="{{ asset('img/logo.png') }}" alt="">
                            CocinaLab
                        </span>
                        <button type="button" class="drawer-close" data-nav-close aria-label="Cerrar menú">
                            <i class="fas fa-times" aria-hidden="true"></i>
                        </button>
                    </div>
                </li>
                <li>
                    <a class="boton nav-link" href="{{ auth()->check() ? route('home') : route('login') }}">
                        <i class="fas fa-home nav-link-icon" aria-hidden="true"></i><span>Inicio</span>
                    </a>
                </li>
                <li>
                    <a class="boton nav-link" href="{{ auth()->check() ? route('recetas.index') : route('login') }}">
                        <i class="fas fa-utensils nav-link-icon" aria-hidden="true"></i><span>Recetas</span>
                    </a>
                </li>
                <li>
                    <a class="boton nav-link" href="{{ auth()->check() ? route('usuario') : route('login') }}">
                        <i class="fas fa-user nav-link-icon" aria-hidden="true"></i><span>Usuario</span>
                    </a>
                </li>
                <li>
                    <a class="boton nav-link" href="{{ auth()->check() ? route('info') : route('login') }}">
                        <i class="fas fa-circle-info nav-link-icon" aria-hidden="true"></i><span>Información</span>
                    </a>
                </li>
                @auth
                <li>
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button class="boton nav-link" type="submit">
                            <i class="fas fa-sign-out-alt nav-link-icon" aria-hidden="true"></i><span>Salir ({{ auth()->user()->name }})</span>
                        </button>
                    </form>
                </li>
                @endauth
                @unless(request()->routeIs('login', 'register', 'password.forgot'))
                <li>
                    <button type="button" class="theme-toggle-public boton nav-link" data-theme-toggle title="Cambiar tema" aria-label="Cambiar tema">
                        <i data-theme-icon class="fas fa-moon nav-link-icon" aria-hidden="true"></i><span class="drawer-only">Tema</span>
                    </button>
                </li>
                @endunless
            </ul>
        </nav>
    </header>
</div>
@yield('content')
<script src="{{ asset('js/theme.js') }}?v=2"></script>
<script src="{{ asset('js/password-toggle.js') }}?v=1"></script>
<script>
    (function() {
        const toggle = document.querySelector('[data-nav-toggle]');
        const menu = document.getElementById('nav-menu');
        const overlay = document.querySelector('[data-nav-overlay]');
        const closeBtn = document.querySelector('[data-nav-close]');
        if (!toggle || !menu || !overlay) return;

        function closeMenu() {
            toggle.setAttribute('aria-expanded', 'false');
            menu.classList.remove('nav-open');
            overlay.classList.remove('nav-overlay-visible');
            document.body.style.overflow = '';
        }

        function openMenu() {
            toggle.setAttribute('aria-expanded', 'true');
            menu.classList.add('nav-open');
            overlay.classList.add('nav-overlay-visible');
            document.body.style.overflow = 'hidden';
        }

        toggle.addEventListener('click', () => {
            const expanded = toggle.getAttribute('aria-expanded') === 'true';
            expanded ? closeMenu() : openMenu();
        });

        overlay.addEventListener('click', closeMenu);

        if (closeBtn) {
            closeBtn.addEventListener('click', closeMenu);
        }

        menu.querySelectorAll('a, button').forEach(el => {
            el.addEventListener('click', () => {
                if (window.matchMedia('(max-width: 768px)').matches) {
                    closeMenu();
                }
            });
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && menu.classList.contains('nav-open')) {
                closeMenu();
            }
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth > 768 && menu.classList.contains('nav-open')) {
                closeMenu();
            }
        });
    })();
</script>
</body>
</html>