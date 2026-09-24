@extends('layouts.app')
@section('title', 'Recetas')
@section('content')
<style>
.menu-centrado{margin-top:60px;}
.espaciado{margin:0 15px;display:inline-block;}
.dropbtn{background-color:#a66328;color:white;padding:16px;font-size:16px;border:none;}
.dropdown{position:relative;display:inline-block;}
.dropdown-content{display:none;position:absolute;background-color:#a66328;min-width:160px;box-shadow:0px 8px 16px 0px rgba(0,0,0,0.2);z-index:1;}
.dropdown-content a{color:black;padding:12px 16px;text-decoration:none;display:block;}
.dropdown-content a:hover{background-color:#7d420f;}
.dropdown:hover .dropdown-content{display:block;}
.dropdown:hover .dropbtn{background-color:#7d420f;}
</style>
<center class="menu-centrado">
@forelse($ingredientesPorTipo as $tipo => $ingredientes)
<div class="dropdown espaciado">
<button class="dropbtn">{{ $tipo }}</button>
<div class="dropdown-content">
@foreach($ingredientes as $ingrediente)
<a href="{{ route('recetas.filtrar', ['tipo' => $tipo, 'ingrediente' => $ingrediente]) }}">{{ $ingrediente }}</a>
@endforeach
</div>
</div>
@empty
<p>No hay ingredientes para mostrar.</p>
@endforelse
</center>
@endsection
