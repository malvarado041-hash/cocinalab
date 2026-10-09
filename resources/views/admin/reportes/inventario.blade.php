@extends('admin.layout')

@section('title', 'Reportes de Inventario')

@section('header-title', 'Reportes de Inventario')

@section('header-actions')
    <a href="{{ route('admin.reportes.inventario.csv', request()->query()) }}"
       class="btn-primary text-xs sm:text-sm px-3 sm:px-4 py-2 whitespace-nowrap"
       title="Descargar detalle del inventario en CSV">
        <i class="fas fa-file-csv sm:mr-2"></i><span class="hidden sm:inline">Descargar CSV</span>
    </a>
@endsection

@section('content')
@php
    $money = fn ($v) => '$' . number_format((float) $v, 2);
    $catLabel = fn (string $key) => $categorias[$key] ?? ucfirst(str_replace('_', ' ', $key));
    $qs = request()->query();
@endphp

{{-- Filtro --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden fade-in mb-6">
    <div class="px-4 sm:px-6 py-4 flex flex-col lg:flex-row lg:items-center gap-3">
        <form method="GET" action="{{ route('admin.reportes.inventario') }}"
              class="flex-[1_1_auto] min-w-0 grid grid-cols-1 sm:grid-cols-2 lg:flex lg:items-center gap-3">
            <select name="categoria" onchange="this.form.submit()"
                class="px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 w-full lg:w-auto lg:max-w-56 transition">
                <option value="">Todas las categorías</option>
                @foreach ($categorias as $key => $label)
                    <option value="{{ $key }}" @selected($categoria === $key)>{{ $label }}</option>
                @endforeach
            </select>
            <div class="flex items-center gap-3 sm:col-span-2 lg:col-span-1">
                @if ($categoria)
                    <a href="{{ route('admin.reportes.inventario') }}" class="text-sm text-gray-500 hover:text-gray-700 px-2 py-2 whitespace-nowrap">
                        <i class="fas fa-times mr-1"></i> Limpiar
                    </a>
                @endif
                <a href="{{ route('admin.reportes.inventario.csv', $qs) }}" class="btn-secondary text-sm px-4 py-2 whitespace-nowrap">
                    <i class="fas fa-file-csv mr-2"></i> CSV
                </a>
            </div>
        </form>
        <div class="text-xs text-gray-400 lg:ml-auto whitespace-nowrap">
            <i class="fas fa-sync-alt mr-1"></i> Generado el {{ $generado }}
        </div>
    </div>
</div>

{{-- KPIs --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6 mb-6">
    <article class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow fade-in">
        <div class="flex items-center justify-between gap-3">
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Productos registrados</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $kpi['productos'] }}</p>
            </div>
            <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-boxes text-blue-600 text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between gap-3">
            <a href="{{ route('admin.almacen.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium flex items-center gap-1 whitespace-nowrap">
                Ver almacén <i class="fas fa-arrow-right text-xs"></i>
            </a>
            <span class="text-xs text-gray-400 text-right">{{ $kpi['productos'] > 0 ? $kpi['productos'] . ' activos' : 'sin datos' }}</span>
        </div>
    </article>

    <article class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow fade-in" style="animation-delay: 0.05s;">
        <div class="flex items-center justify-between gap-3">
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Valor total</p>
                <p class="text-3xl font-bold text-gray-800 mt-1 truncate">{{ $money($kpi['valorTotal']) }}</p>
            </div>
            <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-dollar-sign text-green-600 text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between gap-3">
            <a href="#valor-categoria" class="text-sm text-primary-600 hover:text-primary-700 font-medium flex items-center gap-1 whitespace-nowrap">
                Ver detalle <i class="fas fa-arrow-right text-xs"></i>
            </a>
            <span class="text-xs text-gray-400 text-right">
                @if ($kpi['sinPrecio'] > 0)
                    {{ $kpi['sinPrecio'] }} sin precio registrado
                @elseif ($kpi['productos'] > 0)
                    valoración completa
                @else
                    sin datos
                @endif
            </span>
        </div>
    </article>

    <article class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow fade-in" style="animation-delay: 0.1s;">
        <div class="flex items-center justify-between gap-3">
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Bajo stock</p>
                <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $kpi['bajoStock'] }}</p>
            </div>
            <div class="w-14 h-14 bg-yellow-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-yellow-600 text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between gap-3">
            <a href="#alertas" class="text-sm text-yellow-600 hover:text-yellow-700 font-medium flex items-center gap-1 whitespace-nowrap">
                Ver alertas <i class="fas fa-arrow-right text-xs"></i>
            </a>
            <span class="text-xs text-gray-400 text-right">por debajo del mínimo</span>
        </div>
    </article>

    <article class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow fade-in" style="animation-delay: 0.15s;">
        <div class="flex items-center justify-between gap-3">
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Agotados</p>
                <p class="text-3xl font-bold text-red-600 mt-1">{{ $kpi['agotados'] }}</p>
            </div>
            <div class="w-14 h-14 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-ban text-red-600 text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between gap-3">
            <a href="#alertas" class="text-sm text-red-600 hover:text-red-700 font-medium flex items-center gap-1 whitespace-nowrap">
                Ver alertas <i class="fas fa-arrow-right text-xs"></i>
            </a>
            <span class="text-xs text-gray-400 text-right">sin existencias</span>
        </div>
    </article>

    <article class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow fade-in" style="animation-delay: 0.2s;">
        <div class="flex items-center justify-between gap-3">
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Costo de reposición</p>
                <p class="text-3xl font-bold text-primary-600 mt-1 truncate">{{ $money($kpi['reposicion']) }}</p>
            </div>
            <div class="w-14 h-14 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-cart-plus text-primary-600 text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between gap-3">
            <a href="#alertas" class="text-sm text-primary-600 hover:text-primary-700 font-medium flex items-center gap-1 whitespace-nowrap">
                Ver alertas <i class="fas fa-arrow-right text-xs"></i>
            </a>
            <span class="text-xs text-gray-400 text-right">
                @if ($kpi['reposicionSinPrecio'] > 0)
                    {{ $kpi['reposicionSinPrecio'] }} artículos sin precio
                @else
                    lo que falta comprar
                @endif
            </span>
        </div>
    </article>

    <article class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow fade-in" style="animation-delay: 0.25s;">
        <div class="flex items-center justify-between gap-3">
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Por vencer (≤ 30 días)</p>
                <p class="text-3xl font-bold text-purple-600 mt-1">{{ $kpi['porVencer'] }}</p>
            </div>
            <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-hourglass-half text-purple-600 text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between gap-3">
            <a href="#caducidades" class="text-sm text-purple-600 hover:text-purple-700 font-medium flex items-center gap-1 whitespace-nowrap">
                Ver caducidades <i class="fas fa-arrow-right text-xs"></i>
            </a>
            <span class="text-xs text-gray-400 text-right">
                {{ $kpi['vencidos'] }} {{ \Illuminate\Support\Str::plural('vencido', $kpi['vencidos']) }} · {{ $kpi['en7'] }} ≤ 7 días
            </span>
        </div>
    </article>
