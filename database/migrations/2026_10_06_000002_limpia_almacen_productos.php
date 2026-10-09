<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Limpieza del catálogo de almacén importado de `ingredientes`:
 * - Elimina duplicados (pechuga pollo, chiles serranos, crema, queso
 *   genérico, yogur, papas, pan/tortilla/tomate/cebolla genéricos,
 *   preparaciones como "cocido/picada", genéricos Fruta/Salsa al gusto,
 *   atún escurrido y arvejas = chícharos).
 * - Corrige categorías/subcategorías mal asignadas por la heurística
 *   (ajo en polvo→especia, espinaca/fresas fuera de proteínas, etc.).
 * - Normaliza nombres ambiguos y banderas de perecedero.
 *
 * Todo por nombre exacto: es idempotente y segura de re-correr.
 */
return new class extends Migration
{
    /** nombre => [categoria, subcategoria, unidad] */
    private array $recategorizar = [
        'ajo en polvo' => ['abarrote', 'especia', 'g'],
        'aderezo César' => ['abarrote', 'salsa_condimento', 'pieza'],
        'soja' => ['abarrote', 'salsa_condimento', 'pieza'],
        'salsa de tomate' => ['abarrote', 'salsa_condimento', 'pieza'],
        'caldo de pollo' => ['abarrote', 'enlatado', 'pieza'],
        'crotones' => ['abarrote', 'grano_cereal', 'kg'],
        'Jugo de limón' => ['bebida', 'jugo', 'pieza'],
        'Leche condensada' => ['abarrote', 'enlatado', 'lata'],
        'Leche evaporada' => ['abarrote', 'enlatado', 'lata'],
        'Cacao en polvo' => ['abarrote', 'dulce', 'g'],
        'Galleta' => ['abarrote', 'dulce', 'paquete'],
        'Gelatina' => ['abarrote', 'dulce', 'pieza'],
        'Frijoles refritos' => ['abarrote', 'enlatado', 'lata'],
        'tortilla de harina' => ['pan_tortilla', 'tortilla', 'kg'],
        'espinaca fresca' => ['ingrediente', 'verdura', 'kg'],
        'fresas' => ['ingrediente', 'fruta', 'kg'],
        'huevo' => ['ingrediente', 'proteina_fresca', 'pieza'],
        'cilantro' => ['ingrediente', 'verdura', 'manojo'],
    ];

    private array $eliminar = [
        'pechuga pollo', // = pechuga de pollo
        'chiles serranos', // = chile serrano
        'diente ajo', // = diente de ajo (si existe)
        'dientes de ajo', // = diente de ajo (si existe)
        'crema', // = crema ácida
        'queso', // genérico; quedan fresco/manchego/rallado/crema
        'yogur', // = yogurth natural
        'papas', // = papa
        'pan', // genérico; quedan caja/integral/bolillo/baguette/ciabatta
        'tortilla', // genérica; quedan harina/maíz
        'tomate', // genérico; quedan verde/rojo/cherry
        'cebolla', // genérica; quedan morada/blanca
        'cebolla picada', // preparación, no insumo
        'Fruta', // genérico
        'Salsa al gusto', // genérico
        'pasta cocida', // = pasta
        'arroz cocido', // = arroz
        'atún escurrido', // = atún en agua
        'arvejas', // = chícharos
    ];

    /** nombre actual => nombre nuevo */
    private array $renombrar = [
        'aceite' => 'aceite vegetal',
        'carne' => 'carne de res',
        'filete pescado' => 'filete de pescado',
    ];

    public function up(): void
    {
        if (! DB::getSchemaBuilder()->hasTable('almacen_productos')) {
            return;
        }

        foreach ($this->recategorizar as $nombre => [$cat, $sub, $uni]) {
            DB::table('almacen_productos')->where('nombre', $nombre)->update([
                'categoria' => $cat,
                'subcategoria' => $sub,
                'unidad' => $uni,
            ]);
        }

        foreach ($this->renombrar as $antes => $despues) {
            // Evita chocar con un unique si el destino ya existe.
            if (! DB::table('almacen_productos')->where('nombre', $despues)->exists()) {
                DB::table('almacen_productos')->where('nombre', $antes)->update(['nombre' => $despues]);
            }
        }

        DB::table('almacen_productos')->whereIn('nombre', $this->eliminar)->delete();

        // Perecedero coherente con la categoría final.
        DB::table('almacen_productos')->whereIn('categoria', ['ingrediente', 'pan_tortilla'])->update(['perecedero' => true]);
        DB::table('almacen_productos')->whereNotIn('categoria', ['ingrediente', 'pan_tortilla'])->update(['perecedero' => false]);
    }

    public function down(): void
    {
        // Limpieza de datos: no reversible.
    }
};
