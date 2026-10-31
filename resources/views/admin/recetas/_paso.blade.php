<div class="paso-row">
    <span class="paso-num" aria-hidden="true">{{ is_numeric($idx) ? $idx + 1 : '' }}</span>
    <div class="recipe-field"><label class="sr-only" for="paso-{{ $idx }}">Instrucción de preparación</label><textarea id="paso-{{ $idx }}" class="paso-input" rows="2" maxlength="1000" required placeholder="Ej. Calienta el aceite a fuego medio y sofríe la cebolla durante 3 minutos.">{{ $paso }}</textarea></div>
    <div class="recipe-step-actions"><button type="button" class="paso-up recipe-icon-button" aria-label="Subir paso" title="Subir paso"><i class="fas fa-arrow-up" aria-hidden="true"></i></button><button type="button" class="paso-down recipe-icon-button" aria-label="Bajar paso" title="Bajar paso"><i class="fas fa-arrow-down" aria-hidden="true"></i></button><button type="button" class="paso-del recipe-icon-button" aria-label="Quitar paso" title="Quitar paso"><i class="fas fa-xmark" aria-hidden="true"></i></button></div>
</div>
