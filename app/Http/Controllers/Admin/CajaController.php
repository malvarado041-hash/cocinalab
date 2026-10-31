<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Caja;
use App\Models\Cobro;
use App\Models\Comanda;
use App\Models\User;
use App\Services\CajaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CajaController extends Controller
{
    public function index()
    {
        return view('admin.caja', ['caja' => Caja::where('user_id', auth()->id())->whereNull('cerrada_at')->first(), 'historial' => Caja::where('user_id', auth()->id())->latest()->take(20)->get(), 'cuentas' => Comanda::where('estado', 'servida')->whereNull('cerrada_at')->get()]);
    }

    public function abrir(Request $r)
    {
        $d = $r->validate(['fondo' => 'required|numeric|min:0|max:9999999.99']);
        DB::transaction(function () use ($d) {
            User::lockForUpdate()->findOrFail(auth()->id());
            abort_if(Caja::where('user_id', auth()->id())->whereNull('cerrada_at')->exists(), 422, 'Ya tienes una caja abierta.');
            Caja::create(['user_id' => auth()->id(), 'fondo' => $d['fondo']]);
        });

        return back()->with('success', 'Caja abierta.');
    }

    public function cerrar(Request $r, Caja $caja)
    {
        abort_unless($caja->user_id === auth()->id(), 403);
        $d = $r->validate(['efectivo_contado' => 'required|numeric|min:0|max:99999999.99']);
        DB::transaction(function () use ($caja, $d) {
            $c = Caja::lockForUpdate()->findOrFail($caja->id);
            abort_if($c->cerrada_at, 422);
            $esperado = round($c->fondo + Cobro::where('caja_id', $c->id)->where('metodo', 'efectivo')->sum('importe'), 2);
            $c->update(['efectivo_contado' => $d['efectivo_contado'], 'efectivo_esperado' => $esperado, 'diferencia' => round($d['efectivo_contado'] - $esperado, 2), 'cerrada_at' => now()]);
        });

        return back()->with('success', 'Caja cerrada. Se registró la diferencia.');
    }

    public function cobrar(Request $r, Comanda $comanda)
    {
        $d = $r->validate(['pagos' => 'required|array|min:1|max:2', 'pagos.*.metodo' => 'required|in:efectivo,tarjeta', 'pagos.*.importe' => 'required|numeric|gt:0|max:9999999.99', 'pagos.*.recibido' => 'nullable|numeric|min:0|max:9999999.99']);
        app(CajaService::class)->cobrar($comanda, $d['pagos']);

        return redirect()->route('admin.caja.ticket', $comanda)->with('success', 'Pago registrado.');
    }

    public function ticket(Comanda $comanda)
    {
        return view('admin.ticket', ['comanda' => $comanda->load('items.receta'), 'cobros' => Cobro::where('comanda_id',$comanda->id)->get()]);
    }
}
