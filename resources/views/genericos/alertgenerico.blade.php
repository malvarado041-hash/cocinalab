@php
    $type = $type ?? 'success';
    $title = $title ?? '';
    $message = $message ?? '';
    $dismiss = $dismiss ?? false;
    $class = $class ?? '';
@endphp
<div class="gen-alert gen-alert-{{ $type }} {{ $class }}">
    @if($dismiss)
    <button type="button" class="gen-alert-close" onclick="this.parentNode.style.display='none'">&times;</button>
    @endif
    @if($title !== '')
    <strong>{{ $title }}</strong>
    @endif
    <span>{{ $message }}</span>
</div>