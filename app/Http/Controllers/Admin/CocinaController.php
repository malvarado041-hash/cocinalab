<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ComandaItem;
use Illuminate\Support\Facades\Auth;

/**
 * KDS: pantalla de cocina con los platillos pendientes y en preparación,
 * agrupados por cuenta. Cocina, capitán, admin y sistemas.
 */
class CocinaController extends Controller
{
    private function exigirCocina(): void
    {
        $u = Auth::user();

        if (!$u->isPrivileged() && !$u->hasRole('cocinero') && !$u->hasRole('capitan')) {
            abort(403, 'No tienes permisos para la pantalla de cocina.');
        }
    }

    public function index()
    {
        $this->exigirCocina();

        $items = ComandaItem::with(['comanda.mesero', 'receta'])
            ->whereIn('estado', ['pendiente', 'en_preparacion'])
            ->whereHas('comanda', fn ($q) => $q->where('estado', 'en_cocina'))
            ->orderBy('created_at')
            ->get()
            ->groupBy('comanda_id');

        return view('admin.cocina.index', compact('items'));
    }

    /** Avanza pendiente → en preparación → listo y recalcula la cuenta. */
    public function avanzar(\Illuminate\Http\Request $request, ComandaItem $item)
    {
        $this->exigirCocina();
        $data = $request->validate(['estado_actual'=>'required|in:pendiente,en_preparacion']);
        \Illuminate\Support\Facades\DB::transaction(function () use ($item, $data) {
            $cuenta = \App\Models\Comanda::lockForUpdate()->findOrFail($item->comanda_id);
            $item = ComandaItem::lockForUpdate()->findOrFail($item->id);
            if ($item->estado !== $data['estado_actual']) return;
            abort_unless($cuenta->estado === 'en_cocina', 422, 'La cuenta no está en cocina.');
            $item->estado = ComandaItem::SIGUIENTE[$item->estado];
            if ($item->estado === 'listo') {
                app(\App\Services\ConsumoInventario::class)->descontar($item);
                $item->listo_at = now();
            } else { $item->iniciado_at = now(); }
            $item->save();
            \App\Models\EventoOperacion::create(['user_id'=>Auth::id(),'comanda_id'=>$cuenta->id,'item_id'=>$item->id,'accion'=>$item->estado]);
            $cuenta->actualizarEstadoDesdeItems();
        });
        return back()->with('success', 'Platillo actualizado.');
    }
}
