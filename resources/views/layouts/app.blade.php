<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'CocinaLab')</title>
    <link rel="stylesheet" href="{{ asset('css/boton.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body style="background-color:#B9B9B9;">
<div class="contenedor">
    <img width="10%" src="{{ asset('img/logo.png') }}" alt="Logo">
    @auth
    <form action="{{ route('recetas.show') }}" method="GET" style="display:inline;">
        <input type="search" class="buscador" name="buscar">
        <input type="submit" value="Buscar" style="width:5%;">
    </form>
    @endauth
    <br>
    <center>
        <ul class="btnlist">
            <li style="width:50%;"><a class="boton" href="{{ auth()->check() ? route('home') : route('login') }}">Inicio</a></li>
            <li><a class="boton" href="{{ auth()->check() ? route('recetas.index') : route('login') }}">Recetas</a></li>
            <li><a class="boton" href="{{ auth()->check() ? route('usuario') : route('login') }}">Usuario</a></li>
            <li><a class="boton" href="{{ auth()->check() ? route('info') : route('login') }}">Informacion</a></li>
            @auth
            <li>
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button class="boton" type="submit">Salir ({{ auth()->user()->name }})</button>
                </form>
            </li>
            @endauth
        </ul>
    </center>
</div>
@yield('content')
</body>
</html>
