<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cobro;
use App\Models\Comanda;
use App\Models\ComandaItem;
use App\Models\EventoOperacion;
use App\Models\Mesa;
use App\Models\MovimientoInventario;
use App\Models\Receta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/**
 * Cuentas del restaurante (cada cuenta es una "mesa", incluidas las de llevar).
 * Mesero, capitán, admin y sistemas operan; cajero y cocinero solo consultan
 * (cobro en Fase 2, cocina en el KDS). Solo sistemas restaura y elimina.
 */
class ComandaController extends Controller
{
    /** Roles que pueden operar comandas (crear, editar, cambiar estados). */
    private function puedeOperar(): bool
    {
        $u = Auth::user();

        return $u->isPrivileged() || $u->hasRole('mesero') || $u->hasRole('capitan');
    }

    private function exigirOperar(): void
    {
        if (! $this->puedeOperar()) {
            abort(403, 'No tienes permisos para operar comandas.');
        }
    }

    public function index(Request $request)
    {
        $request->validate([
            'estado' => ['nullable', Rule::in(array_keys(Comanda::ESTADOS))],
            'tipo' => ['nullable', Rule::in(array_keys(Comanda::TIPOS))],
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $query = Comanda::query()
            ->with(['mesero', 'items'])
            ->withCount('items')
            ->porEstado($request->estado);

        // El mesero solo ve sus propias cuentas.
        if (Auth::user()->hasRole('mesero')) {
            $query->where('mesero_id', Auth::id());
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('q')) {
            $query->where('mesa', 'like', '%'.$request->q.'%');
        }

        $comandas = $query->orderByRaw("FIELD(estado, 'abierta', 'en_cocina', 'lista', 'servida', 'cancelada')")
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.comandas.index', [
            'comandas' => $comandas,
            'estados' => Comanda::ESTADOS,
            'tipos' => Comanda::TIPOS,
            'filtros' => $request->only(['estado', 'tipo', 'q']),
            'puedeOperar' => $this->puedeOperar(),
        ]);
    }

    public function show(Comanda $comanda)
    {
        $this->exigirVer($comanda);

        $comanda->load(['mesero', 'items.receta']);

        return view('admin.comandas.show', [
            'comanda' => $comanda,
            'puedeOperar' => $this->puedeOperar(),
        ]);
    }

    public function create()
    {
        $this->exigirOperar();

        return view('admin.comandas.create', [
            'tipos' => Comanda::TIPOS,
            'platillos' => $this->platillos(),
        ]);
    }

    public function store(Request $request)
    {
        $this->exigirOperar();

        $data = $this->validated($request);

        $comanda = DB::transaction(function () use ($data) {
            if (! empty($data['mesa_id'])) {
                $mesa = Mesa::lockForUpdate()->where('activa', true)->findOrFail($data['mesa_id']);
                abort_if(Comanda::where('mesa_id', $mesa->id)->whereNull('cerrada_at')->where('estado', '!=', 'cancelada')->exists(), 422, 'La mesa está ocupada.');
                abort_if(($data['comensales'] ?? 1) > $mesa->capacidad, 422, 'Se excede la capacidad de la mesa.');
                abort_unless($data['tipo'] === 'mesa', 422, 'Una cuenta para llevar no puede ocupar una mesa.');
                $data['mesa'] = $mesa->nombre;
            }
            $comanda = Comanda::create([
                'mesa' => $data['mesa'] ?? 'Cuenta',
                'mesa_id' => $data['mesa_id'] ?? null,
                'comensales' => $data['comensales'] ?? 1,
                'tipo' => $data['tipo'],
                'mesero_id' => Auth::id(),
                'notas' => $data['notas'] ?? null,
            ]);

            $this->sincronizarItems($comanda, $data['items']);

            return $comanda;
        });

        return redirect()->route('admin.comandas.show', $comanda)
            ->with('success', 'Cuenta abierta correctamente.');
    }

    public function edit(Comanda $comanda)
    {
        $this->exigirVer($comanda);
        $this->exigirOperar();
        $this->exigirEditable($comanda);

        return view('admin.comandas.edit', [
            'comanda' => $comanda,
            'tipos' => Comanda::TIPOS,
        ]);
    }

