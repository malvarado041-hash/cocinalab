@extends('admin.layout')

@section('title', 'Nueva receta')
@section('header-title', 'Nueva receta')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 fade-in max-w-4xl">
    <form action="{{ route('admin.recetas.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.recetas._form')
        <div class="mt-6 flex items-center gap-3">
            <a href="{{ route('admin.recetas.index') }}" class="btn-secondary text-sm px-4 py-2">Cancelar</a>
            <button type="submit" class="btn-primary text-sm px-4 py-2"><i class="fas fa-save mr-2"></i> Guardar receta</button>
        </div>
    </form>
</div>
@endsection
