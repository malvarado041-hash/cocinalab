<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlmacenProducto;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class ReporteController extends Controller
{
    public function inventario(Request $request)
    {
        $request->validate([
            'categoria' => ['nullable', Rule::in(array_keys(AlmacenProducto::CATEGORIAS))],
        ]);

        $base = $this->base($request);

        $totales = (clone $base)->selectRaw(
            'COUNT(*) AS productos,
             COALESCE(SUM(stock * precio_unitario), 0) AS valor_total,
             SUM(CASE WHEN precio_unitario IS NULL THEN 1 ELSE 0 END) AS sin_precio,
             SUM(CASE WHEN stock <= 0 THEN 1 ELSE 0 END) AS agotados'
        )->first();

        $bajoStock = (clone $base)->bajoStock()->selectRaw(
            'COUNT(*) AS productos,
             COALESCE(SUM((stock_minimo - stock) * COALESCE(precio_unitario, 0)), 0) AS reposicion,
             SUM(CASE WHEN precio_unitario IS NULL THEN 1 ELSE 0 END) AS sin_precio'
        )->first();

        $porCategoria = (clone $base)->selectRaw(
            "categoria,
             COUNT(*) AS productos,
             COALESCE(SUM(stock * precio_unitario), 0) AS valor,
             SUM(CASE WHEN stock_minimo > 0 AND stock < stock_minimo THEN 1 ELSE 0 END) AS bajos,
             SUM(CASE WHEN stock <= 0 THEN 1 ELSE 0 END) AS agotados,
             SUM(CASE WHEN precio_unitario IS NULL THEN 1 ELSE 0 END) AS sin_precio"
        )->groupBy('categoria')->orderBy('categoria')->get();

        $topValor = (clone $base)
            ->select('id', 'nombre', 'categoria', 'unidad', 'stock', 'stock_minimo', 'precio_unitario')
            ->selectRaw('(stock * COALESCE(precio_unitario, 0)) AS valor')
            ->orderByDesc('valor')
            ->orderBy('nombre')
            ->limit(10)
            ->get();

        $alertas = (clone $base)->bajoStock()
            ->select('id', 'nombre', 'categoria', 'unidad', 'stock', 'stock_minimo', 'precio_unitario')
            ->selectRaw(
                '(stock_minimo - stock) AS faltante,
                 (stock_minimo - stock) * COALESCE(precio_unitario, 0) AS costo_reposicion'
            )
            ->orderByDesc('costo_reposicion')
            ->orderBy('nombre')
            ->get();

        $hoy = now()->startOfDay();
        $caducidades = (clone $base)
            ->where('perecedero', true)
            ->whereNotNull('fecha_caducidad')
            ->select('id', 'nombre', 'categoria', 'unidad', 'stock', 'fecha_caducidad')
            ->orderBy('fecha_caducidad')
            ->get()
            ->each(function ($p) use ($hoy) {
                $fecha = Carbon::parse($p->fecha_caducidad);
                // Carbon 3 devuelve diffInDays con signo: se toma el valor absoluto
                // y la dirección se decide por la comparación de fechas.
                $dias = (int) abs($fecha->diffInDays($hoy));

                // Positivo = días restantes; negativo = días vencidos.
                $p->dias_restantes = $fecha->lte($hoy) ? -$dias : $dias;
            });

        $vencidos = $caducidades->filter(fn ($p) => $p->dias_restantes < 0)->count();
        $en7 = $caducidades->filter(fn ($p) => $p->dias_restantes >= 0 && $p->dias_restantes <= 7)->count();
        $en30 = $caducidades->filter(fn ($p) => $p->dias_restantes > 7 && $p->dias_restantes <= 30)->count();

        return view('admin.reportes.inventario', [
            'kpi' => [
                'productos' => (int) $totales->productos,
                'valorTotal' => (float) $totales->valor_total,
                'sinPrecio' => (int) ($totales->sin_precio ?? 0),
                'agotados' => (int) ($totales->agotados ?? 0),
                'bajoStock' => (int) $bajoStock->productos,
                'reposicion' => (float) $bajoStock->reposicion,
                'reposicionSinPrecio' => (int) ($bajoStock->sin_precio ?? 0),
                'vencidos' => $vencidos,
                'en7' => $en7,
                'en30' => $en30,
                'porVencer' => $vencidos + $en7 + $en30,
            ],
            'porCategoria' => $porCategoria,
            'topValor' => $topValor,
            'alertas' => $alertas,
            'caducidades' => $caducidades,
            'categorias' => AlmacenProducto::CATEGORIAS,
            'categoria' => $request->categoria,
            'generado' => now()->format('d/m/Y H:i'),
        ]);
    }

    public function inventarioCsv(Request $request)
    {
        $request->validate([
            'categoria' => ['nullable', Rule::in(array_keys(AlmacenProducto::CATEGORIAS))],
        ]);

        $base = $this->base($request);
        $nombre = 'reporte-inventario-' . ($request->categoria ?: 'todas') . '-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($base) {
            $out = fopen('php://output', 'w');

            // BOM UTF-8 para que Excel abra bien los acentos.
            fwrite($out, "\xEF\xBB\xBF");

            fputcsv($out, [
                'Producto', 'Categoría', 'Subcategoría', 'Unidad', 'Stock', 'Stock mínimo',
                'Precio unitario', 'Valor en stock', 'Perecedero', 'Caducidad', 'Estado',
            ]);

            $base->orderBy('categoria')->orderBy('nombre')->orderBy('id')->chunk(500, function ($productos) use ($out) {
                foreach ($productos as $p) {
                    fputcsv($out, [
                        $p->nombre,
                        $p->categoria_label,
                        str_replace('_', ' ', $p->subcategoria),
                        AlmacenProducto::unidadLabel($p->unidad),
                        $p->stock,
                        $p->stock_minimo,
                        $p->precio_unitario,
                        round((float) $p->stock * (float) ($p->precio_unitario ?? 0), 2),
                        $p->perecedero ? 'Sí' : 'No',
                        $p->fecha_caducidad?->format('d/m/Y'),
                        $this->estadoCsv($p),
                    ]);
                }
            });

            fclose($out);
        }, $nombre, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function estadoCsv(AlmacenProducto $p): string
    {
        $estados = [];

        if ((float) $p->stock <= 0) {
            $estados[] = 'Agotado';
        } elseif ($p->esBajoStock()) {
            $estados[] = 'Bajo stock';
        }

        if ($p->precio_unitario === null) {
            $estados[] = 'Sin precio';
        }

        if ($p->fecha_caducidad && $p->fecha_caducidad->lte(now())) {
            $estados[] = 'Vencido';
        }

        return $estados ? implode(' | ', $estados) : 'OK';
    }

    /** Alcance base del reporte: todos los productos no dados de baja (SoftDeletes), filtrados por categoría. */
    private function base(Request $request): Builder
    {
        return AlmacenProducto::query()->porCategoria($request->categoria);
    }
}
