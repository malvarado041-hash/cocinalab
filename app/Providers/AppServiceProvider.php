<?php

namespace App\Providers;

use App\Models\AlmacenProducto;
use App\Observers\AlmacenProductoObserver;
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
    }
}
