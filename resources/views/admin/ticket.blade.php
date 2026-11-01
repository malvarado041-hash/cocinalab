@extends('admin.layout')
@section('title', 'Ticket de cuenta')
@section('header-title','Ticket de cuenta')
@section('content')
<article class="ticket-print bg-white rounded-xl p-6 max-w-xl"><h2>{{ \App\Models\Ajuste::valor('nombre_restaurante','CocinaLab') }}</h2><p>{{ \App\Models\Ajuste::valor('direccion') }} · {{ \App\Models\Ajuste::valor('telefono') }}</p><p>Cuenta #{{ $comanda->id }} · {{ $comanda->mesa }} · {{ \App\Models\Ajuste::fecha($comanda->cerrada_at) }}</p>
@foreach($comanda->items->where('estado','!=','cancelado') as $i)<p>{{ $i->cantidad }} × {{ $i->receta?->Nombre }} · ${{ number_format($i->subtotal,2) }}</p>@endforeach
<strong>Total ${{ number_format($comanda->total,2) }}</strong>
@foreach($cobros as $p)<p>{{ ucfirst($p->metodo) }}: ${{ number_format($p->importe,2) }} · Recibido ${{ number_format($p->recibido,2) }} · Cambio ${{ number_format($p->cambio,2) }}</p>@endforeach
<p>{{ \App\Models\Ajuste::valor('mensaje_ticket','Gracias por tu visita') }} · {{ \App\Models\Ajuste::valor('moneda','MXN') }}</p><button onclick="window.print()" class="btn-primary p-2 mt-4 print:hidden">Imprimir ticket</button></article>
@endsection
