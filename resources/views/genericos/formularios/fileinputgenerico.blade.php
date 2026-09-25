<div {{ $attributes->merge(['class' => 'w-full']) }}>
    @if(isset($label))
        <label class="block text-sm font-medium text-gray-700 mb-1.5">
            {{ $label }}
            @if($required)
                <span class="text-red-500 ml-1" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        @if($preview && $value)
            <div class="mb-3 relative" x-data="{ preview: '{{ $value }}' }">
                @if($accept && str_starts_with($accept, 'image'))
                    <img :src="preview" alt="Vista previa" class="h-32 w-auto rounded-lg border border-gray-200 object-cover">
                @else
                    <div class="h-32 w-auto rounded-lg border border-gray-200 flex items-center justify-center bg-gray-50">
                        <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                        </svg>
                        <span class="sr-only">Archivo adjunto</span>
                    </div>
                @endif
                @if($removable)
                    <button type="button" 
                            @click="preview = ''" 
                            wire:click="removeFile('{{ $name }}')"
                            class="absolute top-2 right-2 p-1 rounded-full bg-red-500 text-white hover:bg-red-600 transition-colors"
                            aria-label="Eliminar archivo">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                @endif
            </div>
        @endif

        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-orange-400 hover:bg-orange-50 transition-colors {{ $value && !$preview ? 'border-green-300 bg-green-50' : '' }}">
            <input type="file" 
                   id="{{ $name }}" 
                   name="{{ $name }}" 
                   class="sr-only"
                   {{ $accept ? 'accept="' . $accept . '"' : '' }}
                   {{ $multiple ? 'multiple' : '' }}
                   {{ $required ? 'required' : '' }}
                   {{ $disabled ? 'disabled' : '' }}
                   {{ $attributes->whereStartsWith('wire:') }}
                   @if($maxSize)
                       data-max-size="{{ $maxSize }}"
                   @endif>

            <label for="{{ $name }}" class="cursor-pointer" {{ $disabled ? 'style="pointer-events: none; opacity: 0.5;"' : '' }}>
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                <div class="mt-2">
                    <p class="text-sm font-medium text-gray-900">{{ $buttonText ?? 'Seleccionar archivo' }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $helpText ?? 'Arrastra y suelta o haz clic para seleccionar' }}</p>
                    @if($accept)
                        <p class="text-xs text-gray-400 mt-1">Formatos: {{ is_array($accept) ? implode(', ', $accept) : $accept }}</p>
                    @endif
                    @if($maxSize)
                        <p class="text-xs text-gray-400 mt-1">Máx. {{ $maxSize / 1024 / 1024 }}MB</p>
                    @endif
                </div>
            </label>
        </div>

        @if($value && !$preview)
            <input type="hidden" name="{{ $name }}_existing" value="{{ $value }}">
        @endif
    </div>

    @if($hint)
        <p class="mt-1.5 text-sm text-gray-500">{{ $hint }}</p>
    @endif

    @if($error)
        <p class="mt-1.5 text-sm text-red-600" role="alert">{{ $error }}</p>
    @endif
</div>