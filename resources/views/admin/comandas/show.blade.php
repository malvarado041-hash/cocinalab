@extends('admin.layout')

@section('title', 'Cuenta ' . $comanda->mesa)

@section('header-title', 'Cuenta: ' . $comanda->mesa)

@section('header-actions')
<a href="{{ route('admin.comandas.index') }}" class="btn-secondary text-sm px-4 py-2">
    <i class="fas fa-arrow-left mr-2"></i> Volver
</a>
@endsection

@section('content')
@php
    $estadoColors = [
        'abierta' => 'blue', 'en_cocina' => 'amber', 'lista' => 'green',
        'servida' => 'gray', 'cancelada' => 'red',
    ];
    $itemColors = [
        'pendiente' => 'gray', 'en_preparacion' => 'amber', 'listo' => 'green',
        'servido' => 'blue', 'cancelado' => 'red',
    ];
    $color = $estadoColors[$comanda->estado] ?? 'gray';
@endphp
<div class="max-w-4xl mx-auto space-y-4 fade-in">
    @if (session('error'))
        <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg" role="alert">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-wrap items-center gap-3">
            <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full bg-{{ $color }}-100 text-{{ $color }}-700">
                {{ $comanda->estado_label }}
            </span>
            <span class="text-sm text-gray-500">{{ $comanda->tipo_label }} · {{ $comanda->mesero->name ?? '—' }} · {{ $comanda->created_at->format('d/m/Y H:i') }}</span>
            <span class="ml-auto text-xl font-semibold text-gray-800">Total: ${{ number_format($comanda->total, 2) }}</span>
        </div>
        @if ($comanda->notas)
            <p class="text-sm text-gray-600 mt-3"><i class="fas fa-sticky-note text-gray-400 mr-2"></i>{{ $comanda->notas }}</p>
        @endif

        @if ($puedeOperar ?? false)
        <div class="mt-4 flex flex-wrap items-center gap-2">
            @if ($comanda->estado === 'abierta')
                <a href="{{ route('admin.comandas.edit', $comanda) }}" class="btn-secondary text-sm px-4 py-2">
                    <i class="fas fa-edit mr-2"></i> Editar datos
                </a>
                <form action="{{ route('admin.comandas.enviar-cocina', $comanda) }}" method="POST" class="inline">
                    @csrf @method('PUT')
                    <button type="submit" class="btn-primary text-sm px-4 py-2">
                        <i class="fas fa-fire mr-2"></i> Enviar a cocina
                    </button>
                </form>
            @endif
            @if ($comanda->estado === 'lista')
                <form action="{{ route('admin.comandas.marcar-servida', $comanda) }}" method="POST" class="inline">
                    @csrf @method('PUT')
                    <button type="submit" class="btn-primary text-sm px-4 py-2">
                        <i class="fas fa-bell-concierge mr-2"></i> Marcar servida
                    </button>
                </form>
            @endif
            @if (!in_array($comanda->estado, ['servida', 'cancelada'], true))
                <form action="{{ route('admin.comandas.cancelar', $comanda) }}" method="POST" class="inline">
                    @csrf @method('PUT')
                    <button type="submit" class="btn-danger text-sm px-4 py-2"
                        onclick="return confirm('¿Cancelar la cuenta {{ $comanda->mesa }}?')">
                        <i class="fas fa-ban mr-2"></i> Cancelar cuenta
                    </button>
                </form>
            @endif
        </div>
        @endif
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Platillos ({{ $comanda->items->count() }})</h3>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse ($comanda->items as $item)
                @php $ic = $itemColors[$item->estado] ?? 'gray'; @endphp
                <div class="px-6 py-4 flex flex-wrap items-center gap-3">
                    <div class="min-w-0 flex-1">
                        <p class="font-medium text-gray-800">{{ $item->cantidad }} × {{ $item->receta->Nombre ?? '—' }}</p>
                        @if ($item->notas)
                            <p class="text-xs text-gray-500">{{ $item->notas }}</p>
                        @endif
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-{{ $ic }}-100 text-{{ $ic }}-700">
                        {{ $item->estado_label }}
                    </span>
                    <span class="font-semibold text-gray-800">${{ number_format($item->subtotal, 2) }}</span>
                    @if (($puedeOperar ?? false) && $comanda->esAbierta() && $item->estado === 'pendiente')
                        <form action="{{ route('admin.comandas.items.destroy', [$comanda, $item]) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm px-2 py-1" title="Quitar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    @endif
                </div>
            @empty
                <p class="px-6 py-8 text-center text-gray-500">Sin platillos.</p>
            @endforelse
        </div>

        @if (($puedeOperar ?? false) && $comanda->esAbierta())
        <div class="px-6 py-4 border-t border-gray-100">
            <form action="{{ route('admin.comandas.items.store', $comanda) }}" method="POST" class="grid grid-cols-12 gap-2 items-center">
                @csrf
                <select name="receta_id" required class="col-span-6 px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">Agregar platillo…</option>
                    @foreach (\App\Models\Receta::whereNotNull('precio_platillo')->orderBy('Nombre')->get(['id', 'Nombre', 'precio_platillo']) as $r)
                        <option value="{{ $r->id }}">{{ $r->Nombre }} — ${{ number_format($r->precio_platillo, 2) }}</option>
                    @endforeach
                </select>
                <input type="number" name="cantidad" value="1" required min="1" max="99" class="col-span-2 px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                <input type="text" name="notas" placeholder="Notas" maxlength="255" class="col-span-3 px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                <button type="submit" class="col-span-1 btn-primary text-sm px-3 py-2" title="Agregar"><i class="fas fa-plus"></i></button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
