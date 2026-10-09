@extends('admin.layout')

@section('title', 'Almacén')

@section('header-title', 'Control de Almacén')

@section('header-actions')
@endsection

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden fade-in">
    <div class="px-4 sm:px-6 py-4 flex flex-col lg:flex-row lg:flex-wrap lg:items-center gap-3 border-b border-gray-100">
        <form id="filtros-form" method="GET" action="{{ route('admin.almacen.index') }}" class="flex-[1_1_auto] min-w-0 grid grid-cols-1 sm:grid-cols-2 lg:flex lg:items-center gap-3">
            <div class="relative sm:col-span-2 lg:col-span-1 lg:w-64 xl:w-72">
                <i class="fas fa-search text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                <input type="text" id="filtro-q" name="q" value="{{ $filtros['q'] ?? '' }}" placeholder="Buscar producto..." autocomplete="off"
                    class="pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent w-full transition">
            </div>
            <select id="filtro-categoria" name="categoria"
                class="px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 w-full lg:w-auto lg:max-w-44 transition">
                <option value="">Todas las categorías</option>
                @foreach ($categorias as $key => $label)
                    <option value="{{ $key }}" @selected(($filtros['categoria'] ?? '') === $key)>{{ $label }}</option>
                @endforeach
            </select>
            <select id="filtro-subcategoria" name="subcategoria" onchange="this.form.submit()"
                class="px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 w-full lg:w-auto lg:max-w-44 transition">
                <option value="">Todas las subcategorías</option>
                @foreach ($subcategorias as $sub)
                    <option value="{{ $sub }}" @selected(($filtros['subcategoria'] ?? '') === $sub)>{{ str_replace('_', ' ', $sub) }}</option>
                @endforeach
            </select>
            <div class="flex items-center justify-between sm:col-span-2 lg:col-span-1 gap-3">
                <label class="inline-flex items-center gap-2 text-sm text-gray-600 cursor-pointer whitespace-nowrap">
                    <input type="checkbox" name="bajo_stock" value="1" onchange="this.form.submit()"
                        @checked(!empty($filtros['bajo_stock'])) class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                    Solo bajo stock ({{ $bajoStockCount }})
                </label>
                @if (!empty($filtros['q']) || !empty($filtros['categoria']) || !empty($filtros['subcategoria']) || !empty($filtros['bajo_stock']))
                    <a href="{{ route('admin.almacen.index') }}" class="text-sm text-gray-500 hover:text-gray-700 px-2 py-2 whitespace-nowrap">Limpiar</a>
                @endif
            </div>
        </form>
        <div class="flex flex-col sm:flex-row lg:items-center gap-3 lg:ml-auto">
            @if ($canRestore ?? false)
                <a href="{{ route('admin.almacen.trashed') }}" class="btn-secondary text-sm px-4 py-2 whitespace-nowrap text-center sm:flex-1 lg:flex-none">
                    <i class="fas fa-box-open mr-2"></i> Dados de baja
                </a>
            @endif
            <a href="{{ route('admin.almacen.create', request()->query()) }}" id="btn-nuevo-producto" class="btn-primary text-sm px-4 py-2 whitespace-nowrap text-center sm:flex-1 lg:flex-none">
                <i class="fas fa-plus mr-2"></i> Nuevo producto
            </a>
        </div>
    </div>

    <div id="almacen-tabla" class="transition-opacity duration-150">
        @include('admin.almacen._table')
    </div>
</div>
@endsection

@push('styles')
<style>
    #almacen-tabla table {
        table-layout: fixed;
    }
    #almacen-tabla td {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    #almacen-tabla td p,
    #almacen-tabla td span.block,
    #almacen-tabla td .inline-block-cell {
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 100%;
    }
    #almacen-tabla td .inline-flex-cell {
        display: inline-flex;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    #almacen-tabla td .truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const MAPA = @json($mapa);
    const cat = document.getElementById('filtro-categoria');
    const sub = document.getElementById('filtro-subcategoria');
    const currentSub = @json($filtros['subcategoria'] ?? '');

    cat.addEventListener('change', function() {
        // Reconstruye subcategorías según la categoría y envía el filtro.
        sub.innerHTML = '<option value="">Todas las subcategorías</option>';
        Object.keys(MAPA[cat.value] || {}).forEach(function(s) {
            const o = document.createElement('option');
            o.value = s;
            o.textContent = s.replace(/_/g, ' ');
            sub.appendChild(o);
        });
        sub.value = '';
        cat.form.submit();
    });

    // Búsqueda en vivo: filtra mientras escribes, sin recargar ni dar Enter.
    const searchInput = document.getElementById('filtro-q');
    const tabla = document.getElementById('almacen-tabla');
    const btnNuevo = document.getElementById('btn-nuevo-producto');
    const baseNuevo = btnNuevo.getAttribute('href').split('?')[0];
    let timer = null;
    let ctrl = null;

    function filtrosQuery() {
        const params = new URLSearchParams(new FormData(cat.form));
        for (const [k, v] of [...params]) {
            if (v === '') params.delete(k);
        }
        return params.toString();
    }

    async function liveSearch(url) {
        if (ctrl) ctrl.abort();
        ctrl = new AbortController();
        const target = url || (cat.form.action + '?' + filtrosQuery());
        tabla.style.opacity = '0.5';
        try {
            const res = await fetch(target, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                signal: ctrl.signal,
            });
            const data = await res.json();
            tabla.innerHTML = data.html;
            history.replaceState(null, '', target);
            btnNuevo.setAttribute('href', baseNuevo + (filtrosQuery() ? '?' + filtrosQuery() : ''));
        } catch (e) {
            if (e.name !== 'AbortError') throw e;
        } finally {
            tabla.style.opacity = '';
        }
    }

    searchInput.addEventListener('input', function() {
        clearTimeout(timer);
        timer = setTimeout(function() { liveSearch(); }, 350);
    });

    // Clic en fila → vista de detalle (delegación, la tabla se recarga vía AJAX).
    tabla.addEventListener('click', function(e) {
        const link = e.target.closest('#almacen-pagination a');
        if (link) {
            e.preventDefault();
            liveSearch(link.href);
            return;
        }
        if (e.target.closest('a') || e.target.closest('button') || e.target.closest('form')) return;
        const row = e.target.closest('.almacen-row');
        if (row && row.dataset.url) window.location.href = row.dataset.url;
    });
});
</script>
@endpush
