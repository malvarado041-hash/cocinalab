<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ajuste;
use App\Models\Cobro;
use App\Models\ComandaItem;
use App\Models\MovimientoInventario;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class VentasController extends Controller
{
    public function index(Request $r)
    {
        $d = $r->validate(['desde' => 'nullable|date_format:Y-m-d', 'hasta' => 'nullable|date_format:Y-m-d|after_or_equal:desde']);
        $zona = Ajuste::valor('zona_horaria', 'America/Tijuana');
        $desde = Carbon::parse($d['desde'] ?? now($zona)->startOfMonth()->toDateString(), $zona)->startOfDay()->utc();
        $hasta = Carbon::parse($d['hasta'] ?? now($zona)->toDateString(), $zona)->endOfDay()->utc();
        $cobros = Cobro::whereBetween('created_at', [$desde, $hasta])->get();
        $dias = $cobros->groupBy(fn ($p) => $p->created_at->copy()->timezone($zona)->format('Y-m-d'))->map(fn ($g) => $g->sum('importe'));
        $metodos = $cobros->groupBy('metodo')->map(fn ($g) => $g->sum('importe'));
        $turnos = $cobros->groupBy('caja_id')->map(fn ($g) => $g->sum('importe'));
        $items = ComandaItem::with(['receta' => fn ($q) => $q->withTrashed()])->where('estado', '!=', 'cancelado')->whereIn('comanda_id', $cobros->pluck('comanda_id')->unique())->get();
        $costos = MovimientoInventario::whereIn('comanda_item_id', $items->pluck('id'))->get()->groupBy('comanda_item_id');
        $platillos = $items->groupBy('receta_id')->map(function ($g) use ($costos) {
            $completo = $g->every(fn ($i) => $i->consumido_at && isset($costos[$i->id]));
            $venta = $g->sum(fn ($i) => $i->subtotal);
            $costo = $g->sum(fn ($i) => isset($costos[$i->id]) ? $costos[$i->id]->sum(fn ($m) => abs($m->cantidad) * $m->costo_unitario) : 0);

            return ['nombre' => $g->first()->receta?->Nombre ?? 'Receta eliminada', 'cantidad' => $g->sum('cantidad'), 'venta' => $venta, 'costo' => $completo ? $costo : null, 'margen' => $completo ? $venta - $costo : null];
        })->sortByDesc('cantidad');
        $preparados = ComandaItem::whereBetween('listo_at', [$desde, $hasta])->whereNotNull('iniciado_at')->get();
        $minutos = $preparados->isEmpty() ? null : $preparados->avg(fn ($i) => $i->iniciado_at->diffInSeconds($i->listo_at) / 60);
        $movimientos = MovimientoInventario::whereBetween('created_at', [$desde, $hasta])->whereIn('tipo', ['consumo', 'merma'])->get();

        return view('admin.reportes.ventas', compact('dias', 'metodos', 'turnos', 'platillos', 'minutos', 'movimientos', 'cobros', 'desde', 'hasta', 'zona'));
    }
}
