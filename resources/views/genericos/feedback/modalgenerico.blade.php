@php
    $id = $id ?? 'modalGenerico';
    $title = $title ?? 'Modal';
    $body = $body ?? '';
    $footer = $footer ?? '';
    $open = $open ?? false;
    $size = $size ?? 'md';
@endphp
<div class="modal fade" id="{{ $id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-{{ $size }}" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">{{ $title }}</h4>
            </div>
            <div class="modal-body">
                {!! $body !!}
            </div>
            @if($footer !== '')
            <div class="modal-footer">
                {!! $footer !!}
            </div>
            @endif
        </div>
    </div>
</div>
@if($open)
<script>
    $(document).ready(function () {
        $('#{{ $id }}').modal('show');
    });
</script>
@endif