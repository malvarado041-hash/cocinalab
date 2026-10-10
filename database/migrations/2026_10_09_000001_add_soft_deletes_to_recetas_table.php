<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recetas', function (Blueprint $table) {
            $table->softDeletes()->after('TipoC');
        });
    }

    public function down(): void
    {
        Schema::table('recetas', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
