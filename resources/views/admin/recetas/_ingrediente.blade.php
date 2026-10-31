<div class="ing-row" data-idx="{{ $idx }}">
    <div class="recipe-ingredient-fields">
        <div class="recipe-field"><label for="ing-{{ $idx }}">Ingrediente *</label><select id="ing-{{ $idx }}" name="ingredientes[{{ $idx }}][ingrediente_id]" class="ing-select" required>
            <option value="">Buscar ingrediente del almacén…</option>
            @foreach($opciones as $op)<option value="{{ $op['ingrediente_id'] }}" @selected((string)($fila['ingrediente_id'] ?? '') === (string)$op['ingrediente_id'])>{{ $op['nombre'] }}</option>@endforeach
        </select>
        @if(!empty($fila['ingrediente_id']) && !collect($opciones)->contains('ingrediente_id', (int)$fila['ingrediente_id']))<small class="recipe-error">El ingrediente anterior ya no está vinculado a un producto activo. Selecciona un reemplazo.</small>@endif
        </div>
        <div class="recipe-field"><label for="cantidad-{{ $idx }}">Cantidad *</label><input id="cantidad-{{ $idx }}" name="ingredientes[{{ $idx }}][cantidad_num]" type="number" class="cantidad-input" min="0.01" step="0.01" required value="{{ $fila['cantidad_num'] ?? '' }}" placeholder="Ej. 200"><input type="hidden" name="ingredientes[{{ $idx }}][cantidad]" class="cantidad-legacy" value="{{ $fila['cantidad'] ?? '' }}"></div>
        <div class="recipe-field"><label for="unidad-{{ $idx }}">Unidad *</label><select id="unidad-{{ $idx }}" name="ingredientes[{{ $idx }}][unidad]" class="unidad-select" data-current="{{ $fila['unidad'] ?? '' }}" required><option value="">Selecciona…</option></select></div>
        <button type="button" class="btn-remove-ing recipe-icon-button" title="Quitar ingrediente" aria-label="Quitar ingrediente"><i class="fas fa-trash-can" aria-hidden="true"></i></button>
    </div>
    <div class="recipe-ingredient-meta"><p class="vinculo-info">Selecciona un ingrediente para ver su precio de referencia.</p><p class="subtotal-line">—</p></div>
</div>