    public function update(Request $request, Comanda $comanda)
    {
        $this->exigirVer($comanda);
        $this->exigirOperar();
        $this->exigirEditable($comanda);

        $data = $request->validate([
            'mesa_id' => ['nullable', 'integer', 'exists:mesas,id'],
            'comensales' => ['required', 'integer', 'min:1', 'max:100'],
            'mesa' => ['required_without:mesa_id', 'nullable', 'string', 'max:100'],
            'tipo' => ['required', Rule::in(array_keys(Comanda::TIPOS))],
            'notas' => ['nullable', 'string', 'max:500'],
        ]);

        $this->conCuentaBloqueada($comanda, function ($cuenta) use ($data) {
            $this->exigirEditable($cuenta);
            unset($data['mesa_id']);
            if ($cuenta->mesa_id) {
                $data['mesa'] = $cuenta->mesa;
                $data['tipo'] = 'mesa';
            }
            $cuenta->update($data);
        });

        return redirect()->route('admin.comandas.show', $comanda)
            ->with('success', 'Cuenta actualizada correctamente.');
    }

    /** Agrega un platillo a una cuenta abierta. */
    public function addItem(Request $request, Comanda $comanda)
    {
        $this->exigirVer($comanda);
        $this->exigirOperar();

        if (! $comanda->esAbierta()) {
            abort(422, 'Solo se pueden agregar platillos a una cuenta abierta.');
        }

        $data = $request->validate([
            'receta_id' => ['required', 'exists:recetas,id'],
            'cantidad' => ['required', 'integer', 'min:1', 'max:99'],
            'notas' => ['nullable', 'string', 'max:255'],
        ]);

        $receta = Receta::findOrFail($data['receta_id']);
        $this->exigirConPrecio($receta);

        $this->conCuentaBloqueada($comanda, function ($comanda) use ($receta, $data) {
            abort_unless($comanda->esAbierta(), 422, 'Solo se agregan platillos a cuentas abiertas.');
            $comanda->items()->create([
                'receta_id' => $receta->id,
                'cantidad' => $data['cantidad'],
                'precio_unitario' => $receta->precio_platillo,
                'notas' => $data['notas'] ?? null,
            ]);

        });

        return back()->with('success', 'Platillo agregado a la cuenta.');
    }

    /** Quita un platillo pendiente de una cuenta abierta. */
    public function removeItem(Comanda $comanda, ComandaItem $item)
    {
        $this->exigirVer($comanda);
        $this->exigirOperar();

        if ($item->comanda_id !== $comanda->id) {
            abort(404);
        }

        if (! $comanda->esAbierta() || $item->estado !== 'pendiente') {
            abort(422, 'Solo se pueden quitar platillos pendientes de una cuenta abierta.');
        }

        $this->conCuentaBloqueada($comanda, function ($cuenta) use ($item) {
            $item = $cuenta->items()->lockForUpdate()->findOrFail($item->id);
            abort_unless($cuenta->esAbierta() && $item->estado === 'pendiente', 422, 'El platillo ya está en preparación.');
            $item->delete();
        });

        return back()->with('success', 'Platillo quitado de la cuenta.');
    }

    /** Abierta → en cocina (requiere al menos un platillo). */
    public function enviarCocina(Comanda $comanda)
    {
        $this->exigirVer($comanda);
        $this->exigirOperar();

        if (! $comanda->esAbierta()) {
            abort(422, 'La cuenta ya fue enviada a cocina.');
        }

        if ($comanda->items()->where('estado', '!=', 'cancelado')->count() === 0) {
            return back()->with('error', 'Agrega al menos un platillo antes de enviar a cocina.');
        }

        DB::transaction(function () use ($comanda) {
            $comanda = Comanda::lockForUpdate()->findOrFail($comanda->id);
            abort_unless($comanda->esAbierta(), 422, 'La cuenta ya fue enviada.');
            $this->exigirVer($comanda);
            abort_unless($comanda->items()->where('estado', '!=', 'cancelado')->exists(), 422, 'Agrega al menos un platillo.');
            $comanda->estado = 'en_cocina';
            $comanda->enviada_at = now();
            $comanda->save();
            EventoOperacion::create(['user_id' => Auth::id(), 'comanda_id' => $comanda->id, 'accion' => 'enviada_cocina']);
        });

        return back()->with('success', 'Cuenta enviada a cocina.');
    }

    /** Lista → servida (marca sus platillos listos como servidos). */
    public function marcarServida(Comanda $comanda)
    {
        $this->exigirVer($comanda);
        $this->exigirOperar();

        if ($comanda->estado !== 'lista') {
            abort(422, 'Solo se puede servir una cuenta que esté lista.');
        }

        DB::transaction(function () use ($comanda) {
            $comanda = Comanda::lockForUpdate()->findOrFail($comanda->id);
            abort_unless($comanda->estado === 'lista', 422);
            $this->exigirVer($comanda);
            EventoOperacion::create(['user_id' => Auth::id(), 'comanda_id' => $comanda->id, 'accion' => 'servida']);
            $comanda->items()->where('estado', 'listo')->update(['estado' => 'servido']);
            $comanda->estado = 'servida';
            $comanda->save();
        });

        return back()->with('success', 'Cuenta marcada como servida.');
    }

