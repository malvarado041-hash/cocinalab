<footer class="bg-gray-50 border-t border-gray-200" {{ $attributes }}>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="col-span-1 md:col-span-2">
                <a href="{{ route('home') }}" class="flex items-center space-x-2">
                    <svg class="h-8 w-8 text-orange-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C13.1 2 14 2.9 14 4V7H18V4C18 2.9 18.9 2 20 2V20C20 21.1 19.1 22 18 22H4C2.9 22 2 21.1 2 20V4C2 2.9 2.9 2 4 2H12ZM4 4H8V20H4V4ZM10 4H14V7H10V4ZM16 4H20V20H16V4Z"/>
                    </svg>
                    <span class="text-xl font-bold text-gray-900">{{ $brand ?? 'CocinaLab' }}</span>
                </a>
                <p class="mt-4 text-gray-600 max-w-xs">{{ $description ?? 'Tu comunidad de recetas favoritas. Comparte, descubre y disfruta cocinando.' }}</p>
                
                <div class="mt-6 flex space-x-6">
                    @foreach($socialLinks ?? [] as $link)
                        <a href="{{ $link['url'] }}" 
                           class="text-gray-400 hover:text-orange-600 transition-colors"
                           aria-label="{{ $link['label'] }}"
                           target="_blank"
                           rel="noopener noreferrer">
                            {{ $link['icon'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">{{ $sections['links']['title'] ?? 'Enlaces rápidos' }}</h3>
                <ul class="mt-4 space-y-3">
                    @foreach($sections['links']['items'] ?? [] as $item)
                        <li>
                            <a href="{{ $item['url'] }}" class="text-gray-600 hover:text-orange-600 transition-colors">{{ $item['label'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">{{ $sections['legal']['title'] ?? 'Legal' }}</h3>
                <ul class="mt-4 space-y-3">
                    @foreach($sections['legal']['items'] ?? [] as $item)
                        <li>
                            <a href="{{ $item['url'] }}" class="text-gray-600 hover:text-orange-600 transition-colors">{{ $item['label'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="mt-12 pt-8 border-t border-gray-200">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <p class="text-sm text-gray-500">{{ $copyright ?? '&copy; ' . now()->year . ' CocinaLab. Todos los derechos reservados.' }}</p>
                
                @if($version ?? false)
                    <p class="text-sm text-gray-400">Versión {{ $version }}</p>
                @endif
            </div>
        </div>
    </div>
</footer>