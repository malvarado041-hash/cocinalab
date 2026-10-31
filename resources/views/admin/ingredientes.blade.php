@extends('admin.layout')
@section('header-title', 'Vincular ingredientes con almacén')
@section('content')
<p class="mb-4">Vincular no descuenta stock. Después define cantidades y unidades en cada receta.</p>
<div class="space-y-3">
@foreach($ingredientes as $ing)
<form method="POST" action="{{ route('admin.ingredientes.update', $ing) }}" class="bg-white p-4 rounded-xl flex flex-wrap gap-3 items-center">
@csrf @method('PUT') <label for="ing-{{ $ing->id }}">{{ $ing->Nombre }}</label>
<select id="ing-{{ $ing->id }}" name="almacen_producto_id" class="border rounded p-2"><option value="">Sin vincular</option>
@foreach($productos as $p)<option value="{{ $p->id }}" @selected($ing->almacen_producto_id == $p->id)>{{ $p->nombre }} ({{ $p->unidad }})</option>@endforeach
</select><button class="btn-primary px-4 py-2">Guardar vínculo</button></form>
@endforeach
</div>
@endsection
