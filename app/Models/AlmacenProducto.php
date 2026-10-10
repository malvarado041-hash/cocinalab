<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlmacenProducto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'almacen_productos';

    protected $fillable = [
        'nombre',
        'categoria',
        'subcategoria',
        'unidad',
        'stock',
        'stock_minimo',
        'precio_unitario',
        'perecedero',
        'fecha_caducidad',
        'proveedor',
    ];

    protected $casts = [
        'stock' => 'decimal:2',
        'stock_minimo' => 'decimal:2',
        'precio_unitario' => 'decimal:2',
        'perecedero' => 'boolean',
        'fecha_caducidad' => 'date',
    ];

    public const CATEGORIAS = [
        'ingrediente' => 'Ingredientes',
        'abarrote' => 'Abarrotes',
        'bebida' => 'Bebidas',
        'pan_tortilla' => 'Pan y Tortilla',
        'insumo' => 'Insumos',
        'limpieza' => 'Limpieza',
    ];

    /** Categoría => subcategoría => unidades permitidas. Fuente única (backend + JS). */
    public const MAPA = [
        'ingrediente' => [
            'verdura' => ['kg', 'g', 'pieza', 'manojo'],
            'fruta' => ['kg', 'g', 'pieza'],
            'proteina_fresca' => ['kg', 'g', 'pieza'],
            'lacteo_fresco' => ['L', 'ml', 'pieza'],
            'hierba' => ['g', 'manojo', 'pieza'],
        ],
        'abarrote' => [
            'grano_cereal' => ['kg', 'g', 'costal', 'paquete'],
            'harina' => ['kg', 'g', 'costal', 'paquete'],
            'especia' => ['g', 'kg', 'frasco', 'sobre'],
            'aceite_vinagre' => ['L', 'ml', 'botella'],
            'enlatado' => ['pieza', 'lata', 'caja'],
            'salsa_condimento' => ['L', 'ml', 'botella', 'pieza'],
            'dulce' => ['kg', 'g', 'pieza', 'paquete'],
        ],
        'bebida' => [
            'refresco' => ['pieza', 'botella', 'caja', 'lata'],
            'agua' => ['pieza', 'botella', 'garrafon'],
            'jugo' => ['L', 'ml', 'pieza', 'caja'],
            'cafe_te' => ['kg', 'g', 'pieza', 'caja'],
            'cerveza_licor' => ['pieza', 'botella', 'caja'],
        ],
        'pan_tortilla' => [
            'pan_dulce' => ['pieza', 'paquete', 'caja'],
            'tortilla' => ['kg', 'paquete'],
            'totopo_tostada' => ['kg', 'paquete', 'pieza'],
        ],
        'insumo' => [
            'desechable' => ['pieza', 'paquete', 'caja'],
            'empaque' => ['pieza', 'paquete', 'rollo'],
            'papel' => ['pieza', 'paquete', 'rollo'],
        ],
        'limpieza' => [
            'quimico' => ['L', 'ml', 'botella', 'garrafon'],
            'utensilio_limpieza' => ['pieza', 'paquete'],
            'higiene' => ['pieza', 'paquete', 'caja'],
        ],
    ];

    /** Unidad (clave BD) => [singular, plural] para mostrar nombre completo. */
    public const UNIDADES = [
        'pieza' => ['Pieza', 'Piezas'],
        'kg' => ['Kilogramo', 'Kilogramos'],
        'g' => ['Gramo', 'Gramos'],
        'L' => ['Litro', 'Litros'],
        'ml' => ['Mililitro', 'Mililitros'],
        'paquete' => ['Paquete', 'Paquetes'],
        'caja' => ['Caja', 'Cajas'],
        'rollo' => ['Rollo', 'Rollos'],
        'botella' => ['Botella', 'Botellas'],
        'lata' => ['Lata', 'Latas'],
        'garrafon' => ['Garrafón', 'Garrafones'],
        'manojo' => ['Manojo', 'Manojos'],
        'frasco' => ['Frasco', 'Frascos'],
        'sobre' => ['Sobre', 'Sobres'],
        'costal' => ['Costal', 'Costales'],
    ];

    /** Nombre completo de la unidad, en plural si la cantidad != 1. */
    public static function unidadLabel(string $unidad, $cantidad = null): string
    {
        $nombres = self::UNIDADES[$unidad] ?? [ucfirst($unidad), ucfirst($unidad) . 's'];

        if ($cantidad === null) {
            return $nombres[0];
        }

        return (float) $cantidad === 1.0 ? $nombres[0] : $nombres[1];
    }

    /** Subcategorías perecederas por defecto (sugiere el checkbox en el form). */
    public const PERECEDERO_DEFAULT = [
        'ingrediente', 'pan_tortilla',
    ];

    public static function subcategoriasPara(string $categoria): array
    {
        return array_keys(self::MAPA[$categoria] ?? []);
    }

    public static function unidadesPara(string $categoria, string $subcategoria): array
    {
        return self::MAPA[$categoria][$subcategoria] ?? [];
    }

    public static function todasUnidades(): array
    {
        $todas = [];
        foreach (self::MAPA as $subs) {
            foreach ($subs as $unidades) {
                foreach ($unidades as $u) {
                    $todas[$u] = true;
                }
            }
        }

        return array_keys($todas);
    }

    public function esBajoStock(): bool
    {
        return (float) $this->stock_minimo > 0 && (float) $this->stock < (float) $this->stock_minimo;
    }

    public function scopeBajoStock($query)
    {
        return $query->where('stock_minimo', '>', 0)->whereColumn('stock', '<', 'stock_minimo');
    }

    public function scopePorCategoria($query, ?string $categoria)
    {
        if ($categoria) {
            $query->where('categoria', $categoria);
        }

        return $query;
    }

    public function getCategoriaLabelAttribute(): string
    {
        return self::CATEGORIAS[$this->categoria] ?? ucfirst($this->categoria);
    }

    public function ingredientes()
    {
        return $this->hasMany(\App\Models\Ingrediente::class, 'almacen_producto_id');
    }
}
