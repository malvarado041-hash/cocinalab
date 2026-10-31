<?php

namespace App\Services;

use App\Models\Caja;
use App\Models\Cobro;
use App\Models\Comanda;
use App\Models\EventoOperacion;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CajaService
{
    public function cobrar(Comanda $comanda, array $pagos): void
    {
        DB::transaction(function () use ($comanda, $pagos) {
            $c = Comanda::lockForUpdate()->findOrFail($comanda->id);
            if ($c->estado !== 'servida' || $c->cerrada_at) {
                throw ValidationException::withMessages(['pago' => 'Solo se puede cobrar una cuenta servida y pendiente de pago.']);
            }
            $caja = Caja::where('user_id', auth()->id())->whereNull('cerrada_at')->lockForUpdate()->first();
            if (! $caja) {
                throw ValidationException::withMessages(['pago' => 'Abre tu caja antes de cobrar.']);
            }
            $total = (int) round($c->total * 100);
            $suma = 0;
            foreach ($pagos as $p) {
                $suma += (int) round($p['importe'] * 100);
            }
            if ($total <= 0 || $suma !== $total) {
                throw ValidationException::withMessages(['pago' => 'Los importes deben cubrir exactamente el total de la cuenta.']);
            }
            foreach ($pagos as $p) {
                $importe = (int) round($p['importe'] * 100);
                $recibido = $p['metodo'] === 'efectivo' ? (int) round(($p['recibido'] ?? 0) * 100) : $importe;
                if ($importe <= 0 || $recibido < $importe) {
                    throw ValidationException::withMessages(['pago' => 'El efectivo recibido no cubre el importe.']);
                }
                Cobro::create(['comanda_id' => $c->id, 'caja_id' => $caja->id, 'user_id' => auth()->id(), 'metodo' => $p['metodo'], 'importe' => $importe / 100, 'recibido' => $recibido / 100, 'cambio' => ($recibido - $importe) / 100]);
            }
            $c->cerrada_at = now();
            $c->save();
            EventoOperacion::create(['user_id' => auth()->id(), 'comanda_id' => $c->id, 'accion' => 'cuenta_cobrada', 'detalle' => (string) ($total / 100)]);
        });
    }
}
