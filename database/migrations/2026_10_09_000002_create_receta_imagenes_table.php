<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receta_imagenes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receta_id')->constrained('recetas')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('path', 255);
            $table->unsignedInteger('orden')->default(0);
            $table->timestamps();
            $table->index(['receta_id', 'orden']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receta_imagenes');
    }
};
