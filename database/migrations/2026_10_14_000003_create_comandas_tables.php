<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cada cuenta es una "mesa" (incluidas las de llevar): no hay
        // catálogo de mesas fijas, la comanda se identifica por etiqueta.
        Schema::create('comandas', function (Blueprint $table) {
            $table->id();
            $table->string('mesa', 100);
            $table->enum('tipo', ['mesa', 'llevar'])->default('mesa');
            $table->foreignId('mesero_id')->constrained('users')->restrictOnDelete();
            $table->enum('estado', ['abierta', 'en_cocina', 'lista', 'servida', 'cancelada'])->default('abierta');
            $table->text('notas')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['estado', 'tipo']);
        });

        Schema::create('comanda_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comanda_id')->constrained('comandas')->cascadeOnDelete();
            $table->foreignId('receta_id')->constrained('recetas')->restrictOnDelete();
            $table->unsignedInteger('cantidad')->default(1);
            $table->decimal('precio_unitario', 10, 2);
            $table->text('notas')->nullable();
            $table->enum('estado', ['pendiente', 'en_preparacion', 'listo', 'servido', 'cancelado'])->default('pendiente');
            $table->timestamps();

            $table->index(['comanda_id', 'estado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comanda_items');
        Schema::dropIfExists('comandas');
    }
};
