@extends('admin.layout')

@section('title', 'Recetas dadas de baja')
@section('header-title', 'Recetas dadas de baja')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden fade-in">
    <div class="px-6 py-4">
        <a href="{{ route('admin.recetas.index') }}" title="Volver" aria-label="Volver"
           class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 inline-flex items-center justify-center transition">
            <i class="fas fa-arrow-left text-gray-600"></i>
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full min-w-[640px]">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Receta</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Tipo</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Baja</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($recetas as $r)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $r->Nombre }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $r->TipoC }} ({{ $r->ingredientes_count }})</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $r->deleted_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <form action="{{ route('admin.recetas.restore', $r->id) }}" method="POST" class="inline">
                                    @csrf @method('PUT')
                                    <button type="submit" class="btn-primary text-sm px-3 py-1.5" onclick="return confirm('¿Restaurar {{ $r->Nombre }}?')">
                                        <i class="fas fa-undo mr-1"></i> Restaurar
                                    </button>
                                </form>
                                <form action="{{ route('admin.recetas.forceDestroy', $r->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-danger text-sm px-3 py-1.5" onclick="return confirm('¿Eliminar DEFINITIVAMENTE {{ $r->Nombre }}?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-6 py-16 text-center text-gray-500">No hay recetas dadas de baja</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-4">
        <div class="text-sm text-gray-500">Mostrando {{ $recetas->firstItem() ?? 0 }} a {{ $recetas->lastItem() ?? 0 }} de {{ $recetas->total() }}</div>
        <div class="ml-auto">{{ $recetas->links() }}</div>
    </div>
</div>
@endsection
