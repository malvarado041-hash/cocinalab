<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ajustes', function (Blueprint $t) {
            $t->string('clave')->primary();
            $t->text('valor');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ajustes');
    }
};
