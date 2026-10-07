@extends('admin.layout')

@section('title', 'Detalle de Usuario')

@section('header-title', 'Detalle de Usuario')

@section('header-actions')
<a href="{{ route('admin.users.index') }}" class="btn-secondary text-sm px-4 py-2">
    <i class="fas fa-arrow-left mr-2"></i> Volver
</a>
@endsection

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden fade-in">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-4">
        <div class="w-14 h-14 rounded-xl flex items-center justify-center bg-gray-100">
            <i class="fas fa-user text-gray-400 text-2xl"></i>
        </div>
        <div>
            <h2 class="text-xl font-semibold text-gray-800">{{ $user->name }}</h2>
            <p class="text-sm text-gray-500 font-mono">{{ $user->codigo_empleado ?? 'Sin código' }}</p>
        </div>
    </div>

    <div class="p-6 space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Rol</p>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">
                    {{ ucfirst($user->role ?? 'Sin rol') }}
                </span>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Estado</p>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-medium rounded-full {{ $user->status === 'activo' ? 'bg-green-100 text-green-700' : ($user->status === 'pendiente' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                    {{ ucfirst($user->status) }}
                </span>
            </div>
        </div>

        <div class="border-t border-gray-100 pt-4">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Aprobado por</p>
            @if ($user->approver)
                <p class="text-sm text-gray-700">{{ $user->approver->name }}</p>
            @else
                <p class="text-sm text-gray-400">—</p>
            @endif
        </div>

        <div class="border-t border-gray-100 pt-4">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Fecha de registro</p>
            <p class="text-sm text-gray-700">{{ $user->created_at->format('d/m/Y H:i') }}</p>
        </div>

        @if ($user->password_reset_requested_at)
        <div class="border-t border-gray-100 pt-4">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Solicitud de contraseña</p>
            <p class="text-sm text-amber-700">
                <i class="fas fa-key mr-1"></i> Solicitó cambio el {{ $user->password_reset_requested_at->format('d/m/Y H:i') }}
            </p>
        </div>
        @endif
    </div>

    <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3">
        <a href="{{ route('admin.users.edit', $user) }}" class="btn-primary text-sm px-4 py-2">
            <i class="fas fa-edit mr-2"></i> Editar
        </a>
        @if ($user->id !== Auth::id())
            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger text-sm px-4 py-2"
                        onclick="return confirm('¿Estás seguro de dar de baja a {{ $user->name }}?')">
                    <i class="fas fa-trash mr-2"></i> Dar de baja
                </button>
            </form>
        @endif
    </div>
</div>
@endsection
