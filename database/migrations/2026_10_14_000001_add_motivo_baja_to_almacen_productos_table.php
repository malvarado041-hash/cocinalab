<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('almacen_productos', function (Blueprint $table) {
            $table->text('motivo_baja')->nullable()->after('proveedor');
        });
    }

    public function down(): void
    {
        Schema::table('almacen_productos', function (Blueprint $table) {
            $table->dropColumn('motivo_baja');
        });
    }
};
