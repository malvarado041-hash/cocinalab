<?php
$unidadSingular = $unidades[$producto->unidad][0] ?? ucfirst($producto->unidad);
$unidadPlural = $unidades[$producto->unidad][1] ?? ucfirst($producto->unidad) . 's';
?>
@extends('admin.layout')

@section('title', 'Detalle de Producto')

@section('header-title', 'Detalle de Producto')

@section('header-actions')
<a href="{{ route('admin.almacen.index') }}" class="btn-secondary text-sm px-4 py-2">
    <i class="fas fa-arrow-left mr-2"></i> Volver
</a>
@endsection

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden fade-in">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-4">
        <div class="w-14 h-14 rounded-xl flex items-center justify-center {{ $producto->esBajoStock() ? 'bg-red-100' : 'bg-green-100' }}">
            <i class="fas fa-box {{ $producto->esBajoStock() ? 'text-red-600' : 'text-green-600' }} text-2xl"></i>
        </div>
        <div>
            <h2 class="text-xl font-semibold text-gray-800">{{ $producto->nombre }}</h2>
            <p class="text-sm text-gray-500">{{ $producto->categoria_label }} · {{ str_replace('_', ' ', $producto->subcategoria) }}</p>
        </div>
    </div>

    <div class="p-6 space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Stock</p>
                <p class="text-lg font-semibold {{ $producto->esBajoStock() ? 'text-red-600' : 'text-gray-800' }}">
                    {{ number_format($producto->stock, 2) }}
                    <span class="text-sm font-normal text-gray-500">{{ $producto->stock == 1 ? $unidadSingular : $unidadPlural }}</span>
                </p>
                <p class="text-xs text-gray-400">Mínimo: {{ number_format($producto->stock_minimo, 2) }}</p>
                @if ($producto->esBajoStock())
                    <span class="text-xs text-red-700 bg-red-100 px-2 py-0.5 rounded-full">Bajo stock</span>
                @endif
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Precio</p>
                @if ($producto->precio_unitario !== null)
                    <p class="text-lg font-semibold text-gray-800">${{ number_format($producto->precio_unitario, 2) }}</p>
                    <p class="text-xs text-gray-400">por {{ $unidadSingular }}</p>
                @else
                    <p class="text-lg text-gray-400">—</p>
                @endif
            </div>
        </div>

        <div class="border-t border-gray-100 pt-4">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Proveedor</p>
            <p class="text-sm text-gray-700">{{ $producto->proveedor ?? '—' }}</p>
        </div>

        <div class="border-t border-gray-100 pt-4">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Perecedero</p>
            @if ($producto->perecedero)
                <p class="text-sm text-gray-700">
                    <i class="fas fa-check-circle text-green-600 mr-1"></i> Sí
                    @if ($producto->fecha_caducidad)
                        <span class="text-gray-500">— caduca el {{ $producto->fecha_caducidad->format('d/m/Y') }}</span>
                    @endif
                </p>
            @else
                <p class="text-sm text-gray-700"><i class="fas fa-times-circle text-gray-400 mr-1"></i> No</p>
            @endif
        </div>
    </div>

    <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3">
        <a href="{{ route('admin.almacen.edit', $producto) }}" class="btn-primary text-sm px-4 py-2">
            <i class="fas fa-edit mr-2"></i> Editar
        </a>
        <form action="{{ route('admin.almacen.destroy', $producto) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-danger text-sm px-4 py-2"
                    onclick="return confirm('¿Dar de baja a {{ $producto->nombre }}?')">
                <i class="fas fa-trash mr-2"></i> Dar de baja
            </button>
        </form>
    </div>
</div>
@endsection
