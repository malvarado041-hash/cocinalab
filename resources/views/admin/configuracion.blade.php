@extends('admin.layout')
@section('title', 'Configuración del restaurante')
@section('header-title','Configuración del restaurante')
@section('content')
<form action="{{ route('admin.configuracion.update') }}" method="POST" class="bg-white p-5 rounded-xl space-y-4 max-w-3xl">@csrf @method('PUT')
@foreach(['nombre_restaurante'=>'Nombre del restaurante','direccion'=>'Dirección','telefono'=>'Teléfono','horarios'=>'Horarios de atención','mensaje_ticket'=>'Mensaje del ticket'] as $k=>$label)
<div><label for="{{ $k }}">{{ $label }}</label><input id="{{ $k }}" name="{{ $k }}" value="{{ old($k,$ajustes[$k]??($k==='nombre_restaurante'?'CocinaLab':'')) }}" @required($k==='nombre_restaurante') maxlength="{{ $k==='nombre_restaurante'?100:($k==='telefono'?40:255) }}" class="border rounded p-2 w-full"></div>
@endforeach
<label>Moneda <select name="moneda" class="border rounded p-2">@foreach(['MXN','USD'] as $m)<option @selected(($ajustes['moneda']??'MXN')===$m)>{{ $m }}</option>@endforeach</select></label>
<label>Zona horaria <select name="zona_horaria" class="border rounded p-2">@foreach($zonas as $z)<option @selected(($ajustes['zona_horaria']??'America/Tijuana')===$z)>{{ $z }}</option>@endforeach</select></label>
<button class="btn-primary p-3">Guardar configuración</button></form>
<a href="{{ route('admin.auditoria') }}" class="btn-secondary inline-block p-3 mt-4">Historial de acciones</a>
<section class="bg-white p-5 rounded-xl mt-4"><h3>Respaldo y recuperación</h3><p>Sistemas puede ejecutar el respaldo completo y restaurarlo desde la terminal del servidor. Incluye datos de acceso; se almacena en el directorio privado de la aplicación.</p><p>Respaldo: <code>php artisan db:backup</code></p><p>Restauración: <code>php artisan db:restore archivo.sql --confirm-database=nombre_base</code></p><p>La restauración reemplaza los datos. El procedimiento de recuperación está en la documentación del proyecto.</p></section>
@endsection
