@php
    $isEdit = isset($receta);
    $val = fn($k, $d = '') => old($k, $isEdit ? $receta->$k : $d);
    $oldIngs = old('ingredientes', $isEdit ? $receta->ingredientes->map(fn($i) => [
        'ingrediente_id' => $i->id,
        'cantidad' => $i->pivot->cantidad,
        'cantidad_num' => $i->pivot->cantidad_num,
        'unidad' => $i->pivot->unidad,
    ])->all() : [['ingrediente_id' => '', 'cantidad' => '', 'cantidad_num' => '', 'unidad' => '']]);
    $procTexto = old('Procedimiento', $isEdit ? ($receta->Procedimiento ?? '') : '');
    $oldPasos = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) $procTexto)), fn ($p) => $p !== ''));
    if (empty($oldPasos)) { $oldPasos = ['']; }
@endphp

@if ($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg" role="alert">
        <p class="font-semibold mb-1">Revisa los campos:</p>
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del platillo *</label>
        <input type="text" name="Nombre" value="{{ $val('Nombre') }}" required maxlength="200" placeholder="Ej. Chiles rellenos"
            class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo *</label>
        <select name="TipoC" required class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
            <option value="">Selecciona…</option>
            @foreach ($tipos as $t)<option value="{{ $t }}" @selected($val('TipoC') === $t)>{{ $t }}</option>@endforeach
        </select>
    </div>
</div>

<div class="mt-5">
    <div class="flex items-center justify-between mb-2">
        <label class="block text-sm font-medium text-gray-700">Procedimiento (pasos) *</label>
        <button type="button" id="btn-add-paso" class="btn-secondary text-sm px-3 py-1.5"><i class="fas fa-plus mr-1"></i> Agregar paso</button>
    </div>
    <div id="pasos-wrap" class="space-y-2">
        @foreach ($oldPasos as $paso)
            <div class="paso-row flex items-start gap-2">
                <span class="paso-num mt-2 w-7 h-7 rounded-full bg-primary-50 text-primary-700 text-sm font-bold inline-flex items-center justify-center flex-shrink-0"></span>
                <input type="text" class="paso-input w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" value="{{ $paso }}" placeholder="Describe este paso…" maxlength="1000">
                <button type="button" class="paso-up w-9 h-9 rounded-lg bg-gray-100 hover:bg-gray-200 inline-flex items-center justify-center flex-shrink-0" title="Subir"><i class="fas fa-arrow-up text-gray-600 text-xs"></i></button>
                <button type="button" class="paso-down w-9 h-9 rounded-lg bg-gray-100 hover:bg-gray-200 inline-flex items-center justify-center flex-shrink-0" title="Bajar"><i class="fas fa-arrow-down text-gray-600 text-xs"></i></button>
                <button type="button" class="paso-del w-9 h-9 rounded-lg bg-red-50 hover:bg-red-100 inline-flex items-center justify-center flex-shrink-0" title="Quitar paso"><i class="fas fa-times text-red-600"></i></button>
            </div>
        @endforeach
    </div>
    <textarea name="Procedimiento" id="procedimiento-hidden" hidden required>{{ $procTexto }}</textarea>
</div>

<div class="mt-6">
    <div class="flex items-center justify-between mb-2">
        <h3 class="font-semibold text-gray-800">Ingredientes con cantidad</h3>
        <button type="button" id="btn-add-ing" class="btn-secondary text-sm px-3 py-1.5"><i class="fas fa-plus mr-1"></i> Agregar</button>
    </div>
    <p class="text-xs text-gray-500 mb-1">Escribe para buscar el ingrediente. La lista sale únicamente del almacén (con precio y unidad).</p>
    <p class="text-xs text-gray-500 mb-3">¿Falta uno? <a href="{{ route('admin.almacen.create') }}" target="_blank" class="text-primary-600 underline">Créalo en Almacén</a> y aparecerá solo aquí.</p>
    <div id="ings-wrap" class="space-y-3">
        @foreach ($oldIngs as $idx => $fila)
            <div class="ing-row bg-gray-50 p-3 rounded-xl" data-idx="{{ $idx }}">
                <div class="grid grid-cols-1 md:grid-cols-[1fr_120px_130px_auto] gap-2 items-end">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Ingrediente *</label>
                        <select name="ingredientes[{{ $idx }}][ingrediente_id]" class="ing-select w-full" required>
                            <option value="">Escribe para buscar…</option>
                            @foreach ($opciones as $op)
                                <option value="{{ $op['ingrediente_id'] }}" @selected((string)($fila['ingrediente_id'] ?? '') === (string)$op['ingrediente_id'])>{{ $op['nombre'] }}{{ $op['precio'] ? ' — $' . $op['precio'] . '/' . $op['unidad'] : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Cantidad *</label>
                        <input type="number" name="ingredientes[{{ $idx }}][cantidad_num]" value="{{ $fila['cantidad_num'] ?? '' }}"
                            placeholder="Ej. 500" min="0" step="0.01"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                        <input type="hidden" name="ingredientes[{{ $idx }}][cantidad]" value="{{ $fila['cantidad'] ?? '' }}" class="cantidad-legacy">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Unidad</label>
                        <select name="ingredientes[{{ $idx }}][unidad]" class="unidad-select w-full px-3 py-2 border border-gray-200 rounded-lg text-sm" data-current="{{ $fila['unidad'] ?? '' }}">
                            <option value="">—</option>
                        </select>
                    </div>
                    <button type="button" class="btn-remove-ing w-9 h-9 rounded-lg bg-red-50 hover:bg-red-100 inline-flex items-center justify-center" title="Quitar"><i class="fas fa-times text-red-600"></i></button>
                </div>
                <p class="vinculo-info text-xs mt-2"></p>
                <p class="subtotal-line text-xs font-semibold text-gray-700 mt-1"></p>
            </div>
        @endforeach
    </div>

    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-amber-700">Gasto estimado (ingredientes)</p>
            <p id="gasto-total" class="text-2xl font-bold text-gray-800 mt-1">$0.00</p>
            <p id="gasto-nota" class="text-xs text-amber-600 mt-1"></p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Precio del platillo ($) — venta</label>
            <input type="number" id="precio-platillo" name="precio_platillo" value="{{ $val('precio_platillo') }}" min="0" step="0.01" placeholder="Ej. 85.00"
                class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
            <p id="margen-hint" class="text-xs mt-1 text-gray-500"></p>
        </div>
    </div>
</div>

<div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-5">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Imágenes del platillo {{ $isEdit ? '' : '*' }}</label>
        <input type="file" name="imagenes[]" accept="image/jpeg,image/png,image/webp" multiple {{ $isEdit ? '' : 'required' }}
            class="w-full text-sm file:mr-3 file:px-4 file:py-2 file:rounded-lg file:border-0 file:bg-primary-50 file:text-primary-700">
        <p class="text-xs text-gray-400 mt-1">Hasta 6 imágenes, JPG/PNG/WebP de 2MB c/u. La primera es la portada.</p>
    </div>
    @if ($isEdit && $receta->imagenes->isNotEmpty())
        <div>
            <p class="block text-sm font-medium text-gray-700 mb-2">Galería actual (marca para eliminar)</p>
            <div class="grid grid-cols-3 gap-2">
                @foreach ($receta->imagenes as $img)
                    <label class="relative block rounded-lg overflow-hidden border border-gray-200 cursor-pointer">
                        <img src="{{ asset('storage/' . $img->path) }}" alt="" class="w-full h-20 object-cover">
                        <span class="absolute bottom-1 left-1 right-1 flex items-center gap-1 bg-white/90 rounded px-1 py-0.5 text-xs">
                            <input type="checkbox" name="eliminar_imagenes[]" value="{{ $img->id }}"> Eliminar
                        </span>
                    </label>
                @endforeach
            </div>
        </div>
    @endif
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.default.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const wrap = document.getElementById('ings-wrap');
    const btnAdd = document.getElementById('btn-add-ing');
    // id -> {prod_id, unidad, precio, nombre}
    const VINCULOS = @json($mapaVinculos ?? []);
    // nombre lower -> producto (para sugerencia cuando no hay FK)
    const PRODS = @json($prodSugerencias ?? []);
    const ING_OPTS = @json($ingOpts ?? []);
    let idx = wrap.querySelectorAll('.ing-row').length;

    const FAMILIAS = { kg: ['g', 'kg'], g: ['g', 'kg'], L: ['ml', 'L'], ml: ['ml', 'L'] };

    function compatiblesDe(unidadProd) {
        if (!unidadProd) return [];
        return FAMILIAS[unidadProd] || FAMILIAS[unidadProd.toLowerCase()] || [unidadProd];
    }

    function initTomSelect(sel) {
        if (sel.tomselect) return sel.tomselect;
        const ts = new TomSelect(sel, {
            create: false,          // no permite ingredientes inexistentes
            maxItems: 1,
            highlight: true,
            placeholder: 'Escribe para buscar…',
        });
        ts.on('change', function() { onIngredienteChange(sel.closest('.ing-row')); recalcular(); });
        return ts;
    }

    function fillUnidades(row, unidadProd, current) {
        const sel = row.querySelector('.unidad-select');
        const opts = compatiblesDe(unidadProd);
        sel.innerHTML = '<option value="">—</option>' + opts.map(u => `<option value="${u}">${u}</option>`).join('');
        sel.disabled = opts.length === 0;
        if (current && opts.includes(current)) sel.value = current;
        else if (current) sel.value = '';
    }

    function onIngredienteChange(row) {
        if (!row) return;
        const sel = row.querySelector('.ing-select');
        const info = row.querySelector('.vinculo-info');
        const id = sel.value;
        const vinc = VINCULOS[id];
        const unidadSel = row.querySelector('.unidad-select');
        const current = unidadSel.dataset.current || unidadSel.value;

        if (!id) {
            info.innerHTML = '';
            fillUnidades(row, null, null);
            return;
        }

        if (vinc && vinc.prod_id) {
            // Autollenar y bloquear: el vínculo ya existe, no se edita aquí.
            info.innerHTML = `🔒 Vinculado a almacén: <strong>${vinc.nombre ?? ''}</strong>${vinc.precio ? ` — $${vinc.precio}/${vinc.unidad}` : ''}`;
            fillUnidades(row, vinc.unidad, current);
        } else {
            // Sin FK: sugerir por nombre o pedir alta en almacén.
            const nombre = (vinc?.nombre || sel.selectedOptions[0]?.text || '').trim().toLowerCase();
            const sug = PRODS[nombre];
            fillUnidades(row, null, null);
            if (sug) {
                info.innerHTML = `⚠️ Sin vincular. Existe en almacén “<strong>${sug.nombre}</strong>” — créelo de nuevo o pida vincularlo para activar costo y unidades.`;
            } else {
                info.innerHTML = `⚠️ Sin vincular a almacén (sin costo). <a href="{{ route('admin.almacen.create') }}" target="_blank" class="underline">Crear en Almacén</a> con el mismo nombre lo vincula solo.`;
            }
        }
    }

    function rowHtml(i) {
        const ingOpts = '<option value="">Escribe para buscar…</option>' + ING_OPTS.map(o => `<option value="${o.id}">${o.nombre}</option>`).join('');
        return `<div class="ing-row bg-gray-50 p-3 rounded-xl" data-idx="${i}">
            <div class="grid grid-cols-1 md:grid-cols-[1fr_120px_130px_auto] gap-2 items-end">
                <div><label class="block text-xs text-gray-500 mb-1">Ingrediente *</label>
                <select name="ingredientes[${i}][ingrediente_id]" class="ing-select w-full" required>${ingOpts}</select></div>
                <div><label class="block text-xs text-gray-500 mb-1">Cantidad *</label>
                <input type="number" name="ingredientes[${i}][cantidad_num]" placeholder="Ej. 500" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                <input type="hidden" name="ingredientes[${i}][cantidad]" value="" class="cantidad-legacy"></div>
                <div><label class="block text-xs text-gray-500 mb-1">Unidad</label>
                <select name="ingredientes[${i}][unidad]" class="unidad-select w-full px-3 py-2 border border-gray-200 rounded-lg text-sm" data-current=""><option value="">—</option></select></div>
                <button type="button" class="btn-remove-ing w-9 h-9 rounded-lg bg-red-50 hover:bg-red-100 inline-flex items-center justify-center" title="Quitar"><i class="fas fa-times text-red-600"></i></button>
            </div>
            <p class="vinculo-info text-xs mt-2"></p>
            <p class="subtotal-line text-xs font-semibold text-gray-700 mt-1"></p>
        </div>`;
    }

    // Dual-write: mantiene el texto legacy sincronizado (num + unidad).
    function syncFila(row, target) {
        const num = row.querySelector('input[name$="[cantidad_num]"]');
        const uni = row.querySelector('.unidad-select');
        const legacy = row.querySelector('.cantidad-legacy');
        if (legacy && (target === num || target === uni)) {
            legacy.value = [num.value, uni.value].filter(Boolean).join(' ').trim();
        }
        recalcular();
    }
    wrap.addEventListener('input', function(e) {
        const row = e.target.closest('.ing-row');
        if (row) syncFila(row, e.target);
    });
    wrap.addEventListener('change', function(e) {
        const row = e.target.closest('.ing-row');
        if (row) syncFila(row, e.target);
    });

    btnAdd.addEventListener('click', function() {
        wrap.insertAdjacentHTML('beforeend', rowHtml(idx++));
        const row = wrap.lastElementChild;
        initTomSelect(row.querySelector('.ing-select'));
        recalcular();
    });

    wrap.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-remove-ing');
        if (!btn) return;
        if (wrap.querySelectorAll('.ing-row').length <= 1) { alert('Deja al menos una fila.'); return; }
        const row = btn.closest('.ing-row');
        const sel = row.querySelector('.ing-select');
        if (sel && sel.tomselect) sel.tomselect.destroy();
        row.remove();
        recalcular();
    });

    // Init inicial + estado de vínculo por fila precargada.
    wrap.querySelectorAll('.ing-row').forEach(function(row) {
        initTomSelect(row.querySelector('.ing-select'));
        onIngredienteChange(row);
    });

    // ---------- Gasto estimado en vivo + margen ----------
    const gastoTotal = document.getElementById('gasto-total');
    const gastoNota = document.getElementById('gasto-nota');
    const precioInput = document.getElementById('precio-platillo');
    const margenHint = document.getElementById('margen-hint');

    function factorConversion(unidadReceta, unidadProd) {
        const r = (unidadReceta || '').toLowerCase();
        const p = (unidadProd || '').toLowerCase();
        if (!r) return 1.0;               // sin unidad: misma del producto
        if (!p || r === p) return r === p ? 1.0 : null;
        const peso = { g: 1, kg: 1000 };
        const vol = { ml: 1, l: 1000 };
        if (peso[r] && peso[p]) return peso[r] / peso[p];
        if (vol[r] && vol[p]) return vol[r] / vol[p];
        return null;
    }

    function filaSubtotal(row) {
        const id = row.querySelector('.ing-select').value;
        const vinc = VINCULOS[id];
        const num = parseFloat(row.querySelector('input[name$="[cantidad_num]"]').value);
        const uni = row.querySelector('.unidad-select').value;
        if (!id || isNaN(num) || !vinc || !vinc.prod_id || vinc.precio === null || vinc.precio === undefined) {
            return { subtotal: null, motivo: !id ? 'sin ingrediente' : 'sin precio/cantidad' };
        }
        const f = factorConversion(uni, vinc.unidad);
        if (f === null) return { subtotal: null, motivo: 'unidad incompatible' };
        return { subtotal: Math.round(num * f * parseFloat(vinc.precio) * 100) / 100, motivo: null };
    }

    function fmt(n) { return '$' + n.toFixed(2); }

    function recalcular() {
        let total = 0, parcial = false;
        wrap.querySelectorAll('.ing-row').forEach(function(row) {
            const { subtotal, motivo } = filaSubtotal(row);
            const line = row.querySelector('.subtotal-line');
            if (subtotal !== null) {
                total += subtotal;
                line.textContent = 'Subtotal: ' + fmt(subtotal);
            } else {
                parcial = true;
                line.textContent = row.querySelector('.ing-select').value ? 'Subtotal: — (' + motivo + ')' : '';
            }
        });
        gastoTotal.textContent = fmt(Math.round(total * 100) / 100);
        gastoNota.textContent = parcial ? '* Parcial: vincula todo a almacén con cantidades numéricas.' : '';
        actualizarMargen(Math.round(total * 100) / 100);
    }

    function actualizarMargen(gasto) {
        const precio = parseFloat(precioInput.value);
        if (isNaN(precio)) { margenHint.textContent = ''; return; }
        const margen = Math.round((precio - gasto) * 100) / 100;
        const pct = precio > 0 ? ' · ' + (Math.round(margen / precio * 1000) / 10) + '%' : '';
        margenHint.textContent = 'Margen: ' + (margen >= 0 ? '+' : '') + fmt(margen) + pct;
        margenHint.className = 'text-xs mt-1 font-semibold ' + (margen >= 0 ? 'text-green-600' : 'text-red-600');
        if (margen < 0) margenHint.textContent += ' — el precio no cubre el gasto';
    }

    precioInput?.addEventListener('input', function() {
        actualizarMargen(parseFloat(gastoTotal.textContent.replace('$', '')) || 0);
    });

    // ---------- Procedimiento por pasos ----------
    const pasosWrap = document.getElementById('pasos-wrap');
    const btnAddPaso = document.getElementById('btn-add-paso');
    const procHidden = document.getElementById('procedimiento-hidden');

    function renumerarPasos() {
        pasosWrap.querySelectorAll('.paso-row').forEach(function(row, i) {
            row.querySelector('.paso-num').textContent = i + 1;
        });
    }

    function syncProcedimiento() {
        const pasos = [...pasosWrap.querySelectorAll('.paso-input')]
            .map(el => el.value.trim()).filter(Boolean);
        procHidden.value = pasos.join('\n');
    }

    function pasoHtml() {
        return `<div class="paso-row flex items-start gap-2">
            <span class="paso-num mt-2 w-7 h-7 rounded-full bg-primary-50 text-primary-700 text-sm font-bold inline-flex items-center justify-center flex-shrink-0"></span>
            <input type="text" class="paso-input w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="Describe este paso…" maxlength="1000">
            <button type="button" class="paso-up w-9 h-9 rounded-lg bg-gray-100 hover:bg-gray-200 inline-flex items-center justify-center flex-shrink-0" title="Subir"><i class="fas fa-arrow-up text-gray-600 text-xs"></i></button>
            <button type="button" class="paso-down w-9 h-9 rounded-lg bg-gray-100 hover:bg-gray-200 inline-flex items-center justify-center flex-shrink-0" title="Bajar"><i class="fas fa-arrow-down text-gray-600 text-xs"></i></button>
            <button type="button" class="paso-del w-9 h-9 rounded-lg bg-red-50 hover:bg-red-100 inline-flex items-center justify-center flex-shrink-0" title="Quitar paso"><i class="fas fa-times text-red-600"></i></button>
        </div>`;
    }

    btnAddPaso.addEventListener('click', function() {
        pasosWrap.insertAdjacentHTML('beforeend', pasoHtml());
        renumerarPasos();
        pasosWrap.lastElementChild.querySelector('.paso-input').focus();
    });

    pasosWrap.addEventListener('click', function(e) {
        const row = e.target.closest('.paso-row');
        if (!row) return;
        if (e.target.closest('.paso-del')) {
            if (pasosWrap.querySelectorAll('.paso-row').length <= 1) { alert('Deja al menos un paso.'); return; }
            row.remove();
        } else if (e.target.closest('.paso-up')) {
            if (row.previousElementSibling) row.parentNode.insertBefore(row, row.previousElementSibling);
        } else if (e.target.closest('.paso-down')) {
            if (row.nextElementSibling) row.parentNode.insertBefore(row.nextElementSibling, row);
        } else return;
        renumerarPasos();
        syncProcedimiento();
    });

    pasosWrap.addEventListener('input', syncProcedimiento);
    document.querySelector('form').addEventListener('submit', function(e) {
        syncProcedimiento();
        if (procHidden.value.trim().length < 10) {
            e.preventDefault();
            alert('Agrega al menos un paso de procedimiento (mínimo 10 caracteres).');
        }
    });

    renumerarPasos();
    syncProcedimiento();
    recalcular();
});
</script>
@endpush
