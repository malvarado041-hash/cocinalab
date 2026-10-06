@php
    $isEdit = isset($producto);
    $old = fn($k, $d = '') => old($k, $isEdit ? $producto->$k : $d);
@endphp

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

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
        <input type="text" name="nombre" value="{{ $old('nombre') }}" required maxlength="200"
            placeholder="Ej. Tomate rojo"
            class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Proveedor</label>
        <input type="text" name="proveedor" value="{{ $old('proveedor') }}" maxlength="200"
            placeholder="Ej. Central de abastos"
            class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Categoría *</label>
        <select id="categoria" name="categoria" required
            class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
            <option value="">Selecciona…</option>
            @foreach ($categorias as $key => $label)
                <option value="{{ $key }}" @selected($old('categoria') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Subcategoría *</label>
        <select id="subcategoria" name="subcategoria" required
            class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
            <option value="">Primero elige categoría…</option>
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Unidad de conteo *</label>
        <select id="unidad" name="unidad" required
            class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
            <option value="">Primero elige subcategoría…</option>
        </select>
        <p class="text-xs text-gray-400 mt-1">La subcategoría define cómo se cuenta: verdura/fruta/proteína por kg-g-pieza, especias por g, lácteos por L-ml, insumos por pieza-paquete.</p>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Stock actual *</label>
            <input type="number" name="stock" value="{{ $old('stock', '0') }}" required min="0" step="0.01"
                class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Stock mínimo *</label>
            <input type="number" name="stock_minimo" value="{{ $old('stock_minimo', '0') }}" required min="0" step="0.01"
                class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Precio unitario ($)</label>
        <input type="number" name="precio_unitario" value="{{ $old('precio_unitario') }}" min="0" step="0.01"
            class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
    </div>
    <div class="flex items-center gap-3">
        <input type="checkbox" id="perecedero" name="perecedero" value="1" @checked((bool) $old('perecedero', in_array($old('categoria'), ['ingrediente', 'pan_tortilla'])))
            class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 w-5 h-5">
        <label for="perecedero" class="text-sm font-medium text-gray-700">Perecedero (pide caducidad)</label>
    </div>
    <div id="caducidad-wrap">
        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de caducidad</label>
        <input type="date" name="fecha_caducidad" value="{{ $old('fecha_caducidad', $isEdit && $producto->fecha_caducidad ? $producto->fecha_caducidad->format('Y-m-d') : '') }}"
            min="{{ date('Y-m-d') }}"
            class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const MAPA = @json($mapa);
    const UNIDADES = @json($unidades ?? []);
    const cat = document.getElementById('categoria');
    const sub = document.getElementById('subcategoria');
    const uni = document.getElementById('unidad');
    const per = document.getElementById('perecedero');
    const cadWrap = document.getElementById('caducidad-wrap');
    const initSub = @json($old('subcategoria'));
    const initUni = @json($old('unidad'));

    function fillSubs(keep) {
        sub.innerHTML = '<option value="">Selecciona…</option>';
        Object.keys(MAPA[cat.value] || {}).forEach(function(s) {
            const o = document.createElement('option');
            o.value = s; o.textContent = s.replace(/_/g, ' ');
            if (keep === s) o.selected = true;
            sub.appendChild(o);
        });
    }
    function fillUnis(keep) {
        uni.innerHTML = '<option value="">Selecciona…</option>';
        ((MAPA[cat.value] || {})[sub.value] || []).forEach(function(u) {
            const o = document.createElement('option');
            o.value = u; o.textContent = (UNIDADES[u] && UNIDADES[u][0]) || u;
            if (keep === u) o.selected = true;
            uni.appendChild(o);
        });
    }
    function toggleCad() { if (cadWrap) cadWrap.style.display = per && per.checked ? '' : 'none'; }

    cat.addEventListener('change', function() { fillSubs(); fillUnis(); });
    sub.addEventListener('change', function() { fillUnis(); });
    if (per) per.addEventListener('change', toggleCad);

    fillSubs(initSub); fillUnis(initUni); toggleCad();
});
</script>
@endpush
