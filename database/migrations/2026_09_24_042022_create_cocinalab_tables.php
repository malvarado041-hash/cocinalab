<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Crea tablas
    public function up(): void
    {
        Schema::create('ingredientes', function (Blueprint $table) {
            $table->id();
            $table->string('Nombre', 200);
            $table->string('Tipo', 200)->nullable();
        });

        Schema::create('recetas', function (Blueprint $table) {
            $table->id();
            $table->string('Imagenes', 255)->nullable();
            $table->string('Nombre', 200);
            $table->text('Procedimiento')->nullable();
            $table->string('TipoC', 50)->nullable();
        });

        Schema::create('receta_ingrediente', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receta_id')->constrained('recetas')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('ingrediente_id')->constrained('ingredientes')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('cantidad', 100)->nullable();
        });

        Schema::create('pago', function (Blueprint $table) {
            $table->id();
            $table->string('Metodo', 200)->nullable();
            $table->timestamp('Fecha')->nullable()->useCurrent();
            $table->string('Tarjeta', 200)->nullable();
            $table->string('Vencimiento', 200)->nullable();
        });
    }

    // Revierte tablas
    public function down(): void
    {
        Schema::dropIfExists('receta_ingrediente');
        Schema::dropIfExists('recetas');
        Schema::dropIfExists('ingredientes');
        Schema::dropIfExists('pago');
    }
};
