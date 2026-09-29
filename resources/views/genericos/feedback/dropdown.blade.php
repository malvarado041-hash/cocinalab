<div x-data="{ open: false }" class="relative inline-block text-left" {{ $attributes }}>
    <div>
        <button @click="open = !open" 
                @keydown.escape="open = false"
                @keydown.tab="open = false"
                class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors
                       {{ $disabled ? 'opacity-50 cursor-not-allowed' : '' }}"
                aria-expanded="false"
                aria-haspopup="true"
                {{ $disabled ? 'disabled' : '' }}
                {{ $attributes->whereStartsWith('wire:') }}>
            {{ $slot ?? 'Menú' }}
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
    </div>

    <div x-show="open" 
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         @click.outside="open = false"
         class="absolute right-0 z-50 mt-2 w-48 origin-top-right rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
         role="menu"
         aria-orientation="vertical">
        
        @foreach($items ?? [] as $item)
            @if($item === 'divider' || ($item['divider'] ?? false))
                <hr class="my-1 border-gray-200" role="separator">
            @elseif(isset($item['submenu']))
                <div class="relative" x-data="{ subOpen: false }">
                    <button @click="subOpen = !subOpen"
                            class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 flex items-center justify-between"
                            aria-haspopup="true"
                            aria-expanded="false">
                        {{ $item['label'] }}
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <div x-show="subOpen" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:leave="transition ease-in duration-75"
                         @click.outside="subOpen = false"
                         class="absolute left-full top-0 ml-1 w-48 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5"
                         role="menu">
                        @foreach($item['submenu'] as $subItem)
                            <a href="{{ $subItem['url'] }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                               role="menuitem">{{ $subItem['label'] }}</a>
                        @endforeach
                    </div>
                </div>
            @else
                @if(isset($item['url']))
                    <a href="{{ $item['url'] }}" 
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100
                              {{ ($item['danger'] ?? false) ? 'text-red-600 hover:bg-red-50' : '' }}"
                       role="menuitem"
                       {{ $attributes->whereStartsWith('wire:') }}>
                        @if(isset($item['icon']))
                            <span class="flex items-center gap-2">
                                {{ $item['icon'] }}
                                {{ $item['label'] }}
                            </span>
                        @else
                            {{ $item['label'] }}
                        @endif
                        @if(isset($item['shortcut']))
                            <kbd class="ml-auto px-2 py-0.5 text-xs text-gray-400 bg-gray-100 rounded">{{ $item['shortcut'] }}</kbd>
                        @endif
                    </a>
                @else
                    <button @click="$dispatch('{{ $item['evento'] }}')"
                            class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100
                                   {{ ($item['danger'] ?? false) ? 'text-red-600 hover:bg-red-50' : '' }}"
                            role="menuitem"
                            {{ $attributes->whereStartsWith('wire:') }}>
                        @if(isset($item['icon']))
                            <span class="flex items-center gap-2">
                                {{ $item['icon'] }}
                                {{ $item['label'] }}
                            </span>
                        @else
                            {{ $item['label'] }}
                        @endif
                    </button>
                @endif
            @endif
        @endforeach
    </div>
</div>