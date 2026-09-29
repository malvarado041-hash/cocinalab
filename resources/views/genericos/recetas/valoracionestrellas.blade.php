<div class="flex items-center gap-2 {{ $attributes->class ?? '' }}" {{ $attributes->except('class') }} role="{{ $interactivo ? 'slider' : 'img' }}" aria-label="{{ $ariaLabel ?? 'Valoración: ' . $valor . ' de ' . $maximo }} estrellas">
    @if($mostrarValor)
        <span class="text-sm font-medium text-gray-900 min-w-[2.5rem] text-right">{{ number_format($valor, 1) }}</span>
    @endif

    <div class="flex items-center" {{ $interactivo ? 'x-data="{ valor: ' . $valor . ' }"' : '' }}>
        @for($i = 1; $i <= $maximo; $i++)
            @php
                $llena = $i <= floor($valor);
                $media = !$llena && $i - 0.5 <= $valor;
            @endphp

            @if($interactivo)
                <button type="button"
                        @click="valor = {{ $i }}; $wire.set('{{ $wireModel }}', {{ $i }})"
                        @mouseenter="valor = {{ $i }}"
                        @mouseleave="valor = {{ $valor }}"
                        class="p-1 text-{{ $tamano ?? '2xl' }} transition-colors focus:outline-none focus:ring-2 focus:ring-orange-500 rounded"
                        aria-label="{{ $i }} estrella{{ $i > 1 ? 's' : '' }}"
                        :class="{ 'text-yellow-400': {{ $i }} <= valor, 'text-gray-300': {{ $i }} > valor }"
                        {{ $disabled ? 'disabled' : '' }}>
                    <svg class="w-full h-full fill-current" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </button>
            @else
                <svg class="w-{{ $tamano ?? '6' }} h-{{ $tamano ?? '6' }} {{ $llena || $media ? 'text-yellow-400 fill-current' : 'text-gray-300' }}" 
                     viewBox="0 0 20 20" aria-hidden="true">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
            @endif
        @endfor
    </div>

    @if($mostrarTotal && $totalResenas)
        <span class="text-sm text-gray-500">({{ $totalResenas }} reseña{{ $totalResenas != 1 ? 's' : '' }})</span>
    @endif

    @if($interactivo && $mostrarInput)
        <input type="hidden" name="{{ $wireModel }}" value="{{ $valor }}" {{ $attributes->whereStartsWith('wire:model') }}>
    @endif
</div>

@if($interactivo && $mostrarLeyenda)
    <p class="mt-1 text-xs text-gray-500" x-show="valor" x-text="['Muy mala', 'Mala', 'Regular', 'Buena', 'Excelente'][valor - 1]"></p>
@endif