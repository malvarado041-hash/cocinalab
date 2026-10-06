@extends('admin.layout')

@section('title', 'Nuevo producto')

@section('header-title', 'Registrar producto')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 fade-in max-w-4xl">
    <form action="{{ route('admin.almacen.store', request()->query()) }}" method="POST">
        @csrf
        @include('admin.almacen._form')
        <div class="mt-6 flex items-center gap-3">
            <button type="submit" class="btn-primary text-sm px-5 py-2.5">
                <i class="fas fa-save mr-2"></i> Guardar
            </button>
            <a href="{{ route('admin.almacen.index', request()->query()) }}" class="btn-secondary text-sm px-5 py-2.5">Cancelar</a>
        </div>
    </form>
</div>
@endsection
