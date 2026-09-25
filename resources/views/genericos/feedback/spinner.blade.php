<div class="flex items-center justify-center {{ $attributes->class ?? '' }}" {{ $attributes->except('class') }} role="status" aria-label="{{ $label ?? 'Cargando...' }}">
    @if($tipo === 'spinner')
        <svg class="animate-spin h-{{ $tamano ?? 8 }} w-{{ $tamano ?? 8 }} text-{{ $color ?? 'orange' }}-600" 
             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
             aria-hidden="true">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        
        @if($label)
            <span class="sr-only">{{ $label }}</span>
        @endif

    @elseif($tipo === 'dots')
        <div class="flex gap-1" aria-hidden="true">
            @for($i = 1; $i <= 3; $i++)
                <div class="w-{{ $tamano ?? 2 }} h-{{ $tamano ?? 2 }} bg-{{ $color ?? 'orange' }}-600 rounded-full animate-bounce"
                     style="animation-delay: {{ $i * 150 }}ms"></div>
            @endfor
        </div>
        @if($label)
            <span class="sr-only">{{ $label }}</span>
        @endif

    @elseif($tipo === 'pulse')
        <div class="w-{{ $tamano ?? 8 }} h-{{ $tamano ?? 8 }} bg-{{ $color ?? 'orange' }}-600 rounded-full animate-pulse" aria-hidden="true"></div>
        @if($label)
            <span class="sr-only">{{ $label }}</span>
        @endif

    @elseif($tipo === 'bars')
        <div class="flex gap-1 items-end h-{{ $tamano ?? 8 }}" aria-hidden="true">
            @for($i = 1; $i <= 4; $i++)
                <div class="w-1 bg-{{ $color ?? 'orange' }}-600 rounded animate-pulse"
                     style="animation-delay: {{ $i * 100 }}ms; height: {{ rand(30, 100) }}%"></div>
            @endfor
        </div>
        @if($label)
            <span class="sr-only">{{ $label }}</span>
        @endif

    @elseif($tipo === 'ring')
        <div class="relative w-{{ $tamano ?? 8 }} h-{{ $tamano ?? 8 }}" aria-hidden="true">
            <svg class="w-full h-full text-{{ $color ?? 'orange' }}-200" viewBox="0 0 40 40">
                <circle cx="20" cy="20" r="18" fill="none" stroke-width="3"></circle>
            </svg>
            <svg class="absolute top-0 left-0 w-full h-full text-{{ $color ?? 'orange' }}-600 animate-spin" viewBox="0 0 40 40">
                <circle cx="20" cy="20" r="18" fill="none" stroke-width="3" stroke-dasharray="90 150" stroke-linecap="round"></circle>
            </svg>
        </div>
        @if($label)
            <span class="sr-only">{{ $label }}</span>
        @endif
    @endif

    @if($label && $mostrarLabel)
        <span class="ml-3 text-sm text-gray-600">{{ $label }}</span>
    @endif
</div>

@if($overlay)
<div class="fixed inset-0 bg-white/80 backdrop-blur-sm z-50 flex items-center justify-center" 
     x-show="{{ $wireLoading ?? false }}"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
    <div class="bg-white p-6 rounded-xl shadow-lg">
        <x-genericos.spinner tipo="{{ $tipo }}" tamano="{{ $tamano }}" color="{{ $color }}" label="{{ $label }}" mostrar-label="true" />
    </div>
</div>
@endif