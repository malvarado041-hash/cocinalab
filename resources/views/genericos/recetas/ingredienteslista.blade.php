<div class="{{ $attributes->class ?? '' }}" {{ $attributes->except('class') }}>
    @if($titulo)
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            {{ $titulo }}
        </h3>
    @endif

    @if($porciones && $porcionesOriginales)
        <div class="mb-4 p-3 bg-orange-50 border border-orange-200 rounded-lg">
            <label for="porciones-select" class="text-sm font-medium text-gray-700">Ajustar para:</label>
            <select id="porciones-select" 
                    wire:model.live="porciones"
                    class="mt-1 block w-full sm:w-auto px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm">
                @foreach([1, 2, 3, 4, 6, 8, 10, 12] as $p)
                    <option value="{{ $p }}" {{ $porciones == $p ? 'selected' : '' }}>{{ $p }} persona{{ $p > 1 ? 's' : '' }}</option>
                @endforeach
            </select>
            <p class="mt-1 text-xs text-gray-500">Receta original para {{ $porcionesOriginales }} persona{{ $porcionesOriginales > 1 ? 's' : '' }}</p>
        </div>
    @endif

    <div class="space-y-2">
        @foreach($ingredientes ?? [] as $index => $ingrediente)
            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors {{ $ingrediente['opcional'] ?? false ? 'opacity-75' : '' }}">
                <input type="checkbox" 
                       id="ingrediente-{{ $index }}" 
                       class="h-5 w-5 text-orange-600 border-gray-300 rounded focus:ring-2 focus:ring-orange-500"
                       {{ $ingrediente['comprado'] ?? false ? 'checked' : '' }}
                       {{ $attributes->whereStartsWith('wire:model') }}>
                
                <label for="ingrediente-{{ $index }}" class="flex-1 cursor-pointer">
                    <div class="flex items-baseline space-x-2">
                        <span class="font-medium text-gray-900">{{ $ingrediente['nombre'] }}</span>
                        @if(isset($ingrediente['cantidad']))
                            <span class="text-sm font-medium text-orange-600 bg-orange-50 px-2 py-0.5 rounded">
                                {{ $porciones && $porcionesOriginales 
                                    ? number_format($ingrediente['cantidad'] * $porciones / $porcionesOriginales, 1) 
                                    : $ingrediente['cantidad'] }}
                                {{ $ingrediente['unidad'] ?? '' }}
                            </span>
                        @endif
                        @if($ingrediente['opcional'] ?? false)
                            <span class="text-xs text-gray-500 italic">(opcional)</span>
                        @endif
                    </div>
                    @if(isset($ingrediente['nota']))
                        <p class="text-xs text-gray-500 mt-0.5">{{ $ingrediente['nota'] }}</p>
                    @endif
                </label>

                @if($editable)
                    <button wire:click="editarIngrediente({{ $index }})" 
                            class="p-1 text-gray-400 hover:text-gray-600 transition-colors"
                            aria-label="Editar ingrediente">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <button wire:click="eliminarIngrediente({{ $index }})" 
                            class="p-1 text-gray-400 hover:text-red-600 transition-colors"
                            aria-label="Eliminar ingrediente">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                @endif
            </div>
        @endforeach

        @if($ingredientes->isEmpty() ?? false)
            <p class="text-center text-gray-500 py-8">{{ $emptyMessage ?? 'No hay ingredientes agregados aún.' }}</p>
        @endif
    </div>

    @if($editable)
        <button wire:click="agregarIngrediente" 
                class="mt-4 w-full flex items-center justify-center gap-2 px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-orange-400 hover:text-orange-600 hover:bg-orange-50 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Añadir ingrediente</span>
        </button>
    @endif

    @if($mostrarTotal)
        <div class="mt-4 pt-4 border-t border-gray-200">
            <p class="text-sm text-gray-600">
                Total: <span class="font-medium text-gray-900">{{ $totalIngredientes ?? count($ingredientes ?? []) }} ingredientes</span>
            </p>
        </div>
    @endif
</div>