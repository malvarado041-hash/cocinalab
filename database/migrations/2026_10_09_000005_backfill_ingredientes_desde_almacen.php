<?php

use App\Models\AlmacenProducto;
use App\Models\Ingrediente;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Todo producto de almacén debe tener su ingrediente espejo, porque
     * el form de recetas lista ingredientes únicamente desde almacén.
     * Idempotente: no duplica si ya existe.
     */
    public function up(): void
    {
        AlmacenProducto::orderBy('id')->chunkById(200, function ($productos) {
            foreach ($productos as $producto) {
                $existente = Ingrediente::where('Nombre', $producto->nombre)->first();

                if ($existente) {
                    if ($existente->almacen_producto_id === null) {
                        $existente->update(['almacen_producto_id' => $producto->id]);
                    }
                    continue;
                }

                Ingrediente::create([
                    'Nombre' => $producto->nombre,
                    'almacen_producto_id' => $producto->id,
                ]);
            }
        });
    }

    public function down(): void
    {
        // No borra nada: los espejos pueden estar usados en recetas.
    }
};
