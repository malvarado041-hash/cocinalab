<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['cocinero', 'mesero', 'capitan', 'almacen', 'admin'])->nullable()->after('email');
            $table->enum('status', ['pendiente', 'activo', 'inactivo'])->default('pendiente')->after('role');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete()->after('status');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['role', 'status', 'approved_by', 'approved_at']);
        });
    }
};
