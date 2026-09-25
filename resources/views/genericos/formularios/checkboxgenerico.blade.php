<div {{ $attributes->merge(['class' => 'flex items-start']) }}>
    <div class="flex items-center h-5">
        <input type="checkbox" 
               id="{{ $name }}" 
               name="{{ $name }}" 
               value="{{ $value ?? 1 }}"
               class="h-4 w-4 text-orange-600 border-gray-300 rounded focus:ring-2 focus:ring-orange-500 focus:ring-offset-2
                      disabled:opacity-50 disabled:cursor-not-allowed
                      {{ $attributes->class ?? '' }}"
               {{ $attributes->except('class') }}
               {{ $checked ? 'checked' : '' }}
               {{ $disabled ? 'disabled' : '' }}
               {{ $required ? 'required' : '' }}>
    </div>

    <div class="ml-3 text-sm">
        @if(isset($label))
            <label for="{{ $name }}" class="font-medium text-gray-900 cursor-pointer">{{ $label }}</label>
        @endif
        
        @if(isset($description))
            <p class="text-gray-500 mt-0.5">{{ $description }}</p>
        @endif
    </div>

    @if($error)
        <p class="ml-3 mt-1.5 text-sm text-red-600" role="alert">{{ $error }}</p>
    @endif
</div>