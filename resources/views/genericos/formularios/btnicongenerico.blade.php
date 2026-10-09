@php
    $type = $type ?? 'button';
    $label = $label ?? 'Enviar';
    $style = $style ?? 'primary';
    $url = $url ?? '#';
    $icon = $icon ?? '';
    $class = $class ?? '';
    $onclick = $onclick ?? '';
    $id = $id ?? '';
    $disabled = $disabled ?? false;
@endphp
@if($type === 'link')
<a href="{{ $url }}" id="{{ $id }}" class="gen-btn gen-btn-{{ $style }} {{ $class }}"
   @if($onclick !== '') onclick="{{ $onclick }}" @endif>
    @if($icon !== '')
    <img src="{{ $icon }}" class="gen-btn-icon" alt="">
    @endif
    <span>{{ $label }}</span>
</a>
@else
<button type="{{ $type === 'submit' ? 'submit' : 'button' }}" id="{{ $id }}"
        class="gen-btn gen-btn-{{ $style }} {{ $class }}"
        @if($onclick !== '') onclick="{{ $onclick }}" @endif
        @if($disabled) disabled @endif>
    @if($icon !== '')
    <img src="{{ $icon }}" class="gen-btn-icon" alt="">
    @endif
    <span>{{ $label }}</span>
</button>
@endif