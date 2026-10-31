<?php

namespace App\Observers;

use App\Models\Comanda;
use App\Models\EventoOperacion;
use Illuminate\Database\Eloquent\Model;

class AuditoriaObserver
{
    private function registrar(Model $m, string $accion): void
    {
        if (! auth()->check()) {
            return;
        }
        // Registra nombres de campos, nunca contraseñas, tokens ni datos personales.
        $campos = array_diff(array_keys($m->getChanges()), ['password', 'remember_token', 'updated_at']);
        EventoOperacion::create(['user_id' => auth()->id(), 'comanda_id' => $m instanceof Comanda ? $m->id : null, 'accion' => class_basename($m).':'.$accion, 'detalle' => 'Registro #'.$m->getKey().' · Campos: '.implode(', ', $campos)]);
    }

    public function created(Model $m): void
    {
        $this->registrar($m, 'creado');
    }

    public function updated(Model $m): void
    {
        $this->registrar($m, 'actualizado');
    }

    public function deleted(Model $m): void
    {
        $this->registrar($m, 'baja');
    }

    public function restored(Model $m): void
    {
        $this->registrar($m, 'restaurado');
    }
}
