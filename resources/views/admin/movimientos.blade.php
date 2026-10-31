@extends('admin.layout')
@section('title', 'Movimientos, compras y lotes')
@section('header-title','Movimientos, compras y lotes')
@section('content')
<form action="{{ route('admin.movimientos.store') }}" method="POST" class="bg-white rounded-xl p-5 grid grid-cols-1 md:grid-cols-2 gap-4">@csrf
<label>Producto <select name="almacen_producto_id" required class="border rounded p-2 w-full">@foreach($productos as $p)<option value="{{ $p->id }}">{{ $p->nombre }} · {{ $p->stock }} {{ $p->unidad }}</option>@endforeach</select></label>
<label>Movimiento <select name="tipo" class="border rounded p-2 w-full"><option value="entrada">Entrada / compra</option><option value="merma">Merma</option>@if(auth()->user()->isPrivileged())<option value="ajuste">Ajuste</option>@endif</select></label>
<label>Cantidad (unidad del producto) <input name="cantidad" type="number" step="0.000001" min="0.000001" required class="border rounded p-2 w-full"></label>
<label>Sentido del ajuste <select name="sentido" class="border rounded p-2"><option value="sumar">Sumar</option><option value="restar">Restar</option></select></label>
<label>Motivo <input name="motivo" required minlength="5" maxlength="255" class="border rounded p-2 w-full"></label>
<label>Proveedor <select name="proveedor_id" class="border rounded p-2 w-full"><option value="">Sin proveedor</option>@foreach($proveedores as $p)<option value="{{ $p->id }}">{{ $p->nombre }}</option>@endforeach</select></label>
<label>Precio unitario de compra ($) <input name="precio_unitario" type="number" step="0.01" min="0" class="border rounded p-2 w-full"></label>
<label>Código de lote <input name="lote" maxlength="100" class="border rounded p-2 w-full"></label>
<label>Caducidad del lote <input name="caducidad" type="date" class="border rounded p-2"></label>
<p>Las compras actualizan el precio de referencia y conservan el anterior. Las salidas usan primero los lotes de próxima caducidad.</p>
<button class="btn-primary p-3">Registrar movimiento</button></form>
<section class="bg-white rounded-xl p-5 mt-5"><h3>Sugerencias de compra</h3>@forelse($comprar as $p)<p>{{ $p->nombre }}: comprar {{ round($p->stock_minimo-$p->stock,6) }} {{ $p->unidad }} · ${{ number_format(($p->stock_minimo-$p->stock)*$p->precio_unitario,2) }}</p>@empty<p>No hay productos bajo mínimo.</p>@endforelse</section>
<section class="bg-white rounded-xl p-5 mt-5 overflow-x-auto"><h3>Lotes disponibles</h3><table class="w-full"><thead><tr><th>Producto</th><th>Lote</th><th>Disponible</th><th>Caducidad</th></tr></thead><tbody>@foreach($lotes as $l)<tr><td>{{ $l->producto?->nombre }}</td><td>{{ $l->codigo }}</td><td>{{ $l->disponible }}</td><td>{{ $l->caducidad?->format('d/m/Y') ?? 'Sin fecha' }} @if($l->caducidad && $l->caducidad->toDateString() < \App\Models\Ajuste::hoy()) · VENCIDO @elseif($l->caducidad?->lte(today()->addDays(30))) · Por vencer @endif</td></tr>@endforeach</tbody></table></section>
<section class="bg-white rounded-xl p-5 mt-5 overflow-x-auto"><h3>Historial</h3><form method="GET"><label>Producto <select name="producto_id" class="border p-2"><option value="">Todos</option>@foreach($productos as $p)<option value="{{ $p->id }}" @selected(request('producto_id')==$p->id)>{{ $p->nombre }}</option>@endforeach</select></label><button class="btn-secondary p-2">Filtrar</button></form>
<table class="w-full"><thead><tr><th>Fecha</th><th>Producto</th><th>Tipo / motivo</th><th>Cantidad</th><th>Stock</th><th>Precio / anterior</th><th>Proveedor</th><th>Usuario</th></tr></thead><tbody>@foreach($movimientos as $m)<tr><td>{{ \App\Models\Ajuste::fecha($m->created_at) }}</td><td>{{ $productos->firstWhere('id',$m->almacen_producto_id)?->nombre ?? '#'.$m->almacen_producto_id }}</td><td>{{ $m->tipo }} · {{ $m->motivo }}</td><td>{{ $m->cantidad }}</td><td>{{ $m->stock_resultante }}</td><td>${{ $m->costo_unitario }} / {{ $m->precio_anterior ?? '—' }}</td><td>{{ $proveedores->firstWhere('id',$m->proveedor_id)?->nombre ?? '—' }}</td><td>#{{ $m->user_id }}</td></tr>@endforeach</tbody></table>{{ $movimientos->links() }}</section>
@endsection
