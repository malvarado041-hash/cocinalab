<div {{ $attributes->merge(['class' => '']) }}>
    @if($titulo)
        <header class="mb-4">
            <h2 class="text-lg font-semibold text-gray-900">{{ $titulo }}</h2>
            @if($descripcion)
                <p class="text-sm text-gray-500 mt-1">{{ $descripcion }}</p>
            @endif
        </header>
    @endif

    <div role="tablist" aria-label="{{ $ariaLabel ?? $titulo ?? 'Pestañas' }}"
         class="border-b border-gray-200 {{ $variante === 'pills' ? '' : '' }}">
        <nav class="{{ $variante === 'pills' ? 'flex gap-1 p-1 bg-gray-100 rounded-lg' : '-mb-px flex space-x-8' }}" 
             x-data="{ activeTab: '{{ $active ?? ($tabs[0]['id'] ?? '') }}' }">
            @foreach($tabs ?? [] as $index => $tab)
                @php
                    $id = $tab['id'] ?? Str::slug($tab['label']);
                    $esActivo = $active ?? ($index === 0 ? $id : null) === $id;
                @endphp

                <button @click="activeTab = '{{ $id }}'; $wire.set('{{ $wireModel }}', '{{ $id }}')"
                        :class="activeTab === '{{ $id }}' 
                            ? '{{ $variante === "pills" 
                                ? "bg-white text-gray-900 shadow-sm" 
                                : "border-orange-600 text-orange-600" }}' 
                            : '{{ $variante === "pills" 
                                ? "text-gray-600 hover:text-gray-900" 
                                : "border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" }}'
                            {{ $tab['disabled'] ? 'opacity-50 cursor-not-allowed' : '' }}"
                        class="{{ $variante === 'pills' 
                            ? 'px-4 py-2 rounded-md' 
                            : 'px-1 py-4 border-b-2 text-sm font-medium transition-colors' }}"
                        role="tab"
                        aria-selected="{{ $esActivo ? 'true' : 'false' }}"
                        aria-controls="panel-{{ $id }}"
                        id="tab-{{ $id }}"
                        {{ $tab['disabled'] ? 'disabled' : '' }}
                        {{ $attributes->whereStartsWith('wire:') }}>
                    @if(isset($tab['icon']))
                        <span class="flex items-center gap-1.5">
                            {{ $tab['icon'] }}
                            {{ $tab['label'] }}
                        </span>
                    @else
                        {{ $tab['label'] }}
                    @endif
                    @if(isset($tab['badge']))
                        <span class="ml-2 px-1.5 py-0.5 text-xs font-medium {{ $esActivo ? 'bg-orange-100 text-orange-600' : 'bg-gray-100 text-gray-600' }} rounded-full">
                            {{ $tab['badge'] }}
                        </span>
                    @endif
                </button>
            @endforeach
        </nav>
    </div>

    <div class="mt-4">
        @foreach($tabs ?? [] as $index => $tab)
            @php
                $id = $tab['id'] ?? Str::slug($tab['label']);
                $esActivo = $active ?? ($index === 0 ? $id : null) === $id;
            @endphp

            <div x-show="activeTab === '{{ $id }}'" 
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 role="tabpanel"
                 id="panel-{{ $id }}"
                 aria-labelledby="tab-{{ $id }}"
                 {{ !$esActivo ? 'hidden' : '' }}>
                {{ $tab['contenido'] ?? $slot }}
            </div>
        @endforeach
    </div>
</div>