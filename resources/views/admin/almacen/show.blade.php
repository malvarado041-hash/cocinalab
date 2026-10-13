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
@if ($errors->has('motivo_baja'))
    <div class="max-w-2xl mx-auto mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg" role="alert">
        <p class="font-semibold text-sm">No se pudo dar de baja:</p>
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->get('motivo_baja') as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif
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
        <button type="button" id="btn-baja-show" class="btn-danger text-sm px-4 py-2">
            <i class="fas fa-trash mr-2"></i> Dar de baja
        </button>
    </div>
</div>

<div id="modal-baja" class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-900/50 p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
        <h3 class="text-lg font-semibold text-gray-800">Dar de baja: {{ $producto->nombre }}</h3>
        <form action="{{ route('admin.almacen.destroy', $producto) }}" method="POST">
            @csrf
            @method('DELETE')
            <label class="block text-sm font-medium text-gray-700 mt-4 mb-1" for="motivo_baja">Motivo de la baja *</label>
            <textarea id="motivo_baja" name="motivo_baja" required minlength="5" maxlength="500" rows="3"
                placeholder="Ej. Producto caducado, dañado, descontinuado…"
                class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"></textarea>
            <div class="mt-5 flex items-center justify-end gap-3">
                <button type="button" id="modal-baja-cancelar" class="btn-secondary text-sm px-4 py-2">Cancelar</button>
                <button type="submit" class="btn-danger text-sm px-4 py-2">
                    <i class="fas fa-trash mr-2"></i> Confirmar baja
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modal-baja');
    const openBtn = document.getElementById('btn-baja-show');
    const cancelBtn = document.getElementById('modal-baja-cancelar');
    function openModal() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }
    if (openBtn) openBtn.addEventListener('click', openModal);
    if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', function(e) { if (e.target === modal) closeModal(); });
    document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeModal(); });
});
</script>
@endpush
