<fieldset {{ $attributes->merge(['class' => '']) }}>
    @if(isset($label))
        <legend class="block text-sm font-medium text-gray-700 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500 ml-1" aria-hidden="true">*</span>
            @endif
        </legend>
    @endif

    <div class="space-y-2 {{ $inline ? 'flex flex-wrap gap-6' : '' }}" role="radiogroup" aria-label="{{ $label ?? $name }}">
        @foreach($options ?? [] as $key => $option)
            <div class="flex items-center {{ $inline ? '' : '' }}">
                <input type="radio" 
                       id="{{ $name }}_{{ $key }}" 
                       name="{{ $name }}" 
                       value="{{ $key }}"
                       class="h-4 w-4 text-orange-600 border-gray-300 focus:ring-2 focus:ring-orange-500 focus:ring-offset-2
                              disabled:opacity-50 disabled:cursor-not-allowed"
                       {{ $attributes->whereStartsWith('wire:') }}
                       {{ $value == $key ? 'checked' : '' }}
                       {{ $disabled ? 'disabled' : '' }}
                       {{ $required && $loop->first ? 'required' : '' }}>

                <label for="{{ $name }}_{{ $key }}" class="ml-2 text-sm font-medium text-gray-900 cursor-pointer">
                    {{ is_array($option) ? $option['label'] : $option }}
                </label>

                @if(is_array($option) && isset($option['description']))
                    <span class="ml-2 text-sm text-gray-500">{{ $option['description'] }}</span>
                @endif
            </div>
        @endforeach
    </div>

    @if($hint)
        <p class="mt-2 text-sm text-gray-500">{{ $hint }}</p>
    @endif

    @if($error)
        <p class="mt-2 text-sm text-red-600" role="alert">{{ $error }}</p>
    @endif
</fieldset>