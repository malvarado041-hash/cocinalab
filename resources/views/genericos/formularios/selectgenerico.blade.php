<div {{ $attributes->merge(['class' => 'w-full']) }}>
    @if(isset($label))
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1.5">
            {{ $label }}
            @if($required)
                <span class="text-red-500 ml-1" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        <select id="{{ $name }}" name="{{ $name }}" 
                class="block w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg shadow-sm 
                       focus:ring-2 focus:ring-orange-500 focus:border-orange-500 
                       bg-white text-gray-900
                       disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed
                       {{ $error ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : '' }}
                       {{ $attributes->class ?? '' }}"
                {{ $attributes->except('class') }}
                {{ $disabled ? 'disabled' : '' }}
                {{ $required ? 'required' : '' }}
                {{ $multiple ? 'multiple' : '' }}>
            @if($placeholder)
                <option value="" disabled {{ !$value ? 'selected' : '' }}>{{ $placeholder }}</option>
            @endif
            @foreach($options ?? [] as $key => $option)
                @if(is_array($option))
                    <optgroup label="{{ $key }}">
                        @foreach($option as $optKey => $optValue)
                            <option value="{{ $optKey }}" {{ $value == $optKey ? 'selected' : '' }}>{{ $optValue }}</option>
                        @endforeach
                    </optgroup>
                @else
                    <option value="{{ $key }}" {{ $value == $key ? 'selected' : '' }}>{{ $option }}</option>
                @endif
            @endforeach
        </select>

        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
    </div>

    @if($hint)
        <p class="mt-1.5 text-sm text-gray-500">{{ $hint }}</p>
    @endif

    @if($error)
        <p class="mt-1.5 text-sm text-red-600" role="alert">{{ $error }}</p>
    @endif
</div>