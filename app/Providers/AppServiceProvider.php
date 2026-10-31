<?php

namespace App\Providers;

use App\Models\AlmacenProducto;
use App\Models\Caja;
use App\Models\Comanda;
use App\Models\Mesa;
use App\Models\Proveedor;
use App\Models\Receta;
use App\Models\User;
use App\Observers\AlmacenProductoObserver;
use App\Observers\AuditoriaObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    // Registra servicios
    public function register(): void
    {
        //
    }

    // Arranca servicios
    public function boot(): void
    {
        AlmacenProducto::observe(AlmacenProductoObserver::class);
        foreach ([User::class, Receta::class, AlmacenProducto::class, Proveedor::class, Comanda::class, Caja::class, Mesa::class] as $modelo) {
            $modelo::observe(AuditoriaObserver::class);
        }
    }
}
