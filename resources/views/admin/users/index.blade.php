@extends('admin.layout')

@section('title', 'Gestión de Usuarios')

@section('header-title', 'Gestión de Usuarios')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden fade-in">
    <div class="px-6 py-4 flex items-center justify-end gap-3">
        @if ($canRestore ?? false)
        <a href="{{ route('admin.users.trashed') }}" class="btn-secondary text-sm px-4 py-2">
            <i class="fas fa-user-slash mr-2"></i> Dados de baja
        </a>
        @endif
        <div class="relative w-full sm:w-64">
            <i class="fas fa-search text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
            <input type="text" id="search-users" placeholder="Buscar usuarios..." class="pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent w-full transition">
        </div>
    </div>
    <div class="overflow-x-auto users-table-fixed">
        <table class="w-full table-fixed-users" id="users-table">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Usuario</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Código</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Rol</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aprobado por</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Registro</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100" id="users-tbody">
                @forelse ($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors user-row" data-name="{{ strtolower($user->name) }}" data-code="{{ strtolower($user->codigo_empleado ?? '') }}" data-role="{{ strtolower($user->role ?? '') }}" data-status="{{ strtolower($user->status) }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @php
                                    $avatarColors = [
                                        'admin' => ['bg' => 'purple-100', 'text' => 'purple-600', 'icon' => 'fa-user-shield'],
                                        'cocinero' => ['bg' => 'orange-100', 'text' => 'orange-600', 'icon' => 'fa-utensils'],
                                        'mesero' => ['bg' => 'blue-100', 'text' => 'blue-600', 'icon' => 'fa-concierge-bell'],
                                        'capitan' => ['bg' => 'indigo-100', 'text' => 'indigo-600', 'icon' => 'fa-user-tie'],
                                        'almacen' => ['bg' => 'green-100', 'text' => 'green-600', 'icon' => 'fa-boxes'],
                                        'cajero' => ['bg' => 'teal-100', 'text' => 'teal-600', 'icon' => 'fa-cash-register'],
                                        'sistemas' => ['bg' => 'gray-100', 'text' => 'gray-600', 'icon' => 'fa-laptop-code'],
                                    ];
                                    $avatar = $avatarColors[$user->role ?? ''] ?? ['bg' => 'gray-100', 'text' => 'gray-400', 'icon' => 'fa-user'];
                                @endphp
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-{{ $avatar['bg'] }}">
                                    <i class="fas {{ $avatar['icon'] }} text-{{ $avatar['text'] }}"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $user->name }}</p>
                                    @if ($user->id === Auth::id())
                                        <span class="text-xs text-primary-600 bg-primary-50 px-2 py-0.5 rounded-full">Tú</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-700 font-mono tracking-wider">{{ $user->codigo_empleado ?? '—' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $roleColors = [
                                    'admin' => 'purple',
                                    'cocinero' => 'orange',
                                    'mesero' => 'blue',
                                    'capitan' => 'indigo',
                                    'almacen' => 'green',
                                    'cajero' => 'teal',
                                    'sistemas' => 'gray',
                                ];
                                $roleIcons = [
                                    'admin' => 'fa-user-shield',
                                    'cocinero' => 'fa-utensils',
                                    'mesero' => 'fa-concierge-bell',
                                    'capitan' => 'fa-user-tie',
                                    'almacen' => 'fa-boxes',
                                    'cajero' => 'fa-cash-register',
                                    'sistemas' => 'fa-laptop-code',
                                ];
                                $color = $roleColors[$user->role ?? ''] ?? 'gray';
                                $icon = $roleIcons[$user->role ?? ''] ?? 'fa-user';
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-medium rounded-full bg-{{ $color }}-100 text-{{ $color }}-700">
                                <i class="fas {{ $icon }}"></i>
                                {{ ucfirst($user->role ?? 'Sin rol') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'activo' => 'green',
                                    'pendiente' => 'yellow',
                                    'inactivo' => 'red',
                                ];
                                $statusIcons = [
                                    'activo' => 'fa-check-circle',
                                    'pendiente' => 'fa-clock',
                                    'inactivo' => 'fa-ban',
                                ];
                                $sColor = $statusColors[$user->status] ?? 'gray';
                                $sIcon = $statusIcons[$user->status] ?? 'fa-question';
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-medium rounded-full bg-{{ $sColor }}-100 text-{{ $sColor }}-700">
                                <i class="fas {{ $sIcon }}"></i>
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if ($user->approver)
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 bg-primary-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user-check text-primary-600 text-xs"></i>
                                    </div>
                                    <span class="text-sm text-gray-700">{{ $user->approver->name }}</span>
                                </div>
                            @else
                                <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            {{ $user->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}" 
                                   class="btn-secondary text-sm px-3 py-1.5"
                                   @if ($user->id === Auth::id()) style="pointer-events: none; opacity: 0.5;" @endif
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                @if ($user->id !== Auth::id())
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn-danger text-sm px-3 py-1.5"
                                                @if ($user->isPrivileged() && !Auth::user()->isSistemas()) disabled @endif
                                                title="Dar de baja"
                                                onclick="return confirm('¿Estás seguro de dar de baja a {{ $user->name }}? El usuario dejará de aparecer en el sistema.')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-users text-3xl text-gray-400"></i>
                                </div>
                                <div>
                                    <p class="text-lg font-medium text-gray-800">No hay usuarios registrados</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-4">
        <div class="text-sm text-gray-500">
            Mostrando {{ $users->firstItem() ?? 0 }} a {{ $users->lastItem() ?? 0 }} de {{ $users->total() }} usuarios
        </div>
        <div class="ml-auto flex items-center justify-end gap-2">
            {{ $users->links() }}
        </div>
    </div>
</div>

@push('styles')
<style>
    .users-table-fixed {
        min-height: 511px;
    }
    #users-table tbody tr.user-row {
        height: 73px;
    }
    #users-table tbody tr.user-row td {
        vertical-align: middle;
    }
</style>
@endpush
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-users');
        const rows = document.querySelectorAll('#users-tbody tr.user-row');
        
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase();
                let visibleCount = 0;
                
                rows.forEach(function(row) {
                    const name = row.dataset.name || '';
                    const code = row.dataset.code || '';
                    const role = row.dataset.role || '';
                    const status = row.dataset.status || '';
                    
                    const matches = name.includes(query) || 
                                   code.includes(query) || 
                                   role.includes(query) || 
                                   status.includes(query);
                    
                    row.style.display = matches ? '' : 'none';
                    if (matches) visibleCount++;
                });
                
                // Show/hide empty state
                const emptyRow = document.querySelector('#users-tbody tr:not(.user-row)');
                if (emptyRow && rows.length > 0) {
                    emptyRow.style.display = visibleCount === 0 && query !== '' ? '' : 'none';
                }
            });
        }
    });
</script>
@endpush