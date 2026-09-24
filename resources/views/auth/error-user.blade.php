@extends('layouts.app')
@section('title', 'Error')
@section('content')
<div class="container text-center" style="margin-top:80px;">
<h1>Usuario no encontrado</h1>
<a href="{{ route('login') }}"><button>Regresar</button></a>
</div>
@endsection
