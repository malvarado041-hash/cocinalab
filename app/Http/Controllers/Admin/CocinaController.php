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
            ->whereHas('comanda', fn ($q) => $q->whereNotIn('estado', ['servida', 'cancelada']))
            ->orderBy('created_at')
            ->get()
            ->groupBy('comanda_id');

        return view('admin.cocina.index', compact('items'));
    }

    /** Avanza pendiente → en preparación → listo y recalcula la cuenta. */
    public function avanzar(ComandaItem $item)
    {
        $this->exigirCocina();

        if (!$item->puedeAvanzar()) {
            abort(422, 'Este platillo ya no puede avanzar.');
        }

        if (in_array($item->comanda->estado, ['servida', 'cancelada'], true)) {
            abort(422, 'La cuenta de este platillo ya está cerrada.');
        }

        $item->estado = ComandaItem::SIGUIENTE[$item->estado];
        $item->save();

        $item->comanda->actualizarEstadoDesdeItems();

        return back()->with('success', 'Platillo actualizado.');
    }
}
