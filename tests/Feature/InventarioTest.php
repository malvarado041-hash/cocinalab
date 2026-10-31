<?php
namespace Tests\Feature;
use App\Models\{User,AlmacenProducto,LoteInventario,MovimientoInventario};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class InventarioTest extends TestCase {
 use RefreshDatabase;
 public function test_compra_registra_lote_precio_y_salida_por_caducidad(): void {
  $u=User::factory()->create(['role'=>'admin','status'=>'activo','codigo_empleado'=>'800001']);
  $p=AlmacenProducto::create(['nombre'=>'Leche prueba','categoria'=>'ingrediente','subcategoria'=>'lacteo_fresco','unidad'=>'L','stock'=>0,'precio_unitario'=>20]);
  $this->actingAs($u);
  foreach([['A',today()->addDays(10)->toDateString()],['B',today()->addDays(5)->toDateString()]] as [$l,$f]) {
   $this->post(route('admin.movimientos.store'),['almacen_producto_id'=>$p->id,'tipo'=>'entrada','sentido'=>'sumar','cantidad'=>2,'motivo'=>'Compra de prueba','precio_unitario'=>25,'lote'=>$l,'caducidad'=>$f])->assertRedirect();
  }
  $this->assertEquals(4,$p->fresh()->stock);
  $this->assertEquals(25,$p->fresh()->precio_unitario);
  $this->assertDatabaseHas('movimientos_inventario',['precio_anterior'=>20,'costo_unitario'=>25]);
  $this->post(route('admin.movimientos.store'),['almacen_producto_id'=>$p->id,'tipo'=>'merma','sentido'=>'restar','cantidad'=>1,'motivo'=>'Derrame de prueba'])->assertRedirect();
  $this->assertEquals(1,LoteInventario::where('codigo','B')->first()->disponible);
  $this->assertEquals(2,LoteInventario::where('codigo','A')->first()->disponible);
 }
 public function test_no_permite_consumir_lotes_vencidos_y_revierte_movimiento(): void {
  $p=AlmacenProducto::create(['nombre'=>'Vencido','categoria'=>'ingrediente','subcategoria'=>'proteina_fresca','unidad'=>'kg','stock'=>2]);
  LoteInventario::create(['almacen_producto_id'=>$p->id,'codigo'=>'V','caducidad'=>today()->subDay(),'disponible'=>2]);
  try {
   \Illuminate\Support\Facades\DB::transaction(function() use($p) {
    $m=MovimientoInventario::create(['almacen_producto_id'=>$p->id,'tipo'=>'consumo','cantidad'=>-1,'stock_resultante'=>1,'motivo'=>'Prueba']);
    app(\App\Services\LotesInventario::class)->retirar($p,1,$m);
   });
   $this->fail('Debía rechazar lote vencido');
  } catch(\Illuminate\Validation\ValidationException $e) { $this->assertArrayHasKey('inventario',$e->errors()); }
  $this->assertDatabaseCount('movimientos_inventario',0);
  $this->assertEquals(2,$p->fresh()->stock);
 }
}
