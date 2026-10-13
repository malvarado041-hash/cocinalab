@extends('admin.layout')

@section('title', 'Detalle de Proveedor')

@section('header-title', 'Detalle de Proveedor')

@section('header-actions')
<a href="{{ route('admin.proveedores.index') }}" class="btn-secondary text-sm px-4 py-2">
    <i class="fas fa-arrow-left mr-2"></i> Volver
</a>
@endsection

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden fade-in">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-4">
        <div class="w-14 h-14 rounded-xl flex items-center justify-center bg-blue-100">
            <i class="fas fa-truck text-blue-600 text-2xl"></i>
        </div>
        <div>
            <h2 class="text-xl font-semibold text-gray-800">{{ $proveedor->nombre }}</h2>
            <p class="text-sm text-gray-500">Proveedor</p>
        </div>
    </div>

    <div class="p-6 space-y-4">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Teléfono</p>
            <p class="text-sm text-gray-700"><i class="fas fa-phone text-gray-400 mr-2"></i>{{ $proveedor->telefono ?? '—' }}</p>
        </div>
        <div class="border-t border-gray-100 pt-4">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Correo electrónico</p>
            <p class="text-sm text-gray-700"><i class="fas fa-envelope text-gray-400 mr-2"></i>{{ $proveedor->email ?? '—' }}</p>
        </div>
        <div class="border-t border-gray-100 pt-4">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Dirección</p>
            <p class="text-sm text-gray-700"><i class="fas fa-location-dot text-gray-400 mr-2"></i>{{ $proveedor->direccion ?? '—' }}</p>
        </div>
    </div>

    <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3">
        <a href="{{ route('admin.proveedores.edit', $proveedor) }}" class="btn-primary text-sm px-4 py-2">
            <i class="fas fa-edit mr-2"></i> Editar
        </a>
        <form action="{{ route('admin.proveedores.destroy', $proveedor) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-danger text-sm px-4 py-2"
                    onclick="return confirm('¿Dar de baja a {{ $proveedor->nombre }}?')">
                <i class="fas fa-trash mr-2"></i> Dar de baja
            </button>
        </form>
    </div>
</div>
@endsection