    /** Cancela la cuenta y sus platillos activos. */
    public function cancelar(Comanda $comanda)
    {
        $this->exigirVer($comanda);
        $this->exigirOperar();

        if (in_array($comanda->estado, ['servida', 'cancelada'], true)) {
            abort(422, 'Esta cuenta ya no se puede cancelar.');
        }

        DB::transaction(function () use ($comanda) {
            $comanda = Comanda::lockForUpdate()->findOrFail($comanda->id);
            abort_if(in_array($comanda->estado, ['servida', 'cancelada']), 422);
            $this->exigirVer($comanda);
            foreach ($comanda->items()->whereNotNull('consumido_at')->get() as $item) {
                MovimientoInventario::where('comanda_item_id', $item->id)->update(['tipo' => 'merma', 'motivo' => 'Cancelación de platillo preparado']);
            }
            EventoOperacion::create(['user_id' => Auth::id(), 'comanda_id' => $comanda->id, 'accion' => 'cancelada']);
            $comanda->items()->whereNotIn('estado', ['servido', 'cancelado'])
                ->update(['estado' => 'cancelado']);
            $comanda->estado = 'cancelada';
            $comanda->save();
        });

        return redirect()->route('admin.comandas.index')
            ->with('success', 'Cuenta cancelada correctamente.');
    }

    public function destroy(Comanda $comanda)
    {
        $this->exigirVer($comanda);
        $u = Auth::user();
        if (! $u->isPrivileged() && ! $u->hasRole('capitan')) {
            abort(403, 'Solo capitán, admin o sistemas pueden eliminar cuentas.');
        }

        abort_unless(in_array($comanda->estado, ['servida', 'cancelada']), 422, 'Cancela o termina la cuenta antes de darla de baja.');
        $comanda->delete();

        return redirect()->route('admin.comandas.index')
            ->with('success', 'Cuenta dada de baja correctamente.');
    }

    public function unir(Request $request, Comanda $comanda)
    {
        $this->exigirOperar();
        $this->exigirVer($comanda);
        $d = $request->validate(['destino_id' => 'required|integer|exists:comandas,id']);
        abort_if($comanda->id == (int) $d['destino_id'], 422);
        DB::transaction(function () use ($comanda, $d) {
            $cuentas = Comanda::whereIn('id', [$comanda->id, $d['destino_id']])->orderBy('id')->lockForUpdate()->get()->keyBy('id');
            $origen = $cuentas[$comanda->id];
            $destino = $cuentas[$d['destino_id']];
            $this->exigirVer($destino);
            abort_unless($origen->esAbierta() && $destino->esAbierta() && ! $origen->cerrada_at && ! $destino->cerrada_at, 422, 'Solo se unen cuentas abiertas.');
            $origen->items()->update(['comanda_id' => $destino->id]);
            $destino->comensales += $origen->comensales;
            $destino->save();
            $origen->update(['estado' => 'cancelada', 'cerrada_at' => now()]);
            EventoOperacion::create(['user_id' => Auth::id(), 'comanda_id' => $destino->id, 'accion' => 'cuentas_unidas', 'detalle' => (string) $origen->id]);
        });

        return redirect()->route('admin.comandas.show', $d['destino_id'])->with('success', 'Cuentas unidas.');
    }

    public function dividir(Request $request, Comanda $comanda)
    {
        $this->exigirOperar();
        $this->exigirVer($comanda);
        $d = $request->validate(['item_id' => 'required|integer', 'cantidad' => 'required|integer|min:1|max:99']);
        $nueva = DB::transaction(function () use ($comanda, $d) {
            $c = Comanda::lockForUpdate()->findOrFail($comanda->id);
            abort_unless($c->esAbierta() && ! $c->cerrada_at, 422, 'Solo se dividen cuentas abiertas.');
            $item = $c->items()->lockForUpdate()->findOrFail($d['item_id']);
            abort_unless($item->estado === 'pendiente' && $d['cantidad'] <= $item->cantidad, 422);
            abort_if($c->items()->count() === 1 && $item->cantidad === $d['cantidad'], 422, 'Debe quedar al menos un platillo en la cuenta original.');
            $n = Comanda::create(['mesa' => $c->mesa.' (dividida)', 'mesa_id' => $c->mesa_id, 'tipo' => $c->tipo, 'mesero_id' => $c->mesero_id, 'comensales' => 1]);
            $n->items()->create(['receta_id' => $item->receta_id, 'cantidad' => $d['cantidad'], 'precio_unitario' => $item->precio_unitario, 'notas' => $item->notas]);
            if ($d['cantidad'] === $item->cantidad) {
                $item->delete();
            } else {
                $item->cantidad -= $d['cantidad'];
                $item->save();
            }
            EventoOperacion::create(['user_id' => Auth::id(), 'comanda_id' => $c->id, 'accion' => 'cuenta_dividida', 'detalle' => (string) $n->id]);

            return $n;
        });

        return redirect()->route('admin.comandas.show', $nueva)->with('success', 'Cuenta dividida.');
    }

