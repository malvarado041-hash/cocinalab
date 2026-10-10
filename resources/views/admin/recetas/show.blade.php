@extends('admin.layout')

@section('title', $receta->Nombre)
@section('header-title', $receta->Nombre)

@section('content')
<div class="max-w-4xl space-y-6 fade-in">
    <div>
        <a href="{{ route('admin.recetas.index') }}" class="w-10 h-10 rounded-xl bg-white shadow-sm border border-gray-100 hover:bg-gray-50 inline-flex items-center justify-center transition" title="Volver">
            <i class="fas fa-arrow-left text-gray-600"></i>
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @if ($receta->imagenes->isNotEmpty())
            <div class="grid grid-cols-2 md:grid-cols-3 gap-2 p-4">
                @foreach ($receta->imagenes as $img)
                    <img src="{{ asset('storage/' . $img->path) }}" alt="{{ $receta->Nombre }}" class="w-full h-40 object-cover rounded-xl bg-gray-100" loading="lazy">
                @endforeach
            </div>
        @else
            <img src="{{ $receta->portada }}" alt="{{ $receta->Nombre }}" class="w-full h-64 object-cover bg-gray-100">
        @endif
        <div class="p-6">
            <span class="text-xs font-semibold uppercase tracking-wider text-primary-600 bg-primary-50 px-2 py-1 rounded">{{ $receta->TipoC }}</span>
            <h3 class="text-xl font-bold text-gray-800 mt-2">{{ $receta->Nombre }}</h3>
            @if ($receta->precio_platillo !== null)
                @php $mg = $receta->margenEstimado(); @endphp
                <p class="mt-2 text-sm">
                    <span class="font-semibold text-gray-800">Precio: ${{ number_format($receta->precio_platillo, 2) }}</span>
                    @if ($mg['margen'] !== null)
                        <span class="font-semibold {{ $mg['margen'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            · Margen {{ $mg['margen'] >= 0 ? '+' : '' }}${{ number_format($mg['margen'], 2) }}{{ $mg['porcentaje'] !== null ? ' (' . $mg['porcentaje'] . '%)' : '' }}
                        </span>
                    @endif
                </p>
            @endif
            <h4 class="font-semibold text-gray-800 mt-4 mb-2">Procedimiento</h4>
            <ol class="list-decimal list-inside space-y-1 text-gray-600">
                @forelse ($receta->pasos() as $paso)
                    <li>{{ $paso }}</li>
                @empty
                    <li class="list-none">Sin procedimiento registrado.</li>
                @endforelse
            </ol>
            <div class="mt-4 flex gap-2">
                <a href="{{ route('admin.recetas.edit', $receta) }}" class="btn-primary text-sm px-4 py-2"><i class="fas fa-edit mr-1"></i> Editar</a>
                <form action="{{ route('admin.recetas.destroy', $receta) }}" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-danger text-sm px-4 py-2" onclick="return confirm('¿Dar de baja esta receta?')">Dar de baja</button>
                </form>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h4 class="font-semibold text-gray-800 mb-3">Ingredientes y costo estimado</h4>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[560px] text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs uppercase text-gray-500">Ingrediente</th>
                        <th class="px-4 py-2 text-left text-xs uppercase text-gray-500">Cantidad</th>
                        <th class="px-4 py-2 text-left text-xs uppercase text-gray-500">Precio ref.</th>
                        <th class="px-4 py-2 text-right text-xs uppercase text-gray-500">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($costo['lineas'] as $l)
                        <tr>
                            <td class="px-4 py-2">{{ $l['ingrediente'] }}</td>
                            <td class="px-4 py-2 text-gray-600">
                                @if ($l['cantidad_num'] !== null)
                                    {{ rtrim(rtrim(number_format($l['cantidad_num'], 2), '0'), '.') }}{{ !empty($l['unidad_receta']) ? ' ' . $l['unidad_receta'] : '' }}
                                @else
                                    {{ $l['cantidad'] ?? '—' }}
                                @endif
                            </td>
                            <td class="px-4 py-2 text-gray-600">
                                @if ($l['precio'] !== null) ${{ number_format($l['precio'], 2) }}{{ $l['unidad'] ? '/' . $l['unidad'] : '' }}
                                @else <span class="text-amber-600">sin vincular</span> @endif
                            </td>
                            <td class="px-4 py-2 text-right">{{ $l['subtotal'] !== null ? '$' . number_format($l['subtotal'], 2) : '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-6 text-center text-gray-500">Sin ingredientes.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <p class="mt-3 text-right font-bold text-gray-800">Total estimado: ${{ number_format($costo['total'], 2) }}</p>
        @unless($costo['completo'])
            <p class="mt-1 text-right text-xs text-amber-600">* Parcial: vincula todos los ingredientes a almacén y usa cantidades numéricas.</p>
        @endunless
    </div>
</div>
@endsection
