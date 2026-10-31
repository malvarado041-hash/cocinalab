@extends('admin.layout')
@section('title', 'Historial de acciones')
@section('header-title','Historial de acciones')
@section('content')
<div class="bg-white p-5 rounded-xl overflow-x-auto"><table class="w-full"><thead><tr><th>Fecha</th><th>Usuario</th><th>Acción</th><th>Cuenta</th><th>Detalle</th></tr></thead><tbody>@foreach($eventos as $e)<tr><td>{{ \App\Models\Ajuste::fecha($e->created_at) }}</td><td>#{{ $e->user_id }}</td><td>{{ $e->accion }}</td><td>{{ $e->comanda_id??'—' }}</td><td>{{ $e->detalle }}</td></tr>@endforeach</tbody></table>{{ $eventos->links() }}</div>
@endsection
