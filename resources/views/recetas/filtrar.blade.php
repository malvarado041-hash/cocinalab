@extends('layouts.app')
@section('title', 'Filtrar')
@section('content')
<div class="recetas-resultado">
    <h2>Recetas de tipo '{{ $tipo }}' que contienen '{{ $ingrediente }}':</h2>
    @if ($recetas->isNotEmpty())
        <ul class="recetas-chips">
            @foreach ($recetas as $r)
                <li>
                    <a class="receta-chip" href="{{ route('recetas.show', ['id' => $r->id]) }}">{{ $r->Nombre }}</a>
                </li>
            @endforeach
        </ul>
    @else
        <p class="empty-state">No se encontraron recetas con ese ingrediente en esta categoría.</p>
    @endif
</div>
@endsection
