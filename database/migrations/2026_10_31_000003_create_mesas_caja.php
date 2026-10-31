<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mesas', function (Blueprint $t) {
            $t->id();
            $t->string('nombre', 100)->unique();
            $t->unsignedInteger('capacidad')->default(4);
            $t->boolean('activa')->default(true);
            $t->timestamps();
        });
        Schema::table('comandas', function (Blueprint $t) {
            $t->foreignId('mesa_id')->nullable()->constrained('mesas')->restrictOnDelete();
            $t->unsignedInteger('comensales')->default(1);
            $t->timestamp('cerrada_at')->nullable()->index();
        });
        Schema::create('cajas', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $t->decimal('fondo', 12, 2);
            $t->decimal('efectivo_contado', 12, 2)->nullable();
            $t->decimal('efectivo_esperado', 12, 2)->nullable();
            $t->decimal('diferencia', 12, 2)->nullable();
            $t->timestamp('cerrada_at')->nullable();
            $t->timestamps();
        });
        Schema::create('cobros', function (Blueprint $t) {
            $t->id();
            $t->foreignId('comanda_id')->constrained('comandas')->restrictOnDelete();
            $t->foreignId('caja_id')->constrained('cajas')->restrictOnDelete();
            $t->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $t->string('metodo', 20);
            $t->decimal('importe', 12, 2);
            $t->decimal('recibido', 12, 2);
            $t->decimal('cambio', 12, 2)->default(0);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cobros');
        Schema::dropIfExists('cajas');
        Schema::table('comandas', function (Blueprint $t) {
            $t->dropConstrainedForeignId('mesa_id');
            $t->dropColumn(['comensales', 'cerrada_at']);
        });
        Schema::dropIfExists('mesas');
    }
};
