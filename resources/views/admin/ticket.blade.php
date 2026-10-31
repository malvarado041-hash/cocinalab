@extends('admin.layout')
@section('header-title','Ticket de cuenta')
@section('content')
<article class="bg-white rounded-xl p-6 max-w-xl"><h2>CocinaLab</h2><p>Cuenta #{{ $comanda->id }} · {{ $comanda->mesa }} · {{ $comanda->cerrada_at?->format('d/m/Y H:i') }}</p>
@foreach($comanda->items->where('estado','!=','cancelado') as $i)<p>{{ $i->cantidad }} × {{ $i->receta?->Nombre }} · ${{ number_format($i->subtotal,2) }}</p>@endforeach
<strong>Total ${{ number_format($comanda->total,2) }}</strong>
@foreach($cobros as $p)<p>{{ ucfirst($p->metodo) }}: ${{ number_format($p->importe,2) }} · Recibido ${{ number_format($p->recibido,2) }} · Cambio ${{ number_format($p->cambio,2) }}</p>@endforeach
<button onclick="window.print()" class="btn-primary p-2 mt-4 print:hidden">Imprimir ticket</button></article>
@endsection
