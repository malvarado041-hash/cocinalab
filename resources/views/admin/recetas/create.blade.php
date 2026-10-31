@extends('admin.layout')

@section('title', 'Nueva receta')
@section('header-title', 'Nueva receta')

@section('content')
<div class="recipe-editor fade-in">
    <form action="{{ route('admin.recetas.store') }}" id="receta-form" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.recetas._form')

    </form>
</div>
@endsection
