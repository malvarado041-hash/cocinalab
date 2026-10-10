@extends('admin.layout')

@section('title', 'Editar ' . $receta->Nombre)
@section('header-title', 'Editar receta')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 fade-in max-w-4xl">
    <form action="{{ route('admin.recetas.update', $receta) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('admin.recetas._form')
        <div class="mt-6 flex items-center gap-3">
            <a href="{{ route('admin.recetas.show', $receta) }}" class="btn-secondary text-sm px-4 py-2">Cancelar</a>
            <button type="submit" class="btn-primary text-sm px-4 py-2"><i class="fas fa-save mr-2"></i> Actualizar</button>
        </div>
    </form>
</div>
@endsection
