@extends('layouts.app')
@section('title', 'Filtrar')
@section('content')
<div style="text-align:center;">
<h2>Recetas de tipo '{{ $tipo }}' que contienen '{{ $ingrediente }}':</h2>
@forelse($recetas as $r)
<form style="display:inline-block;margin:10px;" method="GET" action="{{ route('recetas.show') }}">
<input type="hidden" name="id" value="{{ $r->id }}">
<button type="submit" style="padding:10px 20px;background-color:#a66328;color:white;border:none;border-radius:5px;cursor:pointer;">{{ $r->Nombre }}</button>
</form>
@empty
<p>No se encontraron recetas con ese ingrediente en esta categoría.</p>
@endforelse
</div>
@endsection
