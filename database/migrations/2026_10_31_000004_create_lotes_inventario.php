<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lotes_inventario', function (Blueprint $t) {
            $t->id();
            $t->foreignId('almacen_producto_id')->constrained('almacen_productos')->restrictOnDelete();
            $t->string('codigo', 100);
            $t->date('caducidad')->nullable()->index();
            $t->decimal('disponible', 14, 6);
            $t->timestamps();
            $t->unique(['almacen_producto_id', 'codigo'], 'lote_producto_codigo');
        });
        Schema::table('movimientos_inventario', function (Blueprint $t) {
            $t->foreignId('proveedor_id')->nullable()->constrained('proveedores')->restrictOnDelete();
            $t->decimal('precio_anterior', 12, 4)->nullable();
        });
        Schema::create('movimiento_lotes', function (Blueprint $t) {
            $t->id();
            $t->foreignId('movimiento_id')->constrained('movimientos_inventario')->restrictOnDelete();
            $t->foreignId('lote_id')->constrained('lotes_inventario')->restrictOnDelete();
            $t->decimal('cantidad', 14, 6);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimiento_lotes');
        Schema::table('movimientos_inventario', function (Blueprint $t) {
            $t->dropConstrainedForeignId('proveedor_id');
            $t->dropColumn('precio_anterior');
        });
        Schema::dropIfExists('lotes_inventario');
    }
};
