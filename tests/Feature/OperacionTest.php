<?php
namespace Tests\Feature;
use App\Models\{User, AlmacenProducto, Ingrediente, Receta, Comanda, ComandaItem};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class OperacionTest extends TestCase {
 use RefreshDatabase;
 private function usuario($rol) { return User::factory()->create(['role'=>$rol,'status'=>'activo','codigo_empleado'=>(string)fake()->unique()->numberBetween(100000,999999)]); }
 private function escenario($stock=2): array {
  $user=$this->usuario('cocinero');
  $p=AlmacenProducto::create(['nombre'=>'Pollo prueba','categoria'=>'ingrediente','subcategoria'=>'proteina_fresca','unidad'=>'kg','stock'=>$stock,'precio_unitario'=>100]);
  $ing=Ingrediente::where('almacen_producto_id',$p->id)->firstOrFail();
  $r=Receta::create(['Nombre'=>'Pollo','TipoC'=>'Comida','Procedimiento'=>'Cocinar y servir','porciones'=>1,'precio_platillo'=>80]);
  $r->ingredientes()->attach($ing->id,['cantidad_num'=>200,'unidad'=>'g']);
  $c=Comanda::create(['mesa'=>'Prueba','tipo'=>'mesa','mesero_id'=>$user->id,'estado'=>'en_cocina']);
  $i=$c->items()->create(['receta_id'=>$r->id,'cantidad'=>3,'precio_unitario'=>80,'estado'=>'en_preparacion']);
  return [$user,$p,$c,$i];
 }
 public function test_listo_descuenta_una_sola_vez(): void {
  [$u,$p,$c,$i]=$this->escenario();
  $this->actingAs($u)->put(route("admin.cocina.avanzar",$i),["estado_actual"=>"en_preparacion"])->assertRedirect();
  $this->assertEquals(1.4,(float)$p->fresh()->stock);
  $this->actingAs($u)->put(route("admin.cocina.avanzar",$i),["estado_actual"=>"en_preparacion"])->assertRedirect();
  $this->assertEquals(1.4,(float)$p->fresh()->stock);
  $this->assertDatabaseCount("movimientos_inventario",1);
  $this->assertSame("lista",$c->fresh()->estado);
 }
 public function test_stock_insuficiente_no_avanza_ni_des_descuenta(): void {
  [$u,$p,$c,$i]=$this->escenario(0.1);
  $this->actingAs($u)->put(route("admin.cocina.avanzar",$i),["estado_actual"=>"en_preparacion"])->assertSessionHasErrors("inventario");
  $this->assertEquals(0.1,(float)$p->fresh()->stock);
  $this->assertSame("en_preparacion",$i->fresh()->estado);
  $this->assertDatabaseCount("movimientos_inventario",0);
 }
 public function test_mesero_no_puede_operar_cuenta_ajena(): void {
  [$u,$p,$c,$i]=$this->escenario();
  $this->actingAs($this->usuario("mesero"))->put(route("admin.comandas.cancelar",$c))->assertForbidden();
 }
}
