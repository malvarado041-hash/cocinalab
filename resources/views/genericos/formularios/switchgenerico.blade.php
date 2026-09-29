<div {{ $attributes->merge(['class' => 'flex items-center']) }}>
    <div class="relative inline-flex items-center h-6 rounded-full transition-colors
                {{ $checked ? 'bg-orange-600' : 'bg-gray-200' }}
                {{ $disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }}"
         {{ $attributes->whereStartsWith('wire:click') }}
         role="switch"
         aria-checked="{{ $checked ? 'true' : 'false' }}"
         aria-disabled="{{ $disabled ? 'true' : 'false' }}"
         tabindex="{{ $disabled ? '-1' : '0' }}"
         @keydown.space.prevent
         @keydown.enter.prevent>
        
        <input type="checkbox" 
               id="{{ $name }}" 
               name="{{ $name }}" 
               value="{{ $value ?? 1 }}"
               class="sr-only"
               {{ $checked ? 'checked' : '' }}
               {{ $disabled ? 'disabled' : '' }}
               {{ $required ? 'required' : '' }}
               {{ $attributes->whereStartsWith('wire:model') }}>

        <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow-ring-0:0-0#0000001a,0_0_0_1px#0000000d,0_1px_3px_0#0000001a transition-transform duration-200 ease-in-out
                    {{ $checked ? 'translate-x-5' : 'translate-x-0' }}"
              aria-hidden="true"></span>
    </div>

    <div class="ml-3 text-sm">
        @if(isset($label))
            <label for="{{ $name }}" class="font-medium text-gray-900 cursor-pointer">{{ $label }}</label>
        @endif
        
        @if(isset($description))
            <p class="text-gray-500 mt-0.5">{{ $description }}</p>
        @endif
    </div>

    @if(isset($onLabel) || isset($offLabel))
        <div class="ml-3 flex items-center space-x-1 text-xs">
            <span class="text-gray-500 {{ !$checked ? 'font-medium' : '' }}">{{ $offLabel ?? 'No' }}</span>
            <span class="text-gray-300">|</span>
            <span class="text-gray-500 {{ $checked ? 'font-medium' : '' }}">{{ $onLabel ?? 'Sí' }}</span>
        </div>
    @endif

    @if($error)
        <p class="ml-3 mt-1.5 text-sm text-red-600" role="alert">{{ $error }}</p>
    @endif
</div>