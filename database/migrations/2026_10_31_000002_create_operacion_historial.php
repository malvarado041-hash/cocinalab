<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::table('almacen_productos', fn (Blueprint $t) => $t->decimal('stock',14,6)->default(0)->change());
  if (!Schema::hasColumn('comanda_items','iniciado_at')) Schema::table('comanda_items', function(Blueprint $t) { $t->timestamp('iniciado_at')->nullable(); $t->timestamp('listo_at')->nullable(); $t->timestamp('consumido_at')->nullable(); });
  if (!Schema::hasColumn('comandas','enviada_at')) Schema::table('comandas', fn(Blueprint $t) => $t->timestamp('enviada_at')->nullable());
  if (!Schema::hasTable('movimientos_inventario')) Schema::create('movimientos_inventario', function(Blueprint $t) {
   $t->id(); $t->foreignId('almacen_producto_id')->constrained('almacen_productos')->restrictOnDelete();
   $t->foreignId('comanda_item_id')->nullable()->constrained('comanda_items')->nullOnDelete();
   $t->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
   $t->string('tipo',30); $t->decimal('cantidad',14,6); $t->decimal('stock_resultante',14,6); $t->decimal('costo_unitario',12,4)->default(0); $t->string('motivo'); $t->timestamps();
   $t->unique(['comanda_item_id','almacen_producto_id'], 'mov_item_producto_unique');
  });
  if (!Schema::hasIndex('movimientos_inventario','mov_item_producto_unique')) Schema::table('movimientos_inventario', fn(Blueprint $t) => $t->unique(['comanda_item_id','almacen_producto_id'],'mov_item_producto_unique'));
  Schema::create('eventos_operacion', function(Blueprint $t) {
   $t->id(); $t->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
   $t->unsignedBigInteger('comanda_id')->nullable()->index(); $t->unsignedBigInteger('item_id')->nullable();
   $t->string('accion'); $t->text('detalle')->nullable(); $t->timestamps();
  });
 }
 public function down(): void {
  Schema::dropIfExists('eventos_operacion'); Schema::dropIfExists('movimientos_inventario');
  Schema::table('comandas', fn(Blueprint $t) => $t->dropColumn('enviada_at'));
  Schema::table('comanda_items', fn(Blueprint $t) => $t->dropColumn(['iniciado_at','listo_at','consumido_at']));
 }
};
