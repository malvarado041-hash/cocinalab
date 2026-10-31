<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ajuste;
use App\Models\AlmacenProducto;
use App\Models\LoteInventario;
use App\Models\MovimientoInventario;
use App\Models\Proveedor;
use App\Services\LotesInventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovimientoController extends Controller
{
    public function index(Request $r)
    {
        $r->validate(['producto_id' => 'nullable|integer|exists:almacen_productos,id']);

        return view('admin.movimientos', [
            'productos' => AlmacenProducto::orderBy('nombre')->get(), 'proveedores' => Proveedor::orderBy('nombre')->get(),
            'movimientos' => MovimientoInventario::when($r->producto_id, fn ($q) => $q->where('almacen_producto_id', $r->producto_id))->latest('id')->paginate(30)->withQueryString(),
            'lotes' => LoteInventario::with('producto')->where('disponible', '>', 0)->orderByRaw('caducidad IS NULL')->orderBy('caducidad')->get(),
            'comprar' => AlmacenProducto::bajoStock()->get(),
        ]);
    }

    public function store(Request $r)
    {
        $d = $r->validate(['almacen_producto_id' => 'required|integer|exists:almacen_productos,id', 'tipo' => 'required|in:entrada,ajuste,merma', 'cantidad' => 'required|numeric|gt:0|max:9999999', 'sentido' => 'required|in:sumar,restar', 'motivo' => 'required|string|min:5|max:255', 'proveedor_id' => 'nullable|integer|exists:proveedores,id', 'precio_unitario' => 'nullable|numeric|min:0|max:9999999', 'lote' => 'nullable|string|max:100', 'caducidad' => 'nullable|date|after_or_equal:'.Ajuste::hoy()]);
        if ($d['tipo'] === 'ajuste') {
            abort_unless(auth()->user()->isPrivileged(), 403);
        }
        if (! empty($d['caducidad']) && empty($d['lote'])) {
            return back()->withErrors(['lote' => 'Escribe el código del lote para registrar su caducidad.'])->withInput();
        }
        DB::transaction(function () use ($d) {
            $p = AlmacenProducto::lockForUpdate()->findOrFail($d['almacen_producto_id']);
            $delta = round($d['cantidad'] * ($d['tipo'] === 'merma' || ($d['tipo'] === 'ajuste' && $d['sentido'] === 'restar') ? -1 : 1), 6);
            abort_if($p->stock + $delta < 0, 422, 'El movimiento dejaría stock negativo.');
            $precio = $p->precio_unitario;
            $m = MovimientoInventario::create(['almacen_producto_id' => $p->id, 'user_id' => auth()->id(), 'tipo' => $d['tipo'], 'cantidad' => $delta, 'stock_resultante' => $p->stock + $delta, 'costo_unitario' => $d['tipo'] === 'entrada' ? ($d['precio_unitario'] ?? $precio ?? 0) : ($precio ?? 0), 'precio_anterior' => $precio, 'proveedor_id' => $d['proveedor_id'] ?? null, 'motivo' => $d['motivo']]);
            if ($delta < 0) {
                app(LotesInventario::class)->retirar($p, -$delta, $m, true);
            } elseif (! empty($d['lote'])) {
                $l = LoteInventario::firstOrNew(['almacen_producto_id' => $p->id, 'codigo' => $d['lote']]);
                if ($l->exists && $l->caducidad?->toDateString() !== ($d['caducidad'] ?? null)) {
                    abort(422, 'El lote existente tiene otra caducidad.');
                }
                $l->caducidad = $d['caducidad'] ?? null;
                $l->disponible = ($l->disponible ?? 0) + $delta;
                $l->save();
                DB::table('movimiento_lotes')->insert(['movimiento_id' => $m->id, 'lote_id' => $l->id, 'cantidad' => $delta]);
            }
            $p->stock += $delta;
            if ($d['tipo'] === 'entrada' && isset($d['precio_unitario'])) {
                $p->precio_unitario = $d['precio_unitario'];
            }
            $p->save();
        });

        return back()->with('success', 'Movimiento registrado.');
    }
}
