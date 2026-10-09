<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('almacen_productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 200)->unique();
            $table->enum('categoria', ['ingrediente', 'abarrote', 'bebida', 'pan_tortilla', 'insumo', 'limpieza']);
            $table->string('subcategoria', 50);
            $table->enum('unidad', ['pieza', 'kg', 'g', 'L', 'ml', 'paquete', 'caja', 'rollo', 'botella', 'lata', 'garrafon', 'manojo', 'frasco', 'sobre', 'costal']);
            $table->decimal('stock', 10, 2)->default(0);
            $table->decimal('stock_minimo', 10, 2)->default(0);
            $table->decimal('precio_unitario', 10, 2)->nullable();
            $table->boolean('perecedero')->default(false);
            $table->date('fecha_caducidad')->nullable();
            $table->string('proveedor', 200)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['categoria', 'subcategoria']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('almacen_productos');
    }
};
