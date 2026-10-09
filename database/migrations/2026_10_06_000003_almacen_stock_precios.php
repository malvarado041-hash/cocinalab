<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Stock inicial y precio de todos los productos de almacén.
 *
 * Criterio (restaurante medio, precios MX promedio 2025-2026 por unidad):
 * - stock: par semanal típico según rotación (frescos/proteínas alto,
 *   especias en gramaje, desechables por paquete).
 * - stock_minimo: punto de reorden ≈ 25% del par.
 * - precio_unitario: precio de compra promedio en la unidad de conteo
 *   del producto (kg, pieza, litro, paquete, etc.).
 */
return new class extends Migration
{
    /** nombre => [stock, stock_minimo, precio_unitario] */
    private array $datos = [
        // Verdura
        'tomate verde' => [8, 2, 28],
        'chile serrano' => [60, 15, 1],
        'diente de ajo' => [40, 10, 0.5],
        'cilantro' => [12, 3, 8],
        'cebolla morada' => [15, 4, 6],
        'cebolla blanca' => [20, 5, 5],
        'lechuga romana' => [5, 1.5, 22],
        'pimiento rojo' => [3, 1, 55],
        'zanahoria' => [6, 2, 18],
        'elote' => [10, 3, 25],
        'chícharos' => [4, 1, 35],
        'espinaca fresca' => [2, 0.5, 60],
        'papa' => [10, 3, 22],
        'tomate rojo' => [8, 2, 25],
        'tomate cherry' => [2, 0.5, 70],
        // Fruta
        'aguacate' => [20, 5, 18],
        'plátano' => [25, 6, 4],
        'mango' => [15, 4, 12],
        'fresas' => [3, 1, 80],
        'Cascara de citrico' => [10, 2, 2],
        // Proteína fresca
        'pechuga de pollo' => [10, 3, 95],
        'muslo de pollo' => [8, 2, 65],
        'pollo cocido' => [4, 1, 110],
        'carne de res' => [8, 2, 180],
        'carne molida' => [6, 2, 170],
        'filete de pescado' => [5, 1.5, 160],
        'jamón' => [4, 1, 120],
        'huevo' => [120, 30, 3.5],
        // Lácteos
        'leche' => [12, 3, 28],
        'crema ácida' => [8, 2, 35],
        'queso fresco' => [6, 2, 55],
        'Queso manchego' => [4, 1, 90],
        'queso rallado' => [5, 1, 70],
        'Queso crema' => [6, 2, 45],
        'mantequilla' => [6, 2, 40],
        'yogurth natural' => [5, 1, 45],
        // Abarrotes
        'arroz' => [10, 3, 28],
        'avena' => [5, 1, 35],
        'pasta' => [6, 2, 30],
        'espagueti' => [5, 1, 30],
        'lentejas' => [5, 1, 32],
        'Frijoles refritos' => [12, 3, 22],
        'granola' => [3, 1, 90],
        'crotones' => [2, 0.5, 80],
        'Harina' => [8, 2, 20],
        'ajo en polvo' => [500, 100, 0.4],
        'Sal' => [2000, 500, 0.02],
        'Orégano' => [300, 50, 0.6],
        'Canela' => [200, 50, 0.8],
        'Vainilla' => [250, 50, 0.5],
        'Polvo para hornear' => [300, 50, 0.25],
        'Cacao en polvo' => [500, 100, 0.24],
        'aderezo César' => [6, 2, 45],
        'soja' => [4, 1, 60],
        'salsa de tomate' => [8, 2, 30],
        'mayonesa' => [5, 1, 65],
        'aceite vegetal' => [8, 2, 45],
        'aceite de oliva' => [3, 1, 180],
        'vinagre balsámico' => [2, 0.5, 120],
        'atún en agua' => [20, 5, 22],
        'caldo de pollo' => [8, 2, 25],
        'Azucar' => [8, 2, 28],
        'miel' => [2, 0.5, 150],
        'Chocolate semiamargo' => [2, 0.5, 180],
        'Galleta' => [8, 2, 35],
        'Gelatina' => [15, 4, 18],
        'Leche condensada' => [8, 2, 28],
        'Leche evaporada' => [8, 2, 25],
        // Bebidas
        'agua' => [24, 6, 15],
        'Jugo de limón' => [6, 2, 35],
        // Pan y tortilla
        'pan de caja' => [6, 2, 45],
        'pan integral' => [5, 1, 50],
        'bolillo' => [60, 15, 3],
        'baguette' => [8, 2, 25],
        'ciabatta' => [12, 3, 15],
        'tortilla de harina' => [5, 1, 25],
        'tortilla de maíz' => [8, 2, 22],
        'tostada de maíz' => [8, 2, 30],
        'totopos' => [8, 2, 35],
    ];

    public function up(): void
    {
        if (! DB::getSchemaBuilder()->hasTable('almacen_productos')) {
            return;
        }

        foreach ($this->datos as $nombre => [$stock, $minimo, $precio]) {
            DB::table('almacen_productos')->where('nombre', $nombre)->update([
                'stock' => $stock,
                'stock_minimo' => $minimo,
                'precio_unitario' => $precio,
            ]);
        }
    }

    public function down(): void
    {
        // Datos iniciales: no reversible.
    }
};
