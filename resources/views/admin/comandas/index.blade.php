@extends('admin.layout')

@section('title', 'Comandas')

@section('header-title', 'Cuentas / Comandas')

@section('content')
@php
    $estadoColors = [
        'abierta' => 'blue', 'en_cocina' => 'amber', 'lista' => 'green',
        'servida' => 'gray', 'cancelada' => 'red',
    ];
@endphp
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden fade-in flex flex-col min-h-full">
    <div class="px-4 sm:px-6 py-4 flex flex-col lg:flex-row lg:flex-wrap lg:items-center gap-3 border-b border-gray-100">
        <form method="GET" action="{{ route('admin.comandas.index') }}" class="flex-[1_1_auto] min-w-0 grid grid-cols-1 sm:grid-cols-2 lg:flex lg:items-center gap-3">
            <div class="relative sm:col-span-2 lg:w-56">
                <i class="fas fa-search text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                <input type="text" name="q" value="{{ $filtros['q'] ?? '' }}" placeholder="Buscar mesa / cuenta..."
                    class="pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent w-full transition">
            </div>
            <select name="estado" onchange="this.form.submit()"
                class="px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 w-full lg:w-auto transition">
                <option value="">Todos los estados</option>
                @foreach ($estados as $key => $label)
                    <option value="{{ $key }}" @selected(($filtros['estado'] ?? '') === $key)>{{ $label }}</option>
                @endforeach
            </select>
            <select name="tipo" onchange="this.form.submit()"
                class="px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 w-full lg:w-auto transition">
                <option value="">Mesa y llevar</option>
                @foreach ($tipos as $key => $label)
                    <option value="{{ $key }}" @selected(($filtros['tipo'] ?? '') === $key)>{{ $label }}</option>
                @endforeach
            </select>
            @if (!empty($filtros['q']) || !empty($filtros['estado']) || !empty($filtros['tipo']))
                <a href="{{ route('admin.comandas.index') }}" class="text-sm text-gray-500 hover:text-gray-700 px-2 py-2 whitespace-nowrap">Limpiar</a>
            @endif
        </form>
        @if ($puedeOperar ?? false)
        <div class="flex lg:ml-auto">
            <a href="{{ route('admin.comandas.create') }}" class="btn-primary text-sm px-4 py-2 whitespace-nowrap text-center w-full sm:w-auto">
                <i class="fas fa-plus mr-2"></i> Nueva cuenta
            </a>
        </div>
        @endif
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 flex-1 content-start">
        @forelse ($comandas as $c)
            @php $color = $estadoColors[$c->estado] ?? 'gray'; @endphp
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all cursor-pointer fade-in"
                 data-url="{{ route('admin.comandas.show', $c->id) }}">
                <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between gap-2 {{ $c->tipo === 'llevar' ? 'bg-purple-50' : '' }}">
                    <div class="min-w-0">
                        <h3 class="font-semibold text-gray-800 truncate">{{ $c->mesa }}</h3>
                        <p class="text-xs text-gray-500">{{ $c->tipo_label }}</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-{{ $color }}-100 text-{{ $color }}-700 flex-shrink-0">
                        {{ $c->estado_label }}
                    </span>
                </div>
                <div class="px-4 py-3 space-y-1.5">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Mesero</span>
                        <span class="text-gray-700 font-medium">{{ $c->mesero->name ?? '—' }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Platillos</span>
                        <span class="text-gray-700 font-medium">{{ $c->items_count }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Hora</span>
                        <span class="text-gray-700">{{ $c->created_at->format('H:i') }}</span>
                    </div>
                    <div class="pt-2 border-t border-gray-50 flex items-center justify-between">
                        <span class="text-xs text-gray-400 uppercase tracking-wider">Total</span>
                        <span class="font-semibold text-gray-800">${{ number_format($c->total, 2) }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full px-6 py-16 text-center bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="flex flex-col items-center gap-4">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-receipt text-3xl text-gray-400"></i>
                    </div>
                    <p class="text-lg font-medium text-gray-800">No hay cuentas</p>
                    @if ($puedeOperar ?? false)
                    <a href="{{ route('admin.comandas.create') }}" class="btn-primary text-sm px-4 py-2">Abrir la primera</a>
                    @endif
                </div>
            </div>
        @endforelse
    </div>

    <div class="px-2 sm:px-0 mt-4 flex items-center justify-end gap-4">
        <div class="text-sm text-gray-500">
            Mostrando {{ $comandas->firstItem() ?? 0 }} a {{ $comandas->lastItem() ?? 0 }} de {{ $comandas->total() }} cuentas
        </div>
        <div class="ml-auto">{{ $comandas->links() }}</div>
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
