<article class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300 {{ $attributes->class ?? '' }}" {{ $attributes->except('class') }}>
    @if($imagen)
        <div class="relative aspect-video overflow-hidden">
            <img src="{{ $imagen }}" alt="{{ $altImagen ?? $titulo }}" class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
            
            @if($tiempo || $dificultad || $porciones)
                <div class="absolute bottom-3 left-3 right-3 flex flex-wrap gap-2">
                    @if($tiempo)
                        <span class="px-2 py-1 bg-black/70 text-white text-xs font-medium rounded flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $tiempo }}
                        </span>
                    @endif
                    
                    @if($dificultad)
                        <span class="px-2 py-1 bg-black/70 text-white text-xs font-medium rounded flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            {{ $dificultad }}
                        </span>
                    @endif
                    
                    @if($porciones)
                        <span class="px-2 py-1 bg-black/70 text-white text-xs font-medium rounded flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            {{ $porciones }}
                        </span>
                    @endif
                </div>
            @endif

            @if($etiquetas)
                <div class="absolute top-3 left-3 flex flex-wrap gap-1">
                    @foreach($etiquetas as $etiqueta)
                        <span class="px-2 py-0.5 bg-orange-600 text-white text-xs font-medium rounded">{{ $etiqueta }}</span>
                    @endforeach
                </div>
            @endif

            @if($favorito !== null)
                <button wire:click="toggleFavorito({{ $receta->id ?? $id }})" 
                        class="absolute top-3 right-3 p-2 rounded-full bg-white/90 hover:bg-white transition-colors"
                        aria-label="{{ $favorito ? 'Quitar de favoritos' : 'Añadir a favoritos' }}">
                    <svg class="w-5 h-5 {{ $favorito ? 'text-red-500 fill-current' : 'text-gray-600' }}" 
                         fill="{{ $favorito ? 'currentColor' : 'none' }}" 
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </button>
            @endif
        </div>
    @endif

    <div class="p-4">
        @if($categoria)
            <span class="inline-block px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-700 rounded mb-2">{{ $categoria }}</span>
        @endif

        <h3 class="text-lg font-semibold text-gray-900 mb-1 line-clamp-1">
            <a href="{{ $url ?? route('recetas.show', $receta->id ?? $id) }}" class="hover:text-orange-600 transition-colors">
                {{ $titulo }}
            </a>
        </h3>

        @if($descripcion)
            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $descripcion }}</p>
        @endif

        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                @if($autor)
                    <img src="{{ $autor['avatar'] ?? asset('images/default-avatar.png') }}" 
                         alt="{{ $autor['nombre'] }}" 
                         class="h-6 w-6 rounded-full object-cover border border-gray-200">
                    <span class="text-sm text-gray-500">{{ $autor['nombre'] }}</span>
                @endif
            </div>

            <div class="flex items-center space-x-1">
                @if($rating !== null)
                    <span class="text-sm font-medium text-gray-900">{{ number_format($rating, 1) }}</span>
                    <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                @endif
            </div>
        </div>
    </div>
</article>