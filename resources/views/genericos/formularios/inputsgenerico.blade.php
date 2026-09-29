@php
    $type = $type ?? 'text';
    $name = $name ?? '';
    $id = $id ?? ($name !== '' ? $name : Str::random(8));
    $label = $label ?? ($name !== '' ? ucfirst(str_replace(['_', '-'], ' ', $name)) : '');
    $placeholder = $placeholder ?? $label;
    $value = $value ?? '';
    $required = $required ?? false;
    $disabled = $disabled ?? false;
    $class = $class ?? '';
    $icon = $icon ?? '';
@endphp
<div class="gen-field {{ $icon !== '' ? 'gen-field-icon' : '' }} {{ $type === 'password' ? 'gen-field-password' : '' }} {{ $class }}">
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $id }}" value="{{ $value }}"
           placeholder=" " class="gen-input"
           @if($required) required @endif
           @if($disabled) disabled @endif>
    @if($icon !== '')
    <img src="{{ $icon }}" class="gen-input-icon" alt="">
    @endif
    @if($placeholder !== '')
    <label for="{{ $id }}">{{ $placeholder }}</label>
    @endif
    @if($type === 'password')
    <button type="button" class="gen-password-toggle" data-password-toggle data-target="#{{ $id }}" aria-label="Mostrar contraseña" title="Mostrar contraseña" tabindex="-1">
        <i class="fas fa-eye"></i>
    </button>
    @endif
</div>