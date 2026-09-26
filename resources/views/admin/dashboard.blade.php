@extends('admin.layout')

@section('title', 'Dashboard')

@section('header-title', 'Dashboard')

@section('header-actions')
@endsection

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <article class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow fade-in">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Usuarios</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalUsers }}</p>
            </div>
            <div class="w-14 h-14 bg-primary-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-users text-primary-600 text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
            <a href="{{ route('admin.users.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium flex items-center gap-1">
                Ver todos <i class="fas fa-arrow-right text-xs"></i>
            </a>
            <span class="text-xs text-gray-400">+12% vs mes anterior</span>
        </div>
    </article>

    <article class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow fade-in" style="animation-delay: 0.1s;">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Pendientes</p>
                <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $pendingUsers }}</p>
            </div>
            <div class="w-14 h-14 bg-yellow-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-clock text-yellow-600 text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
            <a href="{{ route('admin.users.index') }}?status=pendiente" class="text-sm text-yellow-600 hover:text-yellow-700 font-medium flex items-center gap-1">
                Revisar <i class="fas fa-arrow-right text-xs"></i>
            </a>
            <span class="text-xs text-gray-400">Requieren acción</span>
        </div>
    </article>

    <article class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow fade-in" style="animation-delay: 0.2s;">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Activos</p>
                <p class="text-3xl font-bold text-green-600 mt-1">{{ $activeUsers }}</p>
            </div>
            <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-check-circle text-green-600 text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
            <a href="{{ route('admin.users.index') }}?status=activo" class="text-sm text-green-600 hover:text-green-700 font-medium flex items-center gap-1">
                Ver activos <i class="fas fa-arrow-right text-xs"></i>
            </a>
            <span class="text-xs text-gray-400">{{ $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100) : 0 }}% del total</span>
        </div>
    </article>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden fade-in">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">Usuarios Recientes</h3>
            <a href="{{ route('admin.users.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">Ver todos</a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse ($recentUsers as $user)
                <div class="px-6 py-4 flex items-center gap-4 hover:bg-gray-50 transition-colors">
                    @php
                        $avatarColors = [
                            'admin' => ['bg' => 'purple-100', 'text' => 'purple-600', 'icon' => 'fa-user-shield'],
                            'cocinero' => ['bg' => 'orange-100', 'text' => 'orange-600', 'icon' => 'fa-utensils'],
                            'mesero' => ['bg' => 'blue-100', 'text' => 'blue-600', 'icon' => 'fa-concierge-bell'],
                            'capitan' => ['bg' => 'indigo-100', 'text' => 'indigo-600', 'icon' => 'fa-user-tie'],
                            'almacen' => ['bg' => 'green-100', 'text' => 'green-600', 'icon' => 'fa-boxes'],
                            'cajero' => ['bg' => 'teal-100', 'text' => 'teal-600', 'icon' => 'fa-cash-register'],
                        ];
                        $avatar = $avatarColors[$user->role ?? ''] ?? ['bg' => 'gray-100', 'text' => 'gray-400', 'icon' => 'fa-user'];
                    @endphp
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-{{ $avatar['bg'] }}">
                        <i class="fas {{ $avatar['icon'] }} text-lg text-{{ $avatar['text'] }}"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-800 truncate">{{ $user->name }}</p>
                        <p class="text-sm text-gray-500 truncate font-mono tracking-wider">{{ $user->codigo_empleado ? 'Código: ' . $user->codigo_empleado : 'Sin código asignado' }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : ($user->role ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600') }}">
                            {{ ucfirst($user->role ?? 'Sin rol') }}
                        </span>
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $user->status === 'activo' ? 'bg-green-100 text-green-700' : ($user->status === 'pendiente' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </div>
                    <time class="text-sm text-gray-400 whitespace-nowrap">{{ $user->created_at->format('d/m/Y H:i') }}</time>
                </div>
            @empty
                <div class="px-6 py-12 text-center text-gray-500">
                    <i class="fas fa-users text-3xl text-gray-300 mb-2"></i>
                    <p>No hay usuarios registrados</p>
                </div>
            @endforelse
        </div>
    </section>

    <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden fade-in">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">Distribución por Roles</h3>
        </div>
        <div class="p-6 space-y-4">
            @php
                $roles = ['admin' => 'Administradores', 'cocinero' => 'Cocineros', 'mesero' => 'Meseros', 'capitan' => 'Capitanes', 'almacen' => 'Almacén', 'cajero' => 'Cajeros'];
                $roleColors = ['admin' => 'purple', 'cocinero' => 'orange', 'mesero' => 'blue', 'capitan' => 'indigo', 'almacen' => 'green', 'cajero' => 'teal'];
                $roleIcons = ['admin' => 'fa-user-shield', 'cocinero' => 'fa-utensils', 'mesero' => 'fa-concierge-bell', 'capitan' => 'fa-user-tie', 'almacen' => 'fa-boxes', 'cajero' => 'fa-cash-register'];
            @endphp
            @foreach ($roles as $key => $label)
                @php
                    $count = \App\Models\User::where('role', $key)->count();
                    $percentage = $totalUsers > 0 ? round(($count / $totalUsers) * 100) : 0;
                @endphp
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-{{ $roleColors[$key] }}-100 rounded-lg flex items-center justify-center">
                                <i class="fas {{ $roleIcons[$key] }} text-{{ $roleColors[$key] }}-600 text-sm"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-800">{{ $count }}</span>
                    </div>
                    <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-{{ $roleColors[$key] }}-500 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 text-right">{{ $percentage }}% del total</p>
                </div>
            @endforeach
        </div>
    </section>
</div>
@endsection