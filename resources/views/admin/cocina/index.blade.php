@extends('admin.layout')

@section('title', 'Cocina')

@section('header-title', 'Pantalla de cocina')

@section('header-actions')
<button type="button" onclick="location.reload()" class="btn-secondary text-sm px-4 py-2">
    <i class="fas fa-rotate mr-2"></i> Actualizar
</button>
@endsection

@section('content')
<div class="fade-in" id="kds">
    @if ($items->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-6 py-16 text-center">
            <div class="flex flex-col items-center gap-4">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-3xl text-green-600"></i>
                </div>
                <p class="text-lg font-medium text-gray-800">Cocina al día</p>
                <p class="text-sm text-gray-500">No hay platillos pendientes ni en preparación.</p>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach ($items as $comandaId => $grupo)
                @php $comanda = $grupo->first()->comanda; @endphp
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-3 {{ $comanda->tipo === 'llevar' ? 'bg-purple-50' : '' }}">
                        <div class="min-w-0 flex-1">
                            <h3 class="font-semibold text-gray-800 truncate">{{ $comanda->mesa }}</h3>
                            <p class="text-xs text-gray-500">
                                {{ $comanda->tipo_label }} · {{ $comanda->mesero->name ?? '—' }} ·
                                hace {{ $comanda->created_at->diffForHumans(null, true) }}
                            </p>
                        </div>
                        <span class="text-xs text-gray-500">{{ $grupo->count() }} plat.</span>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach ($grupo as $item)
                            <div class="px-5 py-3 flex items-center gap-3 {{ $item->estado === 'en_preparacion' ? 'bg-amber-50' : '' }}">
                                <div class="min-w-0 flex-1">
                                    <p class="font-medium text-gray-800">{{ $item->cantidad }} × {{ $item->receta->Nombre ?? '—' }}</p>
                                    @if ($item->notas)
                                        <p class="text-xs text-amber-700 font-medium">{{ $item->notas }}</p>
                                    @endif
                                    <p class="text-xs text-gray-400">
                                        {{ $item->estado === 'pendiente' ? 'Pendiente' : 'En preparación' }} ·
                                        hace {{ $item->created_at->diffForHumans(null, true) }}
                                    </p>
                                </div>
                                <form action="{{ route('admin.cocina.avanzar', $item) }}" method="POST" class="flex-shrink-0">
                                    @csrf @method('PUT')
                                    <button type="submit" class="{{ $item->estado === 'pendiente' ? 'btn-secondary' : 'btn-primary' }} text-sm px-4 py-2">
                                        @if ($item->estado === 'pendiente')
                                            <i class="fas fa-fire mr-2"></i> Tomar
                                        @else
                                            <i class="fas fa-check mr-2"></i> Listo
                                        @endif
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// La pantalla se refresca sola cada 45 segundos.
setTimeout(function() { location.reload(); }, 45000);
</script>
@endpush
