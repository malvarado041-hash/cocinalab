@extends('admin.layout')

@section('title', 'Mi cuenta')
@section('header-title', 'Mi cuenta / Configuración')

@section('content')
<div class="w-full max-w-none">

    <!-- Header de mi cuenta -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6 fade-in">
        <div class="bg-gradient-to-r from-primary-500 to-primary-600 px-6 py-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user-shield text-white text-2xl"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h2 class="text-xl font-bold text-white truncate">{{ $user->name }}</h2>
                    <p class="text-primary-100 capitalize">{{ $user->role ?? 'Sin rol' }} · {{ $user->status }}</p>
                </div>
                <span class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/20 text-white text-sm font-medium">
                    <i class="fas fa-barcode"></i>
                    {{ $user->codigo_empleado ?? '—' }}
                </span>
            </div>
        </div>
        <div class="px-6 py-3 bg-gray-50 border-t border-gray-100 flex flex-wrap items-center gap-x-6 gap-y-1 text-sm text-gray-500">
            <span><i class="fas fa-calendar-alt mr-1.5"></i>Miembro desde {{ $user->created_at->format('d/m/Y') }}</span>
            @if ($user->email)
                <span><i class="fas fa-envelope mr-1.5"></i>{{ $user->email }}</span>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 items-start">

        <!-- Datos personales -->
        <form action="{{ route('admin.profile.update') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden fade-in">
            @csrf
            @method('PUT')
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-id-badge text-blue-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Datos de mi cuenta</h3>
                    <p class="text-sm text-gray-500">Actualiza tu nombre y correo</p>
                </div>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Nombre de usuario</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required minlength="3" maxlength="100"
                           class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none">
                    @error('name')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Correo electrónico</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" maxlength="200" placeholder="tucorreo@ejemplo.com"
                           class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none">
                    @error('email')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Código de empleado</label>
                        <input type="text" value="{{ $user->codigo_empleado ?? '—' }}" disabled
                               class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Rol</label>
                        <input type="text" value="{{ ucfirst($user->role ?? 'Sin rol') }}" disabled
                               class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-500 capitalize">
                    </div>
                </div>
                <div class="flex justify-end pt-2">
                    <button type="submit" class="btn-primary px-6 py-2.5">
                        <i class="fas fa-save mr-2"></i> Guardar datos
                    </button>
                </div>
            </div>
        </form>

        <div class="space-y-6">
            <!-- Contraseña -->
            <form action="{{ route('admin.profile.update') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden fade-in" style="animation-delay: 0.1s;">
                @csrf
                @method('PUT')
                <input type="hidden" name="name" value="{{ $user->name }}">
                <input type="hidden" name="email" value="{{ $user->email }}">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-9 h-9 bg-amber-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-key text-amber-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Cambiar contraseña</h3>
                        <p class="text-sm text-gray-500">Déjala en blanco si no quieres cambiarla</p>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Nueva contraseña</label>
                        <input type="password" id="password" name="password" autocomplete="new-password" placeholder="Mínimo 8 caracteres"
                               class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Confirmar contraseña</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" placeholder="Repite la contraseña"
                               class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none">
                    </div>
                    @error('password')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <div class="flex justify-end">
                        <button type="submit" class="btn-primary px-6 py-2.5">
                            <i class="fas fa-key mr-2"></i> Actualizar contraseña
                        </button>
                    </div>
                </div>
            </form>

            <!-- Apariencia -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden fade-in" style="animation-delay: 0.2s;">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-9 h-9 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-palette text-purple-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Apariencia</h3>
                        <p class="text-sm text-gray-500">Modo claro / oscuro</p>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="font-medium text-gray-800">Modo oscuro</p>
                            <p class="text-sm text-gray-500">Cambia el tema del panel</p>
                        </div>
                        <label class="relative inline-flex cursor-pointer items-center">
                            <input type="checkbox" id="toggleTheme" class="peer sr-only">
                            <span class="h-6 w-11 rounded-full bg-gray-200 transition-colors peer-checked:bg-primary-500 after:absolute after:left-[3px] after:top-[3px] after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition-transform after:content-[''] peer-checked:after:translate-x-5"></span>
                        </label>
                    </div>
                    <div class="flex rounded-xl border border-gray-200 overflow-hidden" role="group" aria-label="Seleccionar tema">
                        <button type="button" data-theme-option="light" class="flex-1 px-4 py-2.5 text-sm font-medium transition hover:bg-gray-50">
                            <i class="fas fa-sun mr-2"></i>Claro
                        </button>
                        <button type="button" data-theme-option="dark" class="flex-1 px-4 py-2.5 text-sm font-medium transition hover:bg-gray-50 border-l border-gray-200">
                            <i class="fas fa-moon mr-2"></i>Oscuro
                        </button>
                        <button type="button" data-theme-option="auto" class="flex-1 px-4 py-2.5 text-sm font-medium transition hover:bg-gray-50 border-l border-gray-200">
                            <i class="fas fa-desktop mr-2"></i>Auto
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sesión -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden fade-in" style="animation-delay: 0.3s;">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-9 h-9 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-sign-out-alt text-green-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Sesión</h3>
                        <p class="text-sm text-gray-500">Accesos rápidos de tu cuenta</p>
                    </div>
                </div>
                <div class="p-6 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('home') }}" class="btn-secondary px-6 py-2.5 text-center flex-1">
                        <i class="fas fa-external-link-alt mr-2"></i> Ver sitio
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="btn-danger px-6 py-2.5 w-full">
                            <i class="fas fa-sign-out-alt mr-2"></i> Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Usa el helper compartido (public/js/theme.js); conserva la UI Claro/Oscuro/Auto de esta vista.
    document.addEventListener('DOMContentLoaded', function() {
        var toggle = document.getElementById('toggleTheme');
        var optionBtns = document.querySelectorAll('[data-theme-option]');

        function currentTheme() {
            if (window.CocinaTheme) return window.CocinaTheme.get();
            try {
                var saved = localStorage.getItem('theme');
                if (saved === 'dark') return 'dark';
                if (saved === 'light') return 'light';
                return 'auto';
            } catch (e) { return 'auto'; }
        }

        function applyTheme(mode) {
            if (window.CocinaTheme) {
                window.CocinaTheme.apply(mode);
            } else {
                try {
                    if (mode === 'dark') {
                        localStorage.setItem('theme', 'dark');
                        document.documentElement.classList.add('dark');
                    } else if (mode === 'light') {
                        localStorage.setItem('theme', 'light');
                        document.documentElement.classList.remove('dark');
                    } else {
                        localStorage.removeItem('theme');
                        var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                        document.documentElement.classList.toggle('dark', prefersDark);
                    }
                } catch (e) {}
            }
            syncUI();
        }

        function syncUI() {
            var mode = currentTheme();
            var isDark = document.documentElement.classList.contains('dark');
            if (toggle) toggle.checked = isDark;
            optionBtns.forEach(function(btn) {
                var active = btn.getAttribute('data-theme-option') === mode;
                btn.classList.toggle('bg-primary-50', active);
                btn.classList.toggle('text-primary-600', active);
                btn.classList.toggle('text-gray-600', !active);
            });
        }

        if (toggle) {
            toggle.addEventListener('change', function() {
                applyTheme(this.checked ? 'dark' : 'light');
            });
        }

        optionBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                applyTheme(btn.getAttribute('data-theme-option'));
            });
        });

        syncUI();
        document.addEventListener('cocinalab:theme', syncUI);
    });
</script>
@endpush