    public function trashed()
    {
        if (! Auth::user()->isSistemas()) {
            abort(403, 'Solo sistemas puede ver las cuentas dadas de baja.');
        }

        $comandas = Comanda::onlyTrashed()->with('mesero')->latest()->paginate(10);

        return view('admin.comandas.trashed', compact('comandas'));
    }

    public function restore($id)
    {
        if (! Auth::user()->isSistemas()) {
            abort(403, 'Solo sistemas puede restaurar cuentas.');
        }

        Comanda::onlyTrashed()->findOrFail($id)->restore();

        return redirect()->route('admin.comandas.trashed')
            ->with('success', 'Cuenta restaurada correctamente.');
    }

    public function forceDestroy($id)
    {
        if (! Auth::user()->isSistemas()) {
            abort(403, 'Solo sistemas puede eliminar cuentas definitivamente.');
        }

        $cuenta = Comanda::onlyTrashed()->findOrFail($id);
        abort_if(Cobro::where('comanda_id', $cuenta->id)->exists(), 422, 'Las cuentas con cobros se conservan para auditoría.');
        $cuenta->forceDelete();

        return redirect()->route('admin.comandas.trashed')
            ->with('success', 'Cuenta eliminada definitivamente de la base de datos.');
    }

    /** El mesero solo consulta sus cuentas; el resto ve todas. */
    private function conCuentaBloqueada(Comanda $cuenta, callable $operacion): void
    {
        DB::transaction(function () use ($cuenta, $operacion) {
            $cuenta = Comanda::lockForUpdate()->findOrFail($cuenta->id);
            $this->exigirVer($cuenta);
            $operacion($cuenta);
        });
    }

    private function exigirVer(Comanda $comanda): void
    {
        $u = Auth::user();

        if ($u->hasRole('mesero') && $comanda->mesero_id !== $u->id
            && ! $u->isPrivileged() && ! $u->hasRole('capitan')) {
            abort(403, 'Solo puedes ver tus propias cuentas.');
        }
    }

    private function exigirEditable(Comanda $comanda): void
    {
        if (in_array($comanda->estado, ['servida', 'cancelada'], true)) {
            abort(422, 'Esta cuenta ya no se puede editar.');
        }
    }

    /** Platillos vendibles: recetas con precio, no dadas de baja. */
    private function platillos()
    {
        return Receta::whereNotNull('precio_platillo')
            ->orderBy('Nombre')
            ->get(['id', 'Nombre', 'precio_platillo']);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'mesa_id' => ['nullable', 'integer', 'exists:mesas,id'],
            'comensales' => ['required', 'integer', 'min:1', 'max:100'],
            'mesa' => ['required_without:mesa_id', 'nullable', 'string', 'max:100'],
            'tipo' => ['required', Rule::in(array_keys(Comanda::TIPOS))],
            'notas' => ['nullable', 'string', 'max:500'],
            'items' => ['required', 'array', 'min:1', 'max:50'],
            'items.*.receta_id' => ['required', 'exists:recetas,id'],
            'items.*.cantidad' => ['required', 'integer', 'min:1', 'max:99'],
            'items.*.notas' => ['nullable', 'string', 'max:255'],
        ]);
    }

    private function sincronizarItems(Comanda $comanda, array $items): void
    {
        foreach ($items as $item) {
            $receta = Receta::findOrFail($item['receta_id']);
            $this->exigirConPrecio($receta);

            $comanda->items()->create([
                'receta_id' => $receta->id,
                'cantidad' => $item['cantidad'],
                'precio_unitario' => $receta->precio_platillo,
                'notas' => $item['notas'] ?? null,
            ]);
        }
    }

    private function exigirConPrecio(Receta $receta): void
    {
        if ($receta->precio_platillo === null) {
            abort(422, "«{$receta->Nombre}» no tiene precio de venta y no se puede pedir.");
        }
    }
}
