@extends('admin.layout')
@section('header-title','Mesas y servicio')
@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
@foreach($mesas as $m)
@php $activas=$cuentas->where('mesa_id',$m->id); @endphp
<section class="bg-white rounded-xl p-5 border"><h3>{{ $m->nombre }} · {{ $m->capacidad }} lugares</h3>
<p>{{ $activas->isEmpty() ? 'Libre' : ($activas->every(fn($c)=>$c->estado==='servida') ? 'Pendiente de cobro' : 'Ocupada') }}</p>
@if($activas->isEmpty())<a href="{{ route('admin.comandas.create',['mesa_id'=>$m->id]) }}">Abrir cuenta</a>@endif
@foreach($activas as $c)<p><a href="{{ route('admin.comandas.show',$c) }}">Cuenta #{{ $c->id }} · {{ $c->comensales }} comensales · {{ $c->mesero?->name }}</a></p>
@if(auth()->user()->isPrivileged() || auth()->user()->hasRole('capitan'))
<form action="{{ route('admin.mesas.asignar',$c) }}" method="POST">@csrf @method('PUT')<select name="mesero_id" class="border rounded p-2">@foreach($meseros as $u)<option value="{{ $u->id }}" @selected($c->mesero_id===$u->id)>{{ $u->name }}</option>@endforeach</select><button class="btn-secondary p-2">Asignar mesero</button></form>
@endif @endforeach</section>
@endforeach</div>
@if($mesas->isEmpty())<p>No hay mesas registradas.</p>@endif
@if(auth()->user()->isPrivileged() || auth()->user()->hasRole('capitan'))
<form action="{{ route('admin.mesas.store') }}" method="POST" class="mt-6 bg-white p-4 rounded-xl flex flex-wrap gap-3">@csrf
<label>Nombre <input name="nombre" required maxlength="100" class="border rounded p-2"></label><label>Capacidad <input name="capacidad" type="number" value="4" min="1" max="100" required class="border rounded p-2"></label><button class="btn-primary p-2">Crear mesa</button></form>
@endif
@endsection
