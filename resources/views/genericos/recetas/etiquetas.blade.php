<div class="{{ $attributes->class ?? '' }}" {{ $attributes->except('class') }}>
    @if($titulo)
        <h4 class="text-sm font-medium text-gray-700 mb-2">{{ $titulo }}</h4>
    @endif

    <div class="flex flex-wrap gap-2" role="list" aria-label="{{ $titulo ?? 'Etiquetas' }}">
        @foreach($etiquetas ?? [] as $etiqueta)
            @php
                $esArray = is_array($etiqueta);
                $nombre = $esArray ? ($etiqueta['nombre'] ?? $etiqueta['label'] ?? '') : $etiqueta;
                $color = $esArray ? ($etiqueta['color'] ?? 'gray') : 'gray';
                $icono = $esArray ? ($etiqueta['icono'] ?? '') : '';
                $contador = $esArray ? ($etiqueta['contador'] ?? '') : '';
                $url = $esArray ? ($etiqueta['url'] ?? '') : '';
                $removible = $esArray ? ($etiqueta['removible'] ?? false) : false;
                $seleccionada = $esArray ? ($etiqueta['seleccionada'] ?? false) : false;
            @endphp

            @if($url)
                <a href="{{ $url }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1 text-sm font-medium rounded-full transition-colors
                          {{ $colores[$color] ?? 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}
                          {{ $seleccionada ? 'ring-2 ring-offset-2 ' . ($coloresRing[$color] ?? 'ring-gray-400') : '' }}"
                   role="listitem">
                    @if($icono)
                        <span class="flex-shrink-0">{{ $icono }}</span>
                    @endif
                    {{ $nombre }}
                    @if($contador !== '')
                        <span class="px-1.5 py-0.5 text-xs font-medium bg-white/50 rounded-full">{{ $contador }}</span>
                    @endif
                </a>
            @elseif($removible || $interactivo)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-sm font-medium rounded-full
                              {{ $colores[$color] ?? 'bg-gray-100 text-gray-700' }}
                              {{ $seleccionada ? 'ring-2 ring-offset-2 ' . ($coloresRing[$color] ?? 'ring-gray-400') : '' }}"
                      role="listitem">
                    @if($icono)
                        <span class="flex-shrink-0">{{ $icono }}</span>
                    @endif
                    {{ $nombre }}
                    @if($contador !== '')
                        <span class="px-1.5 py-0.5 text-xs font-medium bg-white/50 rounded-full">{{ $contador }}</span>
                    @endif
                    @if($removible)
                        <button wire:click="removerEtiqueta('{{ $nombre }}')"
                                class="ml-1 p-0.5 rounded-full hover:bg-white/30 transition-colors"
                                aria-label="Eliminar {{ $nombre }}">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    @endif
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-sm font-medium rounded-full
                              {{ $colores[$color] ?? 'bg-gray-100 text-gray-700' }}"
                      role="listitem">
                    @if($icono)
                        <span class="flex-shrink-0">{{ $icono }}</span>
                    @endif
                    {{ $nombre }}
                    @if($contador !== '')
                        <span class="px-1.5 py-0.5 text-xs font-medium bg-white/50 rounded-full">{{ $contador }}</span>
                    @endif
                </span>
            @endif
        @endforeach
    </div>

    @if($editable && !$interactivo)
        <div class="mt-3">
            <x-genericos.inputsgenerico 
                name="nueva_etiqueta" 
                placeholder="Añadir etiqueta..." 
                wire:model.debounce.300ms="nuevaEtiqueta"
                wire:keydown.enter="agregarEtiqueta"
                class="w-full sm:w-64" />
        </div>
    @endif
</div>

@php
    $colores = [
        'gray' => 'bg-gray-100 text-gray-700 hover:bg-gray-200',
        'red' => 'bg-red-100 text-red-700 hover:bg-red-200',
        'orange' => 'bg-orange-100 text-orange-700 hover:bg-orange-200',
        'amber' => 'bg-amber-100 text-amber-700 hover:bg-amber-200',
        'yellow' => 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200',
        'green' => 'bg-green-100 text-green-700 hover:bg-green-200',
        'emerald' => 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200',
        'teal' => 'bg-teal-100 text-teal-700 hover:bg-teal-200',
        'blue' => 'bg-blue-100 text-blue-700 hover:bg-blue-200',
        'indigo' => 'bg-indigo-100 text-indigo-700 hover:bg-indigo-200',
        'purple' => 'bg-purple-100 text-purple-700 hover:bg-purple-200',
        'pink' => 'bg-pink-100 text-pink-700 hover:bg-pink-200',
    ];

    $coloresRing = [
        'gray' => 'ring-gray-400',
        'red' => 'ring-red-400',
        'orange' => 'ring-orange-400',
        'amber' => 'ring-amber-400',
        'yellow' => 'ring-yellow-400',
        'green' => 'ring-green-400',
        'emerald' => 'ring-emerald-400',
        'teal' => 'ring-teal-400',
        'blue' => 'ring-blue-400',
        'indigo' => 'ring-indigo-400',
        'purple' => 'ring-purple-400',
        'pink' => 'ring-pink-400',
    ];
@endphp