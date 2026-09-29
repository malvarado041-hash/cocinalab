<header class="bg-white shadow-sm sticky top-0 z-20">
    <div class="flex items-center justify-between px-6 py-4">
        <div class="flex items-center gap-4">
            <h2 class="text-xl font-semibold text-gray-800">@yield('header-title', 'Panel de Administración')</h2>
            @yield('header-actions')
        </div>

        <div class="flex items-center gap-2">
            <button type="button" data-theme-toggle aria-pressed="false" title="Cambiar a modo oscuro"
                    class="w-9 h-9 rounded-full flex items-center justify-center transition hover:bg-gray-100">
                <i data-theme-icon class="fas fa-moon text-gray-500"></i>
            </button>

            <a href="{{ route('admin.profile.edit') }}" title="Mi cuenta / Configuración"
               class="flex items-center gap-3 rounded-xl px-2 py-1 transition hover:bg-gray-50">
                <div class="hidden md:block text-right">
                    <p class="text-sm font-medium text-gray-800">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500 capitalize">{{ Auth::user()->role }}</p>
                </div>
                <div class="w-9 h-9 bg-primary-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-shield text-primary-600"></i>
                </div>
            </a>
        </div>
    </div>
</header>
