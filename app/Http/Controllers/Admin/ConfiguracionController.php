<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ajuste;
use App\Models\Cobro;
use App\Models\EventoOperacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ConfiguracionController extends Controller
{
    public function index()
    {
        return view('admin.configuracion', ['ajustes' => Ajuste::pluck('valor', 'clave'), 'zonas' => \DateTimeZone::listIdentifiers()]);
    }

    public function update(Request $r)
    {
        $d = $r->validate(['nombre_restaurante' => 'required|string|max:100', 'direccion' => 'nullable|string|max:255', 'telefono' => 'nullable|string|max:40', 'horarios' => 'nullable|string|max:255', 'mensaje_ticket' => 'nullable|string|max:255', 'moneda' => 'required|in:MXN,USD', 'zona_horaria' => ['required', Rule::in(\DateTimeZone::listIdentifiers())]]);
        DB::transaction(function () use ($d) {
            if (Cobro::exists() && $d['moneda'] !== Ajuste::valor('moneda', 'MXN')) {
                throw ValidationException::withMessages(['moneda' => 'La moneda no puede cambiar después de registrar cobros.']);
            }
            foreach ($d as $k => $v) {
                Ajuste::updateOrCreate(['clave' => $k], ['valor' => $v ?? '']);
            }
            EventoOperacion::create(['user_id' => auth()->id(), 'accion' => 'configuracion_actualizada']);
        });

        return back()->with('success', 'Configuración guardada.');
    }

    public function auditoria()
    {
        return view('admin.auditoria', ['eventos' => EventoOperacion::latest('id')->paginate(40)]);
    }
}
