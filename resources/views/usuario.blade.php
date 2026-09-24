@extends('layouts.app')
@section('title', 'Usuario')
@section('content')
<center><h1 style="color:#7d420f;">Bienvenido</h1></center>
<h2 style="color:#7d420f;">Informacion Del Usuario</h2>
<table border="1">
<tr><td style="font-size:200%;"><b>Usuario</b></td><td style="font-size:150%;">{{ auth()->user()->name }}</td></tr>
<tr><td style="font-size:200%;"><b>Correo</b></td><td style="font-size:150%;">{{ auth()->user()->email }}</td></tr>
</table>
@endsection
