@extends('admin.layout')

@section('title', 'Editar cuenta')

@section('header-title', 'Editar: ' . $comanda->mesa)

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 fade-in max-w-4xl">
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg" role="alert">
            <p class="font-semibold mb-1">Revisa los campos:</p>
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('admin.comandas.update', $comanda) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mesa / Cuenta *</label>
                <input type="text" name="mesa" value="{{ old('mesa', $comanda->mesa) }}" required maxlength="100"
                    class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo *</label>
                <select name="tipo" required
                    class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    @foreach ($tipos as $key => $label)
                        <option value="{{ $key }}" @selected(old('tipo', $comanda->tipo) === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
                <input type="text" name="notas" value="{{ old('notas', $comanda->notas) }}" maxlength="500"
                    class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>
        </div>
        <div class="mt-6 flex items-center gap-3">
            <button type="submit" class="btn-primary text-sm px-5 py-2.5">
                <i class="fas fa-save mr-2"></i> Guardar cambios
            </button>
            <a href="{{ route('admin.comandas.show', $comanda) }}" class="btn-secondary text-sm px-5 py-2.5">Cancelar</a>
        </div>
    </form>
</div>
@endsection
