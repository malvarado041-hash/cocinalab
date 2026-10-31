@extends('admin.layout')

@section('title', 'Editar ' . $receta->Nombre)
@section('header-title', 'Editar receta')

@section('content')
<div class="recipe-editor fade-in">
    <form action="{{ route('admin.recetas.update', $receta) }}" id="receta-form" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('admin.recetas._form')

    </form>
</div>
@endsection
