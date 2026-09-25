<div class="{{ $attributes->class ?? '' }}" {{ $attributes->except('class') }}>
    @if($titulo)
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
            </svg>
            {{ $titulo }}
        </h3>
    @endif

    @if($tiempoTotal)
        <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-medium text-green-800">Tiempo total estimado: {{ $tiempoTotal }}</span>
        </div>
    @endif

    <div class="space-y-6">
        @foreach($pasos ?? [] as $index => $paso)
            <div class="relative {{ !$loop->last ? 'pb-6' : '' }}">
                {{-- Línea de conexión --}}
                @if(!$loop->first)
                    <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-gray-200" aria-hidden="true"></div>
                @endif

                <div class="flex gap-4">
                    {{-- Número de paso --}}
                    <div class="relative z-10 flex-shrink-0">
                        <div class="w-12 h-12 rounded-full bg-orange-600 text-white flex items-center justify-center text-lg font-bold shadow-lg">
                            {{ $index + 1 }}
                        </div>
                    </div>

                    {{-- Contenido del paso --}}
                    <div class="flex-1 pt-1">
                        @if(isset($paso['imagen']))
                            <div class="mb-3 relative aspect-video rounded-lg overflow-hidden">
                                <img src="{{ $paso['imagen'] }}" alt="Paso {{ $index + 1 }}" class="w-full h-full object-cover">
                            </div>
                        @endif

                        <div class="prose prose-sm max-w-none">
                            <p class="text-gray-700 leading-relaxed">{{ $paso['texto'] ?? $paso }}</p>
                            
                            @if(isset($paso['tiempo']))
                                <p class="mt-2 text-sm text-gray-500 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Tiempo: {{ $paso['tiempo'] }}
                                </p>
                            @endif

                            @if(isset($paso['temperatura']))
                                <p class="mt-1 text-sm text-gray-500 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    Temperatura: {{ $paso['temperatura'] }}
                                </p>
                            @endif

                            @if(isset($paso['consejo']))
                                <div class="mt-2 p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded-r">
                                    <p class="text-sm text-yellow-800 flex items-center gap-1">
                                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                        </svg>
                                        <strong>Consejo:</strong> {{ $paso['consejo'] }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        @if(($pasos ?? [])->isEmpty())
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <p class="mt-2 text-gray-500">{{ $emptyMessage ?? 'No hay pasos definidos aún.' }}</p>
            </div>
        @endif
    </div>

    @if($editable)
        <button wire:click="agregarPaso" 
                class="mt-6 w-full flex items-center justify-center gap-2 px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-orange-400 hover:text-orange-600 hover:bg-orange-50 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Añadir paso</span>
        </button>
    @endif
</div>