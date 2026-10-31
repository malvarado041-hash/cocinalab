<?php

namespace Tests\Feature;

use App\Models\AlmacenProducto;
use App\Models\Comanda;
use App\Models\Ingrediente;
use App\Models\Mesa;
use App\Models\Receta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RestauranteTest extends TestCase
{
    use RefreshDatabase;

    public function test_servicio_completo_con_consumo_cobro_y_reporte(): void
    {
        $u = User::factory()->create(['role' => 'admin', 'status' => 'activo', 'codigo_empleado' => '920001']);
        $this->actingAs($u);
        $p = AlmacenProducto::create(['nombre' => 'Pollo integración', 'categoria' => 'ingrediente', 'subcategoria' => 'proteina_fresca', 'unidad' => 'kg', 'stock' => 2, 'precio_unitario' => 100]);
        $ing = Ingrediente::where('almacen_producto_id', $p->id)->firstOrFail();
        $this->post(route('admin.recetas.store'), ['Nombre' => 'Pollo integrado', 'TipoC' => 'Comida', 'porciones' => 2, 'precio_platillo' => 80, 'Procedimiento' => 'Cocinar y servir caliente', 'ingredientes' => [['ingrediente_id' => $ing->id, 'cantidad_num' => 400, 'unidad' => 'g']]])->assertRedirect();
        $r = Receta::where('Nombre', 'Pollo integrado')->firstOrFail();
        $this->assertEquals(2, $p->fresh()->stock); // vincular y guardar nunca consume
        $this->assertEquals(20, $r->costoEstimado()['por_porcion']);
        $m = Mesa::create(['nombre' => 'Integración', 'capacidad' => 4]);
        $this->post(route('admin.comandas.store'), ['mesa_id' => $m->id, 'tipo' => 'mesa', 'comensales' => 3, 'items' => [['receta_id' => $r->id, 'cantidad' => 3, 'notas' => 'Sin sal']]])->assertRedirect();
        $c = Comanda::firstOrFail();
        $i = $c->items()->firstOrFail();
        $this->get(route('admin.cocina.index'))->assertDontSee('Pollo integrado');
        $this->put(route('admin.comandas.enviar-cocina', $c))->assertRedirect();
        $this->get(route('admin.cocina.index'))->assertSee('Pollo integrado')->assertSee('Sin sal');
        $this->put(route('admin.cocina.avanzar', $i), ['estado_actual' => 'pendiente'])->assertRedirect();
        $this->assertEquals(2, $p->fresh()->stock);
        $this->put(route('admin.cocina.avanzar', $i), ['estado_actual' => 'en_preparacion'])->assertRedirect();
        $this->assertEquals(1.4, (float) $p->fresh()->stock);
        $this->put(route('admin.comandas.marcar-servida', $c))->assertRedirect();
        $this->post(route('admin.caja.abrir'), ['fondo' => 100])->assertRedirect();
        $this->post(route('admin.caja.cobrar', $c), ['pagos' => [['metodo' => 'efectivo', 'importe' => 240, 'recibido' => 300]]])->assertRedirect();
        $this->get(route('admin.caja.ticket', $c))->assertOk()->assertSee('Pollo integrado')->assertSee('60.00');
        $this->get(route('admin.reportes.ventas'))->assertOk()->assertSee('Pollo integrado')->assertSee('$180.00');
        $this->assertNotNull($c->fresh()->cerrada_at);
    }
}
