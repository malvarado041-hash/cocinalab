@php
    $variantes = [
        'default' => 'bg-gray-100 text-gray-700 hover:bg-gray-200',
        'primary' => 'bg-orange-100 text-orange-700 hover:bg-orange-200',
        'success' => 'bg-green-100 text-green-700 hover:bg-green-200',
        'warning' => 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200',
        'danger' => 'bg-red-100 text-red-700 hover:bg-red-200',
        'info' => 'bg-blue-100 text-blue-700 hover:bg-blue-200',
        'outline' => 'bg-transparent border border-gray-300 text-gray-700 hover:bg-gray-50',
    ];

    $tamanos = [
        'xs' => 'px-1.5 py-0.5 text-[10px]',
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-2.5 py-1 text-sm',
        'lg' => 'px-3 py-1 text-base',
    ];

    $claseBase = 'inline-flex items-center gap-1.5 font-medium rounded-full transition-colors';
    $claseVariante = $variantes[$variante ?? 'default'] ?? $variantes['default'];
    $claseTamano = $tamanos[$tamano ?? 'md'] ?? $tamanos['md'];
@endphp

<span {{ $attributes->merge(['class' => $claseBase . ' ' . $claseVariante . ' ' . $claseTamano]) }}
      role="{{ $role ?? 'status' }}"
      aria-label="{{ $ariaLabel ?? $texto }}">
    
    @if($iconoIzquierda)
        <span class="flex-shrink-0">{{ $iconoIzquierda }}</span>
    @endif

    {{ $texto }}

    @if($iconoDerecha)
        <span class="flex-shrink-0">{{ $iconoDerecha }}</span>
    @endif

    @if($contador !== null)
        <span class="ml-1.5 px-1.5 py-0.5 text-[10px] font-semibold bg-white/50 rounded-full">
            {{ $contador }}
        </span>
    @endif

    @if($punto)
        <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70"
              aria-hidden="true"></span>
    @endif
</span>

@if($removible)
    <button @click="$dispatch('badge:remove', '{{ $id ?? $texto }}')"
            class="ml-1 p-0.5 rounded hover:bg-black/10 transition-colors"
            aria-label="Eliminar {{ $texto }}">
        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
    </button>
@endif