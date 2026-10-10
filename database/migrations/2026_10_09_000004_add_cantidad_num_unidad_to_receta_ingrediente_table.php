<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Unidades reconocibles en el texto legacy para el backfill. */
    private const UNIDADES = ['kg', 'g', 'L', 'ml', 'pieza', 'piezas', 'manojo', 'lata', 'botella', 'paquete', 'caja', 'taza', 'tazas', 'cucharada', 'cucharadas', 'cucharadita', 'cucharaditas'];

    public function up(): void
    {
        Schema::table('receta_ingrediente', function (Blueprint $table) {
            $table->decimal('cantidad_num', 10, 2)->nullable()->after('cantidad');
            $table->string('unidad', 20)->nullable()->after('cantidad_num');
        });

        // Backfill best-effort: número líder + unidad reconocible del texto.
        DB::table('receta_ingrediente')->orderBy('id')->chunkById(500, function ($filas) {
            foreach ($filas as $fila) {
                $texto = trim((string) ($fila->cantidad ?? ''));
                if ($texto === '') {
                    continue;
                }

                $num = null;
                if (preg_match('/^(\d+)\s*\/\s*(\d+)/', $texto, $m)) {
                    $num = ((float) $m[2]) != 0 ? ((float) $m[1] / (float) $m[2]) : null;
                } elseif (preg_match('/^(\d+(?:[.,]\d+)?)/', $texto, $m)) {
                    $num = (float) str_replace(',', '.', $m[1]);
                }

                $unidad = null;
                foreach (self::UNIDADES as $u) {
                    if (preg_match('/\b' . preg_quote($u, '/') . '\b/i', $texto)) {
                        $unidad = strtolower($u) === 'piezas' ? 'pieza' : strtolower($u);
                        break;
                    }
                }

                if ($num !== null || $unidad !== null) {
                    DB::table('receta_ingrediente')->where('id', $fila->id)->update([
                        'cantidad_num' => $num,
                        'unidad' => $unidad,
                    ]);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('receta_ingrediente', function (Blueprint $table) {
            $table->dropColumn(['cantidad_num', 'unidad']);
        });
    }
};