</div>

{{-- Valor por categoría --}}
<section id="valor-categoria" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden fade-in mb-6 scroll-mt-24">
    <div class="px-4 sm:px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-3 flex-wrap">
        <div class="min-w-0">
            <h2 class="text-base font-semibold text-gray-800"><i class="fas fa-layer-group text-primary-500 mr-2"></i>Valor por categoría</h2>
            <p class="text-xs text-gray-500 mt-0.5">Dónde está inmovilizado el dinero del inventario</p>
        </div>
        <span class="text-xs text-gray-400">{{ $porCategoria->count() }} {{ \Illuminate\Support\Str::plural('categoría', $porCategoria->count()) }}</span>
    </div>

    <div class="hidden md:block">
        <table class="w-full admin-table">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Categoría</th>
                    <th class="px-3 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Productos</th>
                    <th class="px-3 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Valor de inventario</th>
                    <th class="px-3 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Bajo stock</th>
                    <th class="px-3 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Agotados</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($porCategoria as $c)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-3 py-4">
                            <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">
                                {{ $catLabel($c->categoria) }}
                            </span>
                        </td>
                        <td class="px-3 py-4 text-sm text-gray-700 text-right">{{ $c->productos }}</td>
                        <td class="px-3 py-4 text-sm font-semibold text-gray-800 text-right">{{ $money($c->valor) }}</td>
                        <td class="px-3 py-4 text-sm text-right {{ $c->bajos ? 'text-yellow-600 font-semibold' : 'text-gray-400' }}">{{ $c->bajos ?? 0 }}</td>
                        <td class="px-3 py-4 text-sm text-right {{ $c->agotados ? 'text-red-600 font-semibold' : 'text-gray-400' }}">{{ $c->agotados ?? 0 }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-500">
                            No hay productos en esta categoría.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            @if ($porCategoria->isNotEmpty())
                <tfoot class="bg-gray-50 border-t border-gray-200">
                    <tr>
                        <td class="px-3 py-4 text-sm font-semibold text-gray-700">Total</td>
                        <td class="px-3 py-4 text-sm font-semibold text-gray-800 text-right">{{ $porCategoria->sum('productos') }}</td>
                        <td class="px-3 py-4 text-sm font-bold text-gray-800 text-right">{{ $money($porCategoria->sum('valor')) }}</td>
                        <td class="px-3 py-4 text-sm font-semibold text-gray-800 text-right">{{ $porCategoria->sum('bajos') }}</td>
                        <td class="px-3 py-4 text-sm font-semibold text-gray-800 text-right">{{ $porCategoria->sum('agotados') }}</td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>

    <div class="md:hidden divide-y divide-gray-100">
        @forelse ($porCategoria as $c)
            <div class="px-4 py-4 space-y-2">
                <div class="flex items-center justify-between gap-3">
                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">
                        {{ $catLabel($c->categoria) }}
                    </span>
                    <span class="text-sm font-semibold text-gray-800">{{ $money($c->valor) }}</span>
                </div>
                <div class="grid grid-cols-3 gap-x-3 text-sm">
                    <div>
                        <p class="text-xs text-gray-500">Productos</p>
                        <p class="text-gray-700">{{ $c->productos }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Bajo stock</p>
                        <p class="{{ $c->bajos ? 'text-yellow-600 font-semibold' : 'text-gray-700' }}">{{ $c->bajos ?? 0 }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Agotados</p>
                        <p class="{{ $c->agotados ? 'text-red-600 font-semibold' : 'text-gray-700' }}">{{ $c->agotados ?? 0 }}</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="px-6 py-10 text-center text-sm text-gray-500">No hay productos en esta categoría.</div>
        @endforelse
    </div>
</section>

{{-- Top 10 por valor --}}
<section id="top-valor" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden fade-in mb-6 scroll-mt-24">
    <div class="px-4 sm:px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-3 flex-wrap">
        <div class="min-w-0">
            <h2 class="text-base font-semibold text-gray-800"><i class="fas fa-sort-amount-down text-primary-500 mr-2"></i>Productos con mayor valor</h2>
            <p class="text-xs text-gray-500 mt-0.5">Top 10 por stock × precio unitario</p>
        </div>
        <span class="text-xs text-gray-400">{{ $topValor->count() }} de {{ $kpi['productos'] }} productos</span>
    </div>

    <div class="hidden md:block">
        <table class="w-full admin-table">
            <thead class="bg-gray-50">
                <tr>
                    <th class="w-12 px-3 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                    <th class="px-3 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Producto</th>
                    <th class="px-3 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Categoría</th>
                    <th class="px-3 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Stock</th>
                    <th class="px-3 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Precio</th>
                    <th class="px-3 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Valor</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($topValor as $i => $p)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-3 py-4 text-sm text-gray-400">{{ $i + 1 }}</td>
                        <td class="px-3 py-4">
                            <a href="{{ route('admin.almacen.show', $p->id) }}" class="font-medium text-gray-800 hover:text-primary-600 truncate block">{{ $p->nombre }}</a>
                        </td>
                        <td class="px-3 py-4">
                            <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">{{ $catLabel($p->categoria) }}</span>
                        </td>
                        <td class="px-3 py-4 text-sm text-right {{ $p->esBajoStock() ? 'text-red-600 font-semibold' : 'text-gray-700' }}">
                            {{ number_format($p->stock, 2) }}
                            <span class="text-xs text-gray-400">{{ \App\Models\AlmacenProducto::unidadLabel($p->unidad) }}</span>
                        </td>
                        <td class="px-3 py-4 text-sm text-right text-gray-700">
                            {{ $p->precio_unitario !== null ? $money($p->precio_unitario) : '—' }}
                        </td>
                        <td class="px-3 py-4 text-sm font-semibold text-gray-800 text-right">{{ $money($p->valor) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500">
                            No hay productos para mostrar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="md:hidden divide-y divide-gray-100">
        @forelse ($topValor as $i => $p)
            <a href="{{ route('admin.almacen.show', $p->id) }}" class="block px-4 py-4 space-y-2 hover:bg-gray-50 transition-colors">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-xs text-gray-400">#{{ $i + 1 }}</p>
                        <p class="font-medium text-gray-800">{{ $p->nombre }}</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700 flex-shrink-0">
                        {{ $catLabel($p->categoria) }}
                    </span>
                </div>
                <div class="grid grid-cols-3 gap-x-3 text-sm">
                    <div>
                        <p class="text-xs text-gray-500">Stock</p>
                        <p class="{{ $p->esBajoStock() ? 'text-red-600 font-semibold' : 'text-gray-700' }}">
                            {{ number_format($p->stock, 2) }}
                            <span class="text-xs text-gray-400">{{ \App\Models\AlmacenProducto::unidadLabel($p->unidad) }}</span>
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Precio</p>
                        <p class="text-gray-700">{{ $p->precio_unitario !== null ? $money($p->precio_unitario) : '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Valor</p>
                        <p class="font-semibold text-gray-800">{{ $money($p->valor) }}</p>
                    </div>
                </div>
            </a>
        @empty
            <div class="px-6 py-10 text-center text-sm text-gray-500">No hay productos para mostrar.</div>
        @endforelse
    </div>
</section>

{{-- Alertas de bajo stock --}}
<section id="alertas" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden fade-in mb-6 scroll-mt-24">
    <div class="px-4 sm:px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-3 flex-wrap">
        <div class="min-w-0">
            <h2 class="text-base font-semibold text-gray-800"><i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i>Alertas de bajo stock</h2>
            <p class="text-xs text-gray-500 mt-0.5">Ordenadas por costo de reposición</p>
        </div>
        <span class="text-xs text-gray-400">{{ $alertas->count() }} productos · reposición {{ $money($kpi['reposicion']) }}</span>
    </div>

    <div class="hidden md:block">
        <table class="w-full admin-table">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Producto</th>
                    <th class="px-3 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Categoría</th>
                    <th class="px-3 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Stock actual</th>
                    <th class="px-3 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Mínimo</th>
                    <th class="px-3 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Faltante</th>
                    <th class="px-3 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Costo de reposición</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($alertas as $p)
                    <tr class="hover:bg-gray-50 transition-colors {{ $p->stock <= 0 ? 'bg-red-50/60' : '' }}">
                        <td class="px-3 py-4">
                            <a href="{{ route('admin.almacen.show', $p->id) }}" class="font-medium text-gray-800 hover:text-primary-600 truncate block">
                                {{ $p->nombre }}
                                @if ($p->stock <= 0)
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide rounded-full bg-red-100 text-red-700">Agotado</span>
                                @endif
                            </a>
                        </td>
                        <td class="px-3 py-4">
                            <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">{{ $catLabel($p->categoria) }}</span>
                        </td>
                        <td class="px-3 py-4 text-sm text-right text-red-600 font-semibold">{{ number_format($p->stock, 2) }}</td>
                        <td class="px-3 py-4 text-sm text-right text-gray-700">{{ number_format($p->stock_minimo, 2) }}</td>
                        <td class="px-3 py-4 text-sm text-right text-gray-700">
                            {{ number_format($p->faltante, 2) }}
                            <span class="text-xs text-gray-400">{{ \App\Models\AlmacenProducto::unidadLabel($p->unidad, $p->faltante) }}</span>
                        </td>
                        <td class="px-3 py-4 text-sm text-right font-semibold {{ $p->precio_unitario !== null ? 'text-gray-800' : 'text-gray-400' }}">
                            {{ $p->precio_unitario !== null ? $money($p->costo_reposicion) : 'Sin precio' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>Ningún producto está por debajo de su stock mínimo.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="md:hidden divide-y divide-gray-100">
        @forelse ($alertas as $p)
            <div class="px-4 py-4 space-y-2">
                <div class="flex items-start justify-between gap-3">
                    <a href="{{ route('admin.almacen.show', $p->id) }}" class="font-medium text-gray-800 min-w-0">
                        {{ $p->nombre }}
                        @if ($p->stock <= 0)
                            <span class="ml-1 inline-flex items-center px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide rounded-full bg-red-100 text-red-700">Agotado</span>
                        @endif
                    </a>
                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700 flex-shrink-0">
                        {{ $catLabel($p->categoria) }}
                    </span>
                </div>
                <div class="grid grid-cols-3 gap-x-3 text-sm">
                    <div>
                        <p class="text-xs text-gray-500">Stock</p>
                        <p class="text-red-600 font-semibold">{{ number_format($p->stock, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Mínimo</p>
                        <p class="text-gray-700">{{ number_format($p->stock_minimo, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Faltante</p>
                        <p class="text-gray-700">
                            {{ number_format($p->faltante, 2) }}
                            <span class="text-xs text-gray-400">{{ \App\Models\AlmacenProducto::unidadLabel($p->unidad, $p->faltante) }}</span>
                        </p>
                    </div>
                </div>
                <p class="text-sm text-gray-700">
                    <span class="text-xs text-gray-500">Reposición: </span>
                    {{ $p->precio_unitario !== null ? $money($p->costo_reposicion) : 'Sin precio registrado' }}
                </p>
            </div>
        @empty
            <div class="px-6 py-10 text-center text-sm text-gray-500">
                <i class="fas fa-check-circle text-green-500 mr-2"></i>Ningún producto está por debajo de su stock mínimo.
            </div>
        @endforelse
    </div>
</section>

{{-- Caducidades --}}
<section id="caducidades" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden fade-in scroll-mt-24">
    <div class="px-4 sm:px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-3 flex-wrap">
        <div class="min-w-0">
            <h2 class="text-base font-semibold text-gray-800"><i class="fas fa-hourglass-half text-purple-500 mr-2"></i>Caducidades</h2>
            <p class="text-xs text-gray-500 mt-0.5">Productos perecederos con fecha de caducidad registrada</p>
        </div>
        <span class="text-xs text-gray-400">{{ $caducidades->count() }} productos con fecha</span>
    </div>

    <div class="hidden md:block">
        <table class="w-full admin-table">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Producto</th>
                    <th class="px-3 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Categoría</th>
                    <th class="px-3 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Stock</th>
                    <th class="px-3 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Caduca</th>
                    <th class="px-3 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Quedan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($caducidades as $p)
                    <tr class="hover:bg-gray-50 transition-colors {{ $p->dias_restantes < 0 ? 'bg-red-50/60' : '' }}">
                        <td class="px-3 py-4">
                            <a href="{{ route('admin.almacen.show', $p->id) }}" class="font-medium text-gray-800 hover:text-primary-600 truncate block">{{ $p->nombre }}</a>
                        </td>
                        <td class="px-3 py-4">
                            <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">{{ $catLabel($p->categoria) }}</span>
                        </td>
                        <td class="px-3 py-4 text-sm text-right text-gray-700">{{ number_format($p->stock, 2) }}</td>
                        <td class="px-3 py-4 text-sm text-gray-700">{{ $p->fecha_caducidad->format('d/m/Y') }}</td>
                        <td class="px-3 py-4 text-right">
                            @if ($p->dias_restantes < 0)
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">
                                    Vencido hace {{ abs($p->dias_restantes) }} {{ \Illuminate\Support\Str::plural('día', abs($p->dias_restantes)) }}
                                </span>
                            @elseif ($p->dias_restantes === 0)
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">Vence hoy</span>
                            @elseif ($p->dias_restantes <= 7)
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                    {{ $p->dias_restantes }} {{ \Illuminate\Support\Str::plural('día', $p->dias_restantes) }}
                                </span>
                            @elseif ($p->dias_restantes <= 30)
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-orange-100 text-orange-700">
                                    {{ $p->dias_restantes }} días
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600">
                                    {{ $p->dias_restantes }} días
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-500">
                            No hay perecederos con fecha de caducidad registrada.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="md:hidden divide-y divide-gray-100">
        @forelse ($caducidades as $p)
            <div class="px-4 py-4 space-y-2">
                <div class="flex items-start justify-between gap-3">
                    <a href="{{ route('admin.almacen.show', $p->id) }}" class="font-medium text-gray-800 min-w-0">{{ $p->nombre }}</a>
                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700 flex-shrink-0">
                        {{ $catLabel($p->categoria) }}
                    </span>
                </div>
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm text-gray-700">
                        <span class="text-xs text-gray-500">Caduca: </span>{{ $p->fecha_caducidad->format('d/m/Y') }}
                    </p>
                    @if ($p->dias_restantes < 0)
                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700 flex-shrink-0">
                            Vencido hace {{ abs($p->dias_restantes) }} d
                        </span>
                    @elseif ($p->dias_restantes === 0)
                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700 flex-shrink-0">Vence hoy</span>
                    @elseif ($p->dias_restantes <= 7)
                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 flex-shrink-0">
                            En {{ $p->dias_restantes }} d
                        </span>
                    @elseif ($p->dias_restantes <= 30)
                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-orange-100 text-orange-700 flex-shrink-0">
                            En {{ $p->dias_restantes }} d
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600 flex-shrink-0">
                            En {{ $p->dias_restantes }} d
                        </span>
                    @endif
                </div>
                <p class="text-sm text-gray-700">
                    <span class="text-xs text-gray-500">Stock: </span>{{ number_format($p->stock, 2) }}
                    <span class="text-xs text-gray-400">{{ \App\Models\AlmacenProducto::unidadLabel($p->unidad, $p->stock) }}</span>
                </p>
            </div>
        @empty
            <div class="px-6 py-10 text-center text-sm text-gray-500">
                No hay perecederos con fecha de caducidad registrada.
            </div>
        @endforelse
    </div>
</section>
@endsection
