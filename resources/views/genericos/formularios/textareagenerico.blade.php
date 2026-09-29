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
        <textarea id="{{ $name }}" name="{{ $name }}" rows="{{ $rows ?? 4 }}"
                  class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm 
                         focus:ring-2 focus:ring-orange-500 focus:border-orange-500 
                         bg-white text-gray-900 placeholder-gray-400
                         disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed
                         resize-y min-h-[100px]
                         {{ $error ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : '' }}
                         {{ $attributes->class ?? '' }}"
                  {{ $attributes->except('class') }}
                  {{ $disabled ? 'disabled' : '' }}
                  {{ $required ? 'required' : '' }}
                  {{ $maxlength ? 'maxlength="' . $maxlength . '"' : '' }}>{{ old($name, $value ?? '') }}</textarea>

        @if($maxlength)
            <div class="absolute bottom-2 right-2 text-xs text-gray-400" aria-hidden="true">
                <span id="{{ $name }}-count">{{ strlen(old($name, $value ?? '')) }}</span>/{{ $maxlength }}
            </div>
        @endif
    </div>

    @if($hint)
        <p class="mt-1.5 text-sm text-gray-500">{{ $hint }}</p>
    @endif

    @if($error)
        <p class="mt-1.5 text-sm text-red-600" role="alert">{{ $error }}</p>
    @endif
</div>

@push('scripts')
    @if($maxlength)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const textarea = document.getElementById('{{ $name }}');
                const counter = document.getElementById('{{ $name }}-count');
                if (textarea && counter) {
                    textarea.addEventListener('input', function() {
                        counter.textContent = this.value.length;
                    });
                }
            });
        </script>
    @endif
@endpush