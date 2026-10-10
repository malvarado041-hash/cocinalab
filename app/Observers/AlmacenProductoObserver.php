<?php

namespace App\Observers;

use App\Models\AlmacenProducto;
use App\Models\Ingrediente;

/**
 * Todo producto de almacén tiene su ingrediente espejo para que
 * el form de recetas siempre encuentre el ingrediente (sin alta
 * al vuelo desde recetas: el alta es solo desde almacén).
 */
class AlmacenProductoObserver
{
    public function created(AlmacenProducto $producto): void
    {
        $existente = Ingrediente::where('Nombre', $producto->nombre)->first();

        if ($existente) {
            if ($existente->almacen_producto_id === null) {
                $existente->update(['almacen_producto_id' => $producto->id]);
            }
            return;
        }

        Ingrediente::create([
            'Nombre' => $producto->nombre,
            'almacen_producto_id' => $producto->id,
        ]);
    }

    public function updated(AlmacenProducto $producto): void
    {
        if (! $producto->wasChanged('nombre')) {
            return;
        }

        Ingrediente::where('almacen_producto_id', $producto->id)
            ->where('Nombre', $producto->getOriginal('nombre'))
            ->update(['Nombre' => $producto->nombre]);
    }
}
