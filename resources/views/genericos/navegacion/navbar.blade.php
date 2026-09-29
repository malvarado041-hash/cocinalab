<nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50" {{ $attributes->merge(['role' => 'navigation']) }}>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center space-x-2" {{ $attributes->whereStartsWith('wire:') }}>
                    <svg class="h-8 w-8 text-orange-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C13.1 2 14 2.9 14 4V7H18V4C18 2.9 18.9 2 20 2V20C20 21.1 19.1 22 18 22H4C2.9 22 2 21.1 2 20V4C2 2.9 2.9 2 4 2H12ZM4 4H8V20H4V4ZM10 4H14V7H10V4ZM16 4H20V20H16V4Z"/>
                    </svg>
                    <span class="text-xl font-bold text-gray-900">{{ $brand ?? 'CocinaLab' }}</span>
                </a>
            </div>

            <div class="hidden md:flex md:items-center md:space-x-4">
                @foreach($links ?? [] as $link)
                    <a href="{{ $link['url'] }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium transition-colors 
                              {{ $link['active'] ?? false 
                                  ? 'bg-orange-50 text-orange-600' 
                                  : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </div>

            <div class="flex items-center space-x-4">
                @if($search ?? false)
                    <div class="hidden sm:block relative">
                        <input type="search" 
                               placeholder="{{ $searchPlaceholder ?? 'Buscar recetas...' }}"
                               class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm"
                               {{ $attributes->whereStartsWith('wire:model') }}>
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                @endif

                @if(auth()->check())
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 transition-colors"
                                @click.outside="open = false"
                                aria-expanded="false"
                                aria-haspopup="true">
                            <div class="h-8 w-8 rounded-full bg-orange-100 flex items-center justify-center">
                                <span class="text-orange-600 font-medium text-sm">
                                    {{ auth()->user()->name[0] ?? 'U' }}
                                </span>
                            </div>
                            <span class="hidden md:block text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open" x-transition:enter="transition ease-out duration-100" 
                             x-transition:enter-start="transform opacity-0 scale-95" 
                             x-transition:enter-end="transform opacity-100 scale-100"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                            <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Mi perfil</a>
                            <a href="{{ route('mis-recetas') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Mis recetas</a>
                            <a href="{{ route('favoritos') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Favoritos</a>
                            <hr class="my-1 border-gray-200">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Cerrar sesión</button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-orange-600">Iniciar sesión</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium text-white bg-orange-600 rounded-lg hover:bg-orange-700">Registrarse</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</nav>