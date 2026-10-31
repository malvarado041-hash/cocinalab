@php
    $isEdit = isset($receta);
    $val = fn($k, $d = '') => old($k, $isEdit ? $receta->$k : $d);
    $oldIngs = array_values(old('ingredientes', $isEdit ? $receta->ingredientes->map(fn($i) => [
        'almacen_producto_id' => $i->almacen_producto_id, 'ingrediente_anterior' => $i->Nombre, 'cantidad' => $i->pivot->cantidad,
        'cantidad_num' => $i->pivot->cantidad_num, 'unidad' => $i->pivot->unidad,
    ])->all() : [['almacen_producto_id' => '', 'cantidad_num' => '', 'unidad' => '']]));
    $procTexto = old('Procedimiento', $isEdit ? implode("\n", $receta->pasos()) : '');
    $oldPasos = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $procTexto)))) ?: [''];
@endphp
<div class="recipe-intro">
    <div><span class="recipe-eyebrow">RECETARIO DEL RESTAURANTE</span><h2>{{ $isEdit ? 'Perfecciona tu receta' : 'De la cocina al recetario' }}</h2>
    <p>Define el rendimiento, agrega los ingredientes y explica cómo preparar el platillo.</p></div>
    <a class="recipe-back" href="{{ route('admin.recetas.index') }}"><i class="fas fa-arrow-left" aria-hidden="true"></i> Volver a recetas</a>
</div>
<div class="recipe-grid">
<div class="recipe-sections">
    <section class="recipe-panel" aria-labelledby="datos-title">
        <header class="recipe-section-heading"><span class="recipe-section-number">1</span><div><h3 id="datos-title">Datos del platillo</h3><p>Dale un nombre y define cuántas porciones rinde.</p></div></header>
        <div class="recipe-fields">
            <div class="recipe-field recipe-name"><label for="receta-nombre">Nombre del platillo <span>*</span></label><input id="receta-nombre" name="Nombre" value="{{ $val('Nombre') }}" required minlength="2" maxlength="200" placeholder="Ej. Chilaquiles verdes con pollo" autocomplete="off"></div>
            <div class="recipe-field"><label for="receta-tipo">Categoría <span>*</span></label><select id="receta-tipo" name="TipoC" required><option value="">Selecciona una categoría</option>@foreach($tipos as $t)<option @selected($val('TipoC') === $t)>{{ $t }}</option>@endforeach</select></div>
            <div class="recipe-field"><label for="porciones">Porciones que rinde <span>*</span></label><input id="porciones" name="porciones" type="number" min="1" max="1000" required value="{{ $val('porciones', 1) }}" aria-describedby="rendimiento-help"><small id="rendimiento-help">Las cantidades de abajo son para la receta completa.</small></div>
        </div>
    </section>
    <section class="recipe-panel" aria-labelledby="ingredientes-title">
        <header class="recipe-section-heading"><span class="recipe-section-number">2</span><div><h3 id="ingredientes-title">Ingredientes</h3><p>Selecciona directamente los productos activos de tu almacén.</p></div><span id="ingredientes-count" class="recipe-count">0 ingredientes</span></header>
        @if(empty($opciones))
            <div class="recipe-notice">Todavía no hay productos activos en el almacén. <a href="{{ route('admin.almacen.create') }}">Registra un producto</a> para comenzar.</div>
        @endif
        <div id="ings-wrap">
        @foreach($oldIngs as $idx => $fila)
            @include('admin.recetas._ingrediente', ['fila' => $fila, 'idx' => $idx])
        @endforeach
        </div>
        <div class="recipe-section-footer"><button type="button" id="btn-add-ing" class="recipe-add" @disabled(empty($opciones))><i class="fas fa-plus" aria-hidden="true"></i> Agregar ingrediente</button><a href="{{ route('admin.almacen.create') }}" target="_blank" rel="noopener">¿Falta un ingrediente? Registrar producto <i class="fas fa-arrow-up-right-from-square" aria-hidden="true"></i></a></div>
        <p class="recipe-hint"><i class="fas fa-circle-info" aria-hidden="true"></i> Guardar la receta no descuenta existencias. El consumo se registra cuando cocina marca el platillo como listo.</p>
    </section>
    <section class="recipe-panel" aria-labelledby="pasos-title">
        <header class="recipe-section-heading"><span class="recipe-section-number">3</span><div><h3 id="pasos-title">Preparación</h3><p>Una instrucción por paso. Puedes cambiar su orden.</p></div></header>
        <div id="pasos-wrap">
        @foreach($oldPasos as $idx => $paso)
            @include('admin.recetas._paso', ['paso' => $paso, 'idx' => $idx])
        @endforeach
        </div>
        <button type="button" id="btn-add-paso" class="recipe-add"><i class="fas fa-plus" aria-hidden="true"></i> Agregar paso</button>
        <textarea name="Procedimiento" id="procedimiento-hidden" hidden>{{ $procTexto }}</textarea>
        <p id="pasos-error" class="recipe-error" role="alert" hidden>Describe la preparación con al menos 10 caracteres.</p>
    </section>
    <section class="recipe-panel" aria-labelledby="fotos-title">
        <header class="recipe-section-heading"><span class="recipe-section-number">4</span><div><h3 id="fotos-title">Fotografías <small>Opcional</small></h3><p>La primera imagen será la portada del platillo.</p></div></header>
        <label for="receta-imagenes" class="recipe-upload"><i class="fas fa-camera" aria-hidden="true"></i><strong>Agrega fotos de tu platillo</strong><span>JPG, PNG o WebP · hasta 6 imágenes de 2 MB</span><input id="receta-imagenes" type="file" name="imagenes[]" accept="image/jpeg,image/png,image/webp" multiple></label>
        <div id="image-previews" class="recipe-previews" aria-live="polite"></div>
        <p id="image-error" class="recipe-error" role="alert" hidden></p>
        @if($isEdit && $receta->imagenes->isNotEmpty())
        <div class="recipe-previews">@foreach($receta->imagenes as $img)<label><img src="{{ asset('storage/'.$img->path) }}" alt="Foto actual del platillo"><span><input type="checkbox" name="eliminar_imagenes[]" value="{{ $img->id }}"> Eliminar foto</span></label>@endforeach</div>
        @endif
    </section>
