@extends('admin.layout')

@section('title', 'Nueva cuenta')

@section('header-title', 'Abrir cuenta')

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

    <form action="{{ route('admin.comandas.store') }}" method="POST" id="form-comanda">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mesa / Cuenta *</label>
                <input type="text" name="mesa" value="{{ old('mesa') }}" required maxlength="100"
                    placeholder="Ej. Mesa 5, Barra 2, Para llevar"
                    class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo *</label>
                <select name="tipo" required
                    class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    @foreach ($tipos as $key => $label)
                        <option value="{{ $key }}" @selected(old('tipo', 'mesa') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
                <input type="text" name="notas" value="{{ old('notas') }}" maxlength="500"
                    placeholder="Ej. Sin cebolla en general"
                    class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>
        </div>

        <h3 class="text-sm font-semibold text-gray-700 mt-6 mb-2">Platillos *</h3>
        <div id="items" class="space-y-3"></div>
        <button type="button" id="btn-add-item" class="btn-secondary text-sm px-4 py-2 mt-3">
            <i class="fas fa-plus mr-2"></i> Agregar platillo
        </button>

        <div class="mt-4 text-right">
            <p class="text-sm text-gray-500">Total estimado: <span id="total-est" class="text-lg font-semibold text-gray-800">$0.00</span></p>
        </div>

        <div class="mt-6 flex items-center gap-3">
            <button type="submit" class="btn-primary text-sm px-5 py-2.5">
                <i class="fas fa-save mr-2"></i> Abrir cuenta
            </button>
            <a href="{{ route('admin.comandas.index') }}" class="btn-secondary text-sm px-5 py-2.5">Cancelar</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const PLATILLOS = @json($platillos);
    const container = document.getElementById('items');
    const totalEl = document.getElementById('total-est');
    let idx = 0;

    function opciones(selected) {
        let html = '<option value="">Selecciona…</option>';
        PLATILLOS.forEach(function(p) {
            const precio = parseFloat(p.precio_platillo);
            html += '<option value="' + p.id + '" data-precio="' + precio + '"' +
                (String(selected) === String(p.id) ? ' selected' : '') + '>' +
                p.Nombre + ' — $' + precio.toFixed(2) + '</option>';
        });
        return html;
    }

    function recalcular() {
        let total = 0;
        container.querySelectorAll('.item-row').forEach(function(row) {
            const sel = row.querySelector('select');
            const cant = parseInt(row.querySelector('input[name$="[cantidad]"]').value, 10) || 0;
            const precio = parseFloat(sel.selectedOptions[0]?.dataset.precio || 0);
            total += cant * precio;
        });
        totalEl.textContent = '$' + total.toFixed(2);
    }

    function agregarItem(selected, cantidad) {
        const row = document.createElement('div');
        row.className = 'item-row grid grid-cols-12 gap-2 items-center';
        row.innerHTML =
            '<select name="items[' + idx + '][receta_id]" required class="col-span-6 px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">' + opciones(selected) + '</select>' +
            '<input type="number" name="items[' + idx + '][cantidad]" value="' + (cantidad || 1) + '" required min="1" max="99" class="col-span-2 px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">' +
            '<input type="text" name="items[' + idx + '][notas]" placeholder="Notas" maxlength="255" class="col-span-3 px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">' +
            '<button type="button" class="col-span-1 text-red-500 hover:text-red-700 btn-quitar" title="Quitar"><i class="fas fa-trash"></i></button>';
        idx++;
        container.appendChild(row);
        row.querySelector('select').addEventListener('change', recalcular);
        row.querySelector('input[name$="[cantidad]"]').addEventListener('input', recalcular);
        row.querySelector('.btn-quitar').addEventListener('click', function() { row.remove(); recalcular(); });
        recalcular();
    }

    document.getElementById('btn-add-item').addEventListener('click', function() { agregarItem(); });

    @if(old('items'))
        @foreach(old('items') as $oi)
            agregarItem(@json($oi['receta_id'] ?? ''), @json($oi['cantidad'] ?? 1));
        @endforeach
    @else
        agregarItem();
    @endif
});
</script>
@endpush
