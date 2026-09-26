@extends('admin.layout')

@section('title', 'Editar Usuario: ' . $user->name)

@section('header-title', 'Editar Usuario')

@section('header-actions')
<a href="{{ route('admin.users.index') }}" class="btn-secondary">
    <i class="fas fa-arrow-left mr-2"></i> Volver
</a>
@endsection

@section('content')
<div class="w-full max-w-none">

    <!-- User Header Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6 fade-in">
        <div class="bg-gradient-to-r from-primary-500 to-primary-600 px-6 py-6">
            <div class="flex items-center gap-4">
                @php
                    $avatarIcons = [
                        'admin' => 'fa-user-shield',
                        'cocinero' => 'fa-utensils',
                        'mesero' => 'fa-concierge-bell',
                        'capitan' => 'fa-user-tie',
                        'almacen' => 'fa-boxes',
                        'cajero' => 'fa-cash-register',
                    ];
                    $avatarIcon = $avatarIcons[$user->role ?? ''] ?? 'fa-user';
                @endphp
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
                    <i class="fas {{ $avatarIcon }} text-white text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white">{{ $user->name }}</h2>
                    @if ($user->codigo_empleado)
                        <p class="text-primary-100">Código: {{ $user->codigo_empleado }}</p>
                    @endif
                </div>
                <a href="{{ route('admin.users.index') }}" class="btn-secondary px-6 py-2.5 text-center">
                Cancelar
            </a>
            <button type="submit" class="btn-primary px-6 py-2.5">
                <i class="fas fa-save mr-2"></i> Guardar cambios
            </button>
            </div>
        </div>
        <div class="p-6 flex flex-wrap items-center justify-between gap-4 border-t border-gray-100 bg-gray-50">
            <div class="flex flex-wrap items-center gap-3">
                @php
                    $statusColors = [
                        'activo' => ['bg' => 'green-100', 'text' => 'green-700', 'icon' => 'fa-check-circle'],
                        'pendiente' => ['bg' => 'yellow-100', 'text' => 'yellow-700', 'icon' => 'fa-clock'],
                        'inactivo' => ['bg' => 'red-100', 'text' => 'red-700', 'icon' => 'fa-ban'],
                    ];
                    $s = $statusColors[$user->status] ?? ['bg' => 'gray-100', 'text' => 'gray-700', 'icon' => 'fa-question'];
                    $roleColors = [
                        'admin' => ['bg' => 'purple-100', 'text' => 'purple-700', 'icon' => 'fa-user-shield'],
                        'cocinero' => ['bg' => 'orange-100', 'text' => 'orange-700', 'icon' => 'fa-utensils'],
                        'mesero' => ['bg' => 'blue-100', 'text' => 'blue-700', 'icon' => 'fa-concierge-bell'],
                        'capitan' => ['bg' => 'indigo-100', 'text' => 'indigo-700', 'icon' => 'fa-user-tie'],
                        'almacen' => ['bg' => 'green-100', 'text' => 'green-700', 'icon' => 'fa-boxes'],
                        'cajero' => ['bg' => 'teal-100', 'text' => 'teal-700', 'icon' => 'fa-cash-register'],
                    ];
                    $r = $roleColors[$user->role ?? ''] ?? ['bg' => 'gray-100', 'text' => 'gray-700', 'icon' => 'fa-user'];
                @endphp
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-{{ $s['bg'] }} text-{{ $s['text'] }} font-medium">
                    <i class="fas {{ $s['icon'] }}"></i>
                    {{ ucfirst($user->status) }}
                </span>
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-{{ $r['bg'] }} text-{{ $r['text'] }} font-medium">
                    <i class="fas {{ $r['icon'] }}"></i>
                    {{ ucfirst($user->role ?? 'Sin rol') }}
                </span>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-500">Registrado</p>
                <p class="text-sm font-medium text-gray-800">{{ $user->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="grid grid-cols-1 xl:grid-cols-2 gap-6 items-start">
        @csrf
        @method('PUT')

        <!-- Permisos -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden fade-in" style="animation-delay: 0.1s;">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                <div class="w-9 h-9 bg-primary-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user-tag text-primary-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Permisos de la Cuenta</h3>
                    <p class="text-sm text-gray-500">Selecciona el rol y estado del usuario</p>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                    <!-- Role Selector -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Rol del Usuario</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3" role="radiogroup" aria-label="Seleccionar rol">
                            @php
                                $roleOptions = [
                                    'cocinero' => ['label' => 'Cocinero', 'icon' => 'fa-utensils', 'color' => 'orange', 'desc' => 'Prepara alimentos'],
                                    'mesero' => ['label' => 'Mesero', 'icon' => 'fa-concierge-bell', 'color' => 'blue', 'desc' => 'Atiende mesas'],
                                    'capitan' => ['label' => 'Capitán', 'icon' => 'fa-user-tie', 'color' => 'indigo', 'desc' => 'Supervisa sala'],
                                    'almacen' => ['label' => 'Almacén', 'icon' => 'fa-boxes', 'color' => 'green', 'desc' => 'Gestiona inventario'],
                                    'cajero' => ['label' => 'Cajero', 'icon' => 'fa-cash-register', 'color' => 'teal', 'desc' => 'Procesa pagos'],
                                    'admin' => ['label' => 'Admin', 'icon' => 'fa-user-shield', 'color' => 'purple', 'desc' => 'Acceso total'],
                                ];
                            @endphp
                            @foreach ($roleOptions as $key => $opt)
                                @php
                                    $checked = $user->role === $key;
                                @endphp
                                <label class="relative block cursor-pointer">
                                    <input type="radio" name="role" value="{{ $key }}"
                                           class="peer sr-only" {{ $checked ? 'checked' : '' }} required>
                                    <div class="rounded-xl border-2 border-gray-200 bg-white p-4 text-center transition-all duration-150
                                                hover:border-gray-300 hover:bg-gray-50
                                                peer-checked:border-{{ $opt['color'] }}-500 peer-checked:bg-{{ $opt['color'] }}-50 peer-focus:ring-2 peer-focus:ring-{{ $opt['color'] }}-200">
                                        <div class="mx-auto mb-3 flex h-11 w-11 items-center justify-center rounded-xl bg-{{ $opt['color'] }}-100">
                                            <i class="fas {{ $opt['icon'] }} text-{{ $opt['color'] }}-600 text-lg"></i>
                                        </div>
                                        <p class="font-semibold text-gray-800">{{ $opt['label'] }}</p>
                                        <p class="mt-1 text-xs text-gray-500">{{ $opt['desc'] }}</p>
                                    </div>
                                    <span class="pointer-events-none absolute -right-1.5 -top-1.5 hidden h-5 w-5 items-center justify-center rounded-full bg-{{ $opt['color'] }}-500 text-[10px] text-white peer-checked:flex" aria-hidden="true">
                                        <i class="fas fa-check"></i>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @error('role')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Selector -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Estado de la Cuenta</label>
                        <div class="grid grid-cols-3 gap-3" role="radiogroup" aria-label="Seleccionar estado">
                            @php
                                $statusOptions = [
                                    'activo' => ['label' => 'Activo', 'icon' => 'fa-check-circle', 'color' => 'green', 'desc' => 'Acceso completo al sistema'],
                                    'pendiente' => ['label' => 'Pendiente', 'icon' => 'fa-clock', 'color' => 'yellow', 'desc' => 'Espera aprobación'],
                                    'inactivo' => ['label' => 'Inactivo', 'icon' => 'fa-ban', 'color' => 'red', 'desc' => 'Sin acceso al sistema'],
                                ];
                            @endphp
                            @foreach ($statusOptions as $key => $opt)
                                @php
                                    $checked = $user->status === $key;
                                @endphp
                                <label class="relative block cursor-pointer">
                                    <input type="radio" name="status" value="{{ $key }}"
                                           class="peer sr-only" {{ $checked ? 'checked' : '' }} required>
                                    <div class="rounded-xl border-2 border-gray-200 bg-white p-4 text-center transition-all duration-150
                                                hover:border-gray-300 hover:bg-gray-50
                                                peer-checked:border-{{ $opt['color'] }}-500 peer-checked:bg-{{ $opt['color'] }}-50 peer-focus:ring-2 peer-focus:ring-{{ $opt['color'] }}-200">
                                        <div class="mx-auto mb-3 flex h-11 w-11 items-center justify-center rounded-xl bg-{{ $opt['color'] }}-100">
                                            <i class="fas {{ $opt['icon'] }} text-{{ $opt['color'] }}-600 text-lg"></i>
                                        </div>
                                        <p class="font-semibold text-gray-800">{{ $opt['label'] }}</p>
                                        <p class="mt-1 text-xs text-gray-500">{{ $opt['desc'] }}</p>
                                    </div>
                                    <span class="pointer-events-none absolute -right-1.5 -top-1.5 hidden h-5 w-5 items-center justify-center rounded-full bg-{{ $opt['color'] }}-500 text-[10px] text-white peer-checked:flex" aria-hidden="true">
                                        <i class="fas fa-check"></i>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @error('status')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    
                </div>
            </div>
        </div>

        <!-- Columna derecha -->
        <div class="space-y-6">
            <!-- Contraseña -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden fade-in" style="animation-delay: 0.2s;">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-9 h-9 bg-amber-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-key text-amber-600"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-800">Contraseña</h3>
                        <p class="text-sm text-gray-500">Opcional: establece una nueva contraseña para el usuario</p>
                    </div>
                    <label class="relative inline-flex cursor-pointer select-none items-center gap-3">
                        <span class="text-sm font-medium text-gray-600">Cambiar</span>
                        <input type="checkbox" id="togglePassword" class="peer sr-only">
                        <span class="relative h-6 w-11 rounded-full bg-gray-200 transition-colors peer-checked:bg-primary-500 after:absolute after:left-[3px] after:top-[3px] after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition-transform after:content-[''] peer-checked:after:translate-x-5"></span>
                    </label>
                </div>

                <div id="passwordFields" class="hidden p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Nueva contraseña</label>
                            <input type="password" id="password" name="password" autocomplete="new-password"
                                   disabled placeholder="Mínimo 8 caracteres"
                                   class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none disabled:bg-gray-50 disabled:text-gray-400">
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Confirmar contraseña</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password"
                                   disabled placeholder="Repite la contraseña"
                                   class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none disabled:bg-gray-50 disabled:text-gray-400">
                        </div>
                    </div>
                    <div class="mt-3 flex items-start gap-2 rounded-xl bg-amber-50 border border-amber-200 px-4 py-3">
                        <i class="fas fa-info-circle text-amber-500 mt-0.5"></i>
                        <p class="text-xs text-amber-700">Si dejas los campos vacíos, la contraseña actual del usuario se mantiene sin cambios.</p>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Datos del Usuario -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden fade-in" style="animation-delay: 0.3s;">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-id-badge text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Datos del Usuario</h3>
                        <p class="text-sm text-gray-500">Nombre de usuario y código de empleado</p>
                    </div>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Nombre de usuario</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-gray-400">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                                   required minlength="3" maxlength="100" autocomplete="username"
                                   class="w-full rounded-xl border border-gray-300 py-2.5 pl-10 pr-4 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none">
                        </div>
                        @error('name')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="codigo_empleado" class="block text-sm font-medium text-gray-700 mb-1.5">Código de empleado</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-gray-400">
                                <i class="fas fa-barcode"></i>
                            </span>
                            <input type="text" id="codigo_empleado" name="codigo_empleado"
                                   value="{{ old('codigo_empleado', $user->codigo_empleado) }}"
                                   required digits="6" minlength="6" maxlength="6" inputmode="numeric"
                                   pattern="[0-9]{6}" placeholder="Ej. 102458"
                                   class="w-full rounded-xl border border-gray-300 py-2.5 pl-10 pr-4 text-sm tracking-widest focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none">
                        </div>
                        <p class="mt-1.5 text-xs text-gray-500">Exactamente 6 dígitos. Debe ser único para cada usuario.</p>
                        @error('codigo_empleado')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pb-2 xl:col-span-2">
            <a href="{{ route('admin.users.index') }}" class="btn-secondary px-6 py-2.5 text-center">
                Cancelar
            </a>
            <button type="submit" class="btn-primary px-6 py-2.5">
                <i class="fas fa-save mr-2"></i> Guardar cambios
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggle = document.getElementById('togglePassword');
        const wrap = document.getElementById('passwordFields');
        const inputs = wrap.querySelectorAll('input[type="password"]');

        toggle.addEventListener('change', function() {
            const enabled = this.checked;
            wrap.classList.toggle('hidden', !enabled);
            inputs.forEach(function(input) {
                input.disabled = !enabled;
                if (!enabled) input.value = '';
            });
            if (enabled) document.getElementById('password').focus();
        });
    });
</script>
@endpush
