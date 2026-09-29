@extends('admin.layout')

@section('title', 'Usuarios dados de baja')

@section('header-title', 'Usuarios dados de baja')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden fade-in">
    <div class="px-6 py-4 flex items-center gap-4">
        <a href="{{ route('admin.users.index') }}" title="Volver" aria-label="Volver"
           class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center flex-shrink-0 transition">
            <i class="fas fa-arrow-left text-gray-600"></i>
        </a>
        <div class="relative w-full sm:w-64 ml-auto">
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
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Baja</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100" id="users-tbody">
                @forelse ($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors user-row" data-name="{{ strtolower($user->name) }}" data-code="{{ strtolower($user->codigo_empleado ?? '') }}" data-role="{{ strtolower($user->role ?? '') }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-gray-100">
                                    <i class="fas fa-user-slash text-gray-400"></i>
                                </div>
                                <p class="font-medium text-gray-800">{{ $user->name }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-700 font-mono tracking-wider">{{ $user->codigo_empleado ?? '—' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600">
                                {{ ucfirst($user->role ?? 'Sin rol') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            {{ $user->deleted_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                            <form action="{{ route('admin.users.restore', $user->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <button type="submit"
                                        class="btn-primary text-sm px-3 py-1.5"
                                        title="Restaurar"
                                        onclick="return confirm('¿Restaurar a {{ $user->name }}? Volverá a aparecer en el sistema.')">
                                    <i class="fas fa-undo mr-1"></i> Restaurar
                                </button>
                            </form>
                            <form action="{{ route('admin.users.forceDestroy', $user->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn-danger text-sm px-3 py-1.5"
                                        title="Eliminar definitivamente"
                                        onclick="return confirm('¿Eliminar DEFINITIVAMENTE a {{ $user->name }}? Se borrará de la base de datos y no se podrá recuperar.')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user-check text-3xl text-gray-400"></i>
                                </div>
                                <p class="text-lg font-medium text-gray-800">No hay usuarios dados de baja</p>
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
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-users');
        const rows = document.querySelectorAll('#users-tbody tr.user-row');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase();

                rows.forEach(function(row) {
                    const name = row.dataset.name || '';
                    const code = row.dataset.code || '';
                    const role = row.dataset.role || '';

                    row.style.display = (name.includes(query) || code.includes(query) || role.includes(query)) ? '' : 'none';
                });
            });
        }
    });
</script>
@endpush
