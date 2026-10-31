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
        // Reutiliza únicamente un ingrediente libre; nunca modifica el vínculo
        // de un ingrediente que ya pertenece a otro producto.
        $existente = Ingrediente::where('Nombre', $producto->nombre)
            ->whereNull('almacen_producto_id')
            ->lockForUpdate()
            ->first();

        if ($existente) {
            $existente->update(['almacen_producto_id' => $producto->id]);

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
