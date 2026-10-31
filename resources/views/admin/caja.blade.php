@extends('admin.layout')
@section('header-title','Caja y cobros')
@section('content')
@if(!$caja)
<form action="{{ route('admin.caja.abrir') }}" method="POST" class="bg-white rounded-xl p-5">@csrf<label>Fondo inicial ($) <input type="number" step="0.01" min="0" name="fondo" required class="border rounded p-2"></label><button class="btn-primary p-2">Abrir caja</button></form>
@else
<section class="bg-white rounded-xl p-5"><h3>Caja #{{ $caja->id }} · Fondo ${{ number_format($caja->fondo,2) }}</h3>
<p>Efectivo esperado: ${{ number_format($caja->fondo+\App\Models\Cobro::where('caja_id',$caja->id)->where('metodo','efectivo')->sum('importe'),2) }}</p>
<form action="{{ route('admin.caja.cerrar',$caja) }}" method="POST" class="mt-4">@csrf @method('PUT')<label>Efectivo contado ($) <input name="efectivo_contado" type="number" step="0.01" min="0" required class="border rounded p-2"></label><button class="btn-secondary p-2">Cerrar caja</button></form></section>
@endif
<h3 class="mt-6">Cuentas pendientes de cobro</h3>
@forelse($cuentas as $c)
<section class="bg-white p-5 rounded-xl my-4"><h4>{{ $c->mesa }} · Cuenta #{{ $c->id }} · Total ${{ number_format($c->total,2) }}</h4>
@if($caja)
<form action="{{ route('admin.caja.cobrar',$c) }}" method="POST" class="space-y-3">@csrf
<p>Para pago mixto, divide el total entre ambas filas. Elimina la segunda fila si no la usas.</p>
@for($i=0;$i<2;$i++)<div class="flex flex-wrap gap-2"><label>Método <select name="pagos[{{ $i }}][metodo]" class="border rounded p-2"><option value="efectivo">Efectivo</option><option value="tarjeta" @selected($i===1)>Tarjeta</option></select></label><label>Importe <input name="pagos[{{ $i }}][importe]" type="number" step="0.01" min="0.01" required value="{{ $i===0 ? $c->total : '' }}" class="border rounded p-2"></label><label>Recibido en efectivo <input name="pagos[{{ $i }}][recibido]" type="number" step="0.01" min="0" class="border rounded p-2"></label>@if($i===1)<button type="button" onclick="this.parentElement.remove()" class="btn-secondary p-2">Quitar pago mixto</button>@endif</div>@endfor
<button class="btn-primary p-2">Registrar pago y cerrar cuenta</button></form>
@endif</section>
@empty <p>No hay cuentas servidas pendientes de pago.</p> @endforelse
<h3>Últimos cierres</h3><div class="bg-white rounded-xl p-4">@foreach($historial->whereNotNull('cerrada_at') as $h)<p>Caja #{{ $h->id }} · {{ $h->cerrada_at->format('d/m/Y H:i') }} · Esperado ${{ $h->efectivo_esperado }} · Contado ${{ $h->efectivo_contado }} · Diferencia ${{ $h->diferencia }}</p>@endforeach</div>
@endsection
