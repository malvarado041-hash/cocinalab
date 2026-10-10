@extends('admin.layout')

@section('title', 'Recetas')
@section('header-title', 'Recetas')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden fade-in">
    <div class="px-4 sm:px-6 py-4 flex flex-col lg:flex-row lg:items-center gap-3 border-b border-gray-100">
        <form method="GET" action="{{ route('admin.recetas.index') }}" class="flex-[1_1_auto] grid grid-cols-1 sm:grid-cols-3 gap-3">
            <input type="text" name="q" value="{{ $filtros['q'] ?? '' }}" placeholder="Buscar receta..."
                class="px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 w-full">
            <select name="tipo" onchange="this.form.submit()"
                class="px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 w-full">
                <option value="">Todos los tipos</option>
                @foreach ($tipos as $t)
                    <option value="{{ $t }}" @selected(($filtros['tipo'] ?? '') === $t)>{{ $t }}</option>
                @endforeach
            </select>
            <div class="flex items-center gap-2">
                <button type="submit" class="btn-primary text-sm px-4 py-2"><i class="fas fa-search mr-1"></i> Filtrar</button>
                @if (!empty($filtros['q']) || !empty($filtros['tipo']))
                    <a href="{{ route('admin.recetas.index') }}" class="text-sm text-gray-500 hover:text-gray-700 px-2 py-2">Limpiar</a>
                @endif
            </div>
        </form>
        <div class="flex gap-2 lg:ml-auto">
            @if ($canRestore ?? false)
                <a href="{{ route('admin.recetas.trashed') }}" class="btn-secondary text-sm px-4 py-2 text-center">
                    <i class="fas fa-box-open mr-2"></i> Dadas de baja
                </a>
            @endif
            <a href="{{ route('admin.recetas.create') }}" class="btn-primary text-sm px-4 py-2 text-center">
                <i class="fas fa-plus mr-2"></i> Nueva receta
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[720px]">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Receta</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Tipo</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Ingredientes</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Costo est.</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Precio / margen</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($recetas as $r)
                    @php $c = $r->costoEstimado(); $m = $r->margenEstimado(); @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $r->portada }}" alt="{{ $r->Nombre }}" class="w-12 h-12 rounded-lg object-cover bg-gray-100" loading="lazy">
                                <span class="font-medium text-gray-800">{{ $r->Nombre }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $r->TipoC }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $r->ingredientes->count() }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">
                            ${{ number_format($c['total'], 2) }}
                            @unless($c['completo'])<span class="text-xs text-amber-600" title="Faltan precios o cantidades">*</span>@endunless
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-800">
                            @if ($r->precio_platillo !== null)
                                ${{ number_format($r->precio_platillo, 2) }}
                                @if ($m['margen'] !== null)
                                    <span class="text-xs font-semibold {{ $m['margen'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        ({{ $m['margen'] >= 0 ? '+' : '' }}${{ number_format($m['margen'], 2) }})
                                    </span>
                                @endif
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.recetas.show', $r) }}" class="w-9 h-9 rounded-lg bg-gray-100 hover:bg-gray-200 inline-flex items-center justify-center" title="Ver"><i class="fas fa-eye text-gray-600"></i></a>
                                <a href="{{ route('admin.recetas.edit', $r) }}" class="w-9 h-9 rounded-lg bg-blue-50 hover:bg-blue-100 inline-flex items-center justify-center" title="Editar"><i class="fas fa-edit text-blue-600"></i></a>
                                <form action="{{ route('admin.recetas.destroy', $r) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-9 h-9 rounded-lg bg-red-50 hover:bg-red-100 inline-flex items-center justify-center" title="Dar de baja"
                                        onclick="return confirm('¿Dar de baja {{ $r->Nombre }}?')"><i class="fas fa-arrow-down text-red-600"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-16 text-center text-gray-500">No hay recetas. Crea la primera.</td></tr>
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
