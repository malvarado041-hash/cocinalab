<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receta extends Model
{
    use SoftDeletes;

    protected $table = 'recetas';
    public $timestamps = false;
    protected $guarded = [];

    public const TIPOS = ['Desayuno', 'Comida', 'Cena', 'Postre'];

    public function ingredientes()
    {
        return $this->belongsToMany(Ingrediente::class, 'receta_ingrediente', 'receta_id', 'ingrediente_id')
            ->withPivot('id', 'cantidad', 'cantidad_num', 'unidad');
    }

    public function imagenes()
    {
        return $this->hasMany(RecetaImagen::class, 'receta_id')->orderBy('orden');
    }

    /** Primera imagen de la galería o legacy Imagenes o placeholder. */
    public function getPortadaAttribute(): string
    {
        $primera = $this->relationLoaded('imagenes')
            ? $this->imagenes->first()
            : $this->imagenes()->first();

        if ($primera) {
            return asset('storage/' . $primera->path);
        }

        if (! empty($this->Imagenes)) {
            return str_starts_with($this->Imagenes, 'http')
                ? $this->Imagenes
                : asset($this->Imagenes);
        }

        return asset('img/01.jpg');
    }

    /**
     * Costo estimado: suma cantidad × precio_unitario con conversión
     * de unidades (g↔kg, ml↔L) donde el ingrediente tiene
     * almacen_producto vinculado.
     * Devuelve ['total' => float, 'completo' => bool, 'lineas' => [...]].
     */
    public function costoEstimado(): array
    {
        $total = 0.0;
        $completo = true;
        $lineas = [];

        $ingredientes = $this->relationLoaded('ingredientes')
            ? $this->ingredientes
            : $this->ingredientes()->with('almacenProducto')->get();

        foreach ($ingredientes as $ing) {
            // Prefiere columnas nuevas; cae al texto legacy.
            $cantidadNum = $ing->pivot->cantidad_num !== null
                ? (float) $ing->pivot->cantidad_num
                : self::parseCantidad($ing->pivot->cantidad ?? null);
            $unidadReceta = $ing->pivot->unidad ?? null;
            $precio = $ing->almacenProducto->precio_unitario ?? null;
            $unidadProd = $ing->almacenProducto->unidad ?? null;
            $subtotal = null;

            if ($cantidadNum !== null && $precio !== null) {
                $factor = self::factorConversion($unidadReceta, $unidadProd);
                if ($factor !== null) {
                    $subtotal = round($cantidadNum * $factor * (float) $precio, 2);
                    $total += $subtotal;
                } else {
                    $completo = false;
                }
            } else {
                $completo = false;
            }

            $lineas[] = [
                'ingrediente' => $ing->Nombre,
                'cantidad' => $ing->pivot->cantidad ?? null,
                'cantidad_num' => $cantidadNum,
                'unidad_receta' => $unidadReceta,
                'precio' => $precio !== null ? (float) $precio : null,
                'unidad' => $unidadProd,
                'subtotal' => $subtotal,
            ];
        }

        return ['total' => round($total, 2), 'completo' => $completo, 'lineas' => $lineas];
    }

    /**
     * Factor para convertir cantidad en $unidadReceta a $unidadProd.
     * Null si son incompatibles (ej. taza vs kg) o desconocidas.
     */
    public static function factorConversion(?string $unidadReceta, ?string $unidadProd): ?float
    {
        $r = $unidadReceta ? strtolower($unidadReceta) : null;
        $p = $unidadProd ? strtolower($unidadProd) : null;

        // Sin unidad en receta: se asume misma unidad del producto.
        if ($r === null || $r === '') {
            return 1.0;
        }
        if ($p === null || $p === '') {
            return null;
        }
        if ($r === $p) {
            return 1.0;
        }

        $peso = ['g' => 1.0, 'kg' => 1000.0];
        $vol = ['ml' => 1.0, 'l' => 1000.0];

        if (isset($peso[$r]) && isset($peso[$p])) {
            return $peso[$r] / $peso[$p];
        }
        if (isset($vol[$r]) && isset($vol[$p])) {
            return $vol[$r] / $vol[$p];
        }

        return null;
    }

    /** Unidades ofrecibles según la unidad del producto vinculado. */
    public static function unidadesCompatibles(?string $unidadProd): array
    {
        $u = $unidadProd ? strtolower($unidadProd) : null;

        return match ($u) {
            'kg', 'g' => ['g', 'kg'],
            'l', 'ml' => ['ml', 'L'],
            default => $unidadProd ? [$unidadProd] : [],
        };
    }

    /** Extrae el número líder de "2", "1.5 tazas", "1/2 kg". Null si no hay. */
    public static function parseCantidad(?string $cantidad): ?float
    {
        if ($cantidad === null || trim($cantidad) === '') {
            return null;
        }

        $cantidad = trim($cantidad);

        if (preg_match('/^(\d+)\s*\/\s*(\d+)/', $cantidad, $m)) {
            return ((float) $m[2]) != 0 ? ((float) $m[1] / (float) $m[2]) : null;
        }

        if (preg_match('/^(\d+(?:[.,]\d+)?)/', $cantidad, $m)) {
            return (float) str_replace(',', '.', $m[1]);
        }

        return null;
    }

    /**
     * Margen estimado: precio_platillo − gasto total.
     * Devuelve ['margen' => ?float, 'porcentaje' => ?float].
     */
    public function margenEstimado(): array
    {
        if ($this->precio_platillo === null) {
            return ['margen' => null, 'porcentaje' => null];
        }

        $gasto = $this->costoEstimado()['total'];
        $margen = round((float) $this->precio_platillo - $gasto, 2);
        $porcentaje = ((float) $this->precio_platillo) > 0
            ? round($margen / (float) $this->precio_platillo * 100, 1)
            : null;

        return ['margen' => $margen, 'porcentaje' => $porcentaje];
    }

    /** Procedimiento partido en pasos (uno por línea no vacía). */
    public function pasos(): array
    {
        return array_values(array_filter(
            array_map('trim', preg_split('/\r\n|\r|\n/', (string) ($this->Procedimiento ?? ''))),
            fn ($p) => $p !== ''
        ));
    }
}
