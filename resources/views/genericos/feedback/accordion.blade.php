<div {{ $attributes->merge(['class' => 'space-y-2']) }}>
    @foreach($items ?? [] as $index => $item)
        <div class="border border-gray-200 rounded-lg overflow-hidden {{ $item['abierto'] ?? false ? 'border-orange-300' : '' }}"
             x-data="{ open: {{ $item['abierto'] ?? false ? 'true' : 'false' }} }"
             @if($multiple !== true)
                 @this.on('accordion:opened', (id) => { if (id !== '{{ $item['id'] ?? $index }}') open = false; })
             @endif>
            
            <button @click="open = !open; $dispatch('accordion:opened', '{{ $item['id'] ?? $index }}')"
                    class="w-full px-4 py-4 text-left flex items-center justify-between gap-4 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-inset
                           {{ $item['abierto'] ?? false ? 'bg-orange-50' : 'hover:bg-gray-50' }}"
                    aria-expanded="{{ $item['abierto'] ?? false ? 'true' : 'false' }}"
                    aria-controls="accordion-content-{{ $item['id'] ?? $index }}">
                
                <div class="flex items-center gap-3 flex-1">
                    @if(isset($item['icon']))
                        <span class="flex-shrink-0 text-gray-400">{{ $item['icon'] }}</span>
                    @endif
                    
                    <span class="font-medium text-gray-900">{{ $item['titulo'] ?? $item['label'] }}</span>
                    
                    @if(isset($item['badge']))
                        <span class="ml-auto px-2 py-0.5 text-xs font-medium bg-orange-100 text-orange-600 rounded-full">
                            {{ $item['badge'] }}
                        </span>
                    @endif
                </div>

                <svg class="w-5 h-5 text-gray-400 flex-shrink-0 transition-transform duration-200" 
                     :class="{ 'rotate-180': open }"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform -translate-y-1"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 transform translate-y-0"
                 x-transition:leave-end="opacity-0 transform -translate-y-1"
                 id="accordion-content-{{ $item['id'] ?? $index }}"
                 role="region"
                 aria-labelledby="accordion-header-{{ $item['id'] ?? $index }}"
                 class="px-4 pb-4 border-t border-gray-100 bg-gray-50">
                {{ $item['contenido'] }}
            </div>
        </div>
    @endforeach
</div>