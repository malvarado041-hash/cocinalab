<aside class="w-64 bg-white border-r border-gray-200 h-screen fixed inset-y-0 left-0 z-40 transform transition-transform duration-300 ease-in-out lg:translate-x-0" 
     :class="{ '-translate-x-full': !open }"
     x-data="{ open: @entangle($wire.sidebarOpen ?? false) }"
     x-show="open"
     @click.outside="open = false"
     {{ $attributes }}>
    
    <div class="flex flex-col h-full">
        <div class="p-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">{{ $title ?? 'Navegación' }}</h2>
        </div>

        <nav class="flex-1 overflow-y-auto p-4 space-y-1" aria-label="{{ $title ?? 'Sidebar navigation' }}">
            @foreach($sections ?? [] as $section)
                @if(isset($section['label']))
                    <div class="pt-4 pb-2">
                        <h3 class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ $section['label'] }}</h3>
                    </div>
                @endif

                @foreach($section['items'] ?? [] as $item)
                    <a href="{{ $item['url'] }}" 
                       class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors
                              {{ $item['active'] ?? false 
                                  ? 'bg-orange-50 text-orange-600' 
                                  : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}"
                       {{ $attributes->whereStartsWith('wire:') }}>
                        @if(isset($item['icon']))
                            <span class="flex-shrink-0 mr-3 h-5 w-5">
                                {{ $item['icon'] }}
                            </span>
                        @endif
                        {{ $item['label'] }}
                        @if(isset($item['badge']))
                            <span class="ml-auto px-2 py-0.5 text-xs font-medium bg-orange-100 text-orange-600 rounded-full">
                                {{ $item['badge'] }}
                            </span>
                        @endif
                    </a>
                @endforeach
            @endforeach
        </nav>

        <div class="p-4 border-t border-gray-200">
            @if($footer)
                {{ $footer }}
            @endif
        </div>
    </div>
</aside>

<div x-show="open" 
     x-transition:enter="transition ease-in-out duration-300" 
     x-transition:enter-start="opacity-0" 
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in-out duration-300" 
     x-transition:leave-start="opacity-100" 
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-gray-900/50 z-30 lg:hidden"
     @click="open = false"
     aria-hidden="true"></div>