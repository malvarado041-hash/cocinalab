@extends('admin.layout')

@section('title', 'Proveedores')

@section('header-title', 'Proveedores')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden fade-in">
    <div class="px-4 sm:px-6 py-4 flex flex-col sm:flex-row sm:items-center gap-3 border-b border-gray-100">
        <form method="GET" action="{{ route('admin.proveedores.index') }}" class="flex-[1_1_auto] min-w-0">
            <div class="relative w-full sm:max-w-xs">
                <i class="fas fa-search text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                <input type="text" name="q" value="{{ $filtros['q'] ?? '' }}" placeholder="Buscar proveedor..."
                    class="pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent w-full transition">
            </div>
        </form>
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:ml-auto">
            @if ($canRestore ?? false)
                <a href="{{ route('admin.proveedores.trashed') }}" class="btn-secondary text-sm px-4 py-2 whitespace-nowrap text-center">
                    <i class="fas fa-user-slash mr-2"></i> Dados de baja
                </a>
            @endif
            <a href="{{ route('admin.proveedores.create') }}" class="btn-primary text-sm px-4 py-2 whitespace-nowrap text-center">
                <i class="fas fa-plus mr-2"></i> Nuevo proveedor
            </a>
        </div>
    </div>

    <div class="hidden md:block">
        <table class="w-full admin-table">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nombre</th>
                    <th class="px-3 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Teléfono</th>
                    <th class="px-3 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Correo</th>
                    <th class="px-3 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider sticky-col">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($proveedores as $p)
                    <tr class="hover:bg-gray-50 transition-colors cursor-pointer" data-url="{{ route('admin.proveedores.show', $p->id) }}">
                        <td class="px-3 py-4">
                            <p class="font-medium text-gray-800 truncate">{{ $p->nombre }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $p->direccion ?? '—' }}</p>
                        </td>
                        <td class="px-3 py-4 text-sm text-gray-600">{{ $p->telefono ?? '—' }}</td>
                        <td class="px-3 py-4 text-sm text-gray-600 truncate">{{ $p->email ?? '—' }}</td>
                        <td class="px-3 py-4 text-center sticky-col">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.proveedores.edit', $p->id) }}" class="hidden min-[400px]:inline-flex btn-secondary text-sm px-3 py-1.5" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.proveedores.destroy', $p->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger text-sm px-3 py-1.5" title="Dar de baja"
                                        onclick="return confirm('¿Dar de baja a {{ $p->nombre }}?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-truck text-3xl text-gray-400"></i>
                                </div>
                                <p class="text-lg font-medium text-gray-800">No hay proveedores registrados</p>
                                <a href="{{ route('admin.proveedores.create') }}" class="btn-primary text-sm px-4 py-2">Registrar el primero</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="md:hidden divide-y divide-gray-100">
        @forelse ($proveedores as $p)
            <div class="px-4 py-4 space-y-2 cursor-pointer hover:bg-gray-50 transition-colors" data-url="{{ route('admin.proveedores.show', $p->id) }}">
                <p class="font-medium text-gray-800">{{ $p->nombre }}</p>
                <div class="text-sm text-gray-500 space-y-1">
                    <p><i class="fas fa-phone w-5 text-center mr-1"></i>{{ $p->telefono ?? '—' }}</p>
                    <p><i class="fas fa-envelope w-5 text-center mr-1"></i>{{ $p->email ?? '—' }}</p>
                    <p><i class="fas fa-location-dot w-5 text-center mr-1"></i>{{ $p->direccion ?? '—' }}</p>
                </div>
                <div class="flex items-center gap-2 pt-1">
                    <a href="{{ route('admin.proveedores.edit', $p->id) }}" class="btn-secondary text-sm px-3 py-1.5" title="Editar">
                        <i class="fas fa-edit mr-1"></i> Editar
                    </a>
                    <form action="{{ route('admin.proveedores.destroy', $p->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger text-sm px-3 py-1.5" title="Dar de baja"
                            onclick="return confirm('¿Dar de baja a {{ $p->nombre }}?')">
                            <i class="fas fa-trash mr-1"></i> Dar de baja
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="px-6 py-16 text-center">
                <div class="flex flex-col items-center gap-4">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-truck text-3xl text-gray-400"></i>
                    </div>
                    <p class="text-lg font-medium text-gray-800">No hay proveedores registrados</p>
                    <a href="{{ route('admin.proveedores.create') }}" class="btn-primary text-sm px-4 py-2">Registrar el primero</a>
                </div>
            </div>
        @endforelse
    </div>

    <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-4">
        <div class="text-sm text-gray-500">
            Mostrando {{ $proveedores->firstItem() ?? 0 }} a {{ $proveedores->lastItem() ?? 0 }} de {{ $proveedores->total() }} proveedores
        </div>
        <div class="ml-auto">{{ $proveedores->links() }}</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[data-url]').forEach(function(el) {
        el.addEventListener('click', function(e) {
            if (e.target.closest('a') || e.target.closest('button') || e.target.closest('form')) return;
            const url = el.getAttribute('data-url');
            if (url) window.location.href = url;
        });
    });
});
</script>
@endpush
