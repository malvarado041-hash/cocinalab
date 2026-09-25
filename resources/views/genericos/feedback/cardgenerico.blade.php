@php
    $title = $title ?? '';
    $subtitle = $subtitle ?? '';
    $body = $body ?? '';
    $image = $image ?? '';
    $footer = $footer ?? '';
    $class = $class ?? '';
@endphp
<div class="gen-card {{ $class }}">
    @if($image !== '')
    <img src="{{ $image }}" class="gen-card-image" alt="">
    @endif
    @if($title !== '' || $subtitle !== '')
    <div class="gen-card-header">
        @if($title !== '')
        <h4 class="gen-card-title">{{ $title }}</h4>
        @endif
        @if($subtitle !== '')
        <p class="gen-card-subtitle">{{ $subtitle }}</p>
        @endif
    </div>
    @endif
    <div class="gen-card-body">
        {!! $body !!}
    </div>
    @if($footer !== '')
    <div class="gen-card-footer">
        {!! $footer !!}
    </div>
    @endif
</div>