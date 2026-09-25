@php
    $type = $type ?? 'button';
    $label = $label ?? 'Enviar';
    $style = $style ?? 'primary';
    $url = $url ?? '#';
    $class = $class ?? '';
    $onclick = $onclick ?? '';
    $id = $id ?? '';
    $disabled = $disabled ?? false;
@endphp
@if($type === 'link')
<a href="{{ $url }}" id="{{ $id }}" class="gen-btn gen-btn-{{ $style }} {{ $class }}"
   @if($onclick !== '') onclick="{{ $onclick }}" @endif>{{ $label }}</a>
@else
<button type="{{ $type === 'submit' ? 'submit' : 'button' }}" id="{{ $id }}"
        class="gen-btn gen-btn-{{ $style }} {{ $class }}"
        @if($onclick !== '') onclick="{{ $onclick }}" @endif
        @if($disabled) disabled @endif>{{ $label }}</button>
@endif