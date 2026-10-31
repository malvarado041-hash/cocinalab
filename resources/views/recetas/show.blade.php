@extends('layouts.app')
@section('title', $receta->Nombre)
@section('content')
<div class="receta-detalle">
    <h2>{{ $receta->Nombre }}</h2>
    <p><strong>Tipo:</strong> {{ $receta->TipoC }}</p>

    @if ($receta->imagenes->isNotEmpty())
        <div class="receta-galeria">
            @foreach ($receta->imagenes as $img)
                <img src="{{ asset('storage/' . $img->path) }}" alt="{{ $receta->Nombre }}" loading="lazy">
            @endforeach
        </div>
    @elseif (!empty($receta->Imagenes))
        <div class="receta-galeria">
            <img src="{{ $receta->portada }}" alt="{{ $receta->Nombre }}" loading="lazy">
        </div>
    @endif

    <h3>Procedimiento</h3>
    <ol class="receta-pasos">
        @forelse ($receta->pasos() as $paso)
            <li>{{ $paso }}</li>
        @empty
            <li>Sin procedimiento registrado.</li>
        @endforelse
    </ol>

    @if (($costo['lineas'] ?? []) !== [])
        <div class="receta-costo">
            <h3>Ingredientes y costo estimado</h3>
            <table>
                <thead>
                    <tr><th>Ingrediente</th><th>Cantidad</th><th>Precio ref.</th><th>Subtotal</th></tr>
                </thead>
                <tbody>
                    @foreach ($costo['lineas'] as $l)
                        <tr>
                            <td>{{ $l['ingrediente'] }}</td>
                            <td>
                                @if ($l['cantidad_num'] !== null)
                                    {{ rtrim(rtrim(number_format($l['cantidad_num'], 2), '0'), '.') }}{{ !empty($l['unidad_receta']) ? ' ' . $l['unidad_receta'] : '' }}
                                @else
                                    {{ $l['cantidad'] ?? '—' }}
                                @endif
                            </td>
                            <td>
                                @if ($l['precio'] !== null) ${{ number_format($l['precio'], 2) }}{{ $l['unidad'] ? '/' . $l['unidad'] : '' }}
                                @else sin dato @endif
                            </td>
                            <td>{{ $l['subtotal'] !== null ? '$' . number_format($l['subtotal'], 2) : '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p class="total">{{ $costo['completo'] ? 'Total estimado:' : 'Costo incompleto · subtotal conocido:' }} ${{ number_format($costo['total'], 2) }}</p>
            @unless($costo['completo'])
                <p><small>* Costo parcial: algunos ingredientes no tienen precio o cantidad numérica.</small></p>
            @endunless
        </div>
    @endif

    <a class="receta-volver" href="{{ route('recetas.index') }}">Volver a Recetas</a>
</div>
<p>Rinde {{ $receta->porciones }} porciones. Costo por porción: {{ $costo['completo'] ? '$'.number_format($costo['por_porcion'], 2) : 'Pendiente de completar' }}</p>
@endsection