</div>
<aside class="recipe-summary" aria-labelledby="resumen-title">
    <div class="recipe-panel">
        <span class="recipe-eyebrow">COSTO Y VENTA</span><h3 id="resumen-title">Resumen de la receta</h3>
        <dl class="recipe-totals"><div><dt>Receta completa</dt><dd id="costo-receta">—</dd></div><div><dt>Rendimiento</dt><dd id="resumen-porciones">1 porción</dd></div></dl>
        <div class="recipe-cost"><span>Costo por porción</span><strong id="gasto-total">—</strong><small id="gasto-nota" aria-live="polite">Agrega los ingredientes y sus cantidades.</small></div>
        <div class="recipe-field"><label for="precio-platillo">Precio de venta por porción</label><div class="recipe-price"><span>$</span><input id="precio-platillo" name="precio_platillo" type="number" min="0" max="9999999.99" step="0.01" value="{{ $val('precio_platillo') }}" placeholder="0.00" aria-describedby="margen-hint"></div><small>Opcional. Necesario para ofrecer el platillo en comandas.</small></div>
        <p id="margen-hint" class="recipe-margin" aria-live="polite">Completa los ingredientes para calcular el margen.</p>
        <button type="submit" id="guardar-receta" class="btn-primary recipe-save" @disabled(empty($opciones))><i class="fas fa-check" aria-hidden="true"></i> {{ $isEdit ? 'Guardar cambios' : 'Guardar receta' }}</button>
        <a class="recipe-cancel" href="{{ $isEdit ? route('admin.recetas.show', $receta) : route('admin.recetas.index') }}">Cancelar</a>
        <p class="recipe-required">Los campos con * son obligatorios.</p>
    </div>
</aside>
</div>
<template id="ingrediente-template">@include('admin.recetas._ingrediente', ['idx' => '__INDEX__', 'fila' => []])</template>
<template id="paso-template">@include('admin.recetas._paso', ['idx' => '__INDEX__', 'paso' => ''])</template>
<script type="application/json" id="receta-vinculos">@json($mapaVinculos)</script>
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.default.min.css" rel="stylesheet">
<link href="{{ asset('css/receta-form.css') }}?v=1" rel="stylesheet">
@endpush
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script src="{{ asset('js/receta-form.js') }}?v=1"></script>
@endpush
