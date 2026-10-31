<?php

namespace Tests\Feature;

use App\Models\Comanda;
use App\Models\Mesa;
use App\Models\Receta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MesasTest extends TestCase
{
    use RefreshDatabase;

    private function escenario(): array
    {
        $u = User::factory()->create(['role' => 'mesero', 'status' => 'activo', 'codigo_empleado' => '910001']);
        $m = Mesa::create(['nombre' => 'Mesa prueba', 'capacidad' => 4]);
        $r = Receta::create(['Nombre' => 'Platillo', 'precio_platillo' => 50]);

        return [$u, $m, $r];
    }

    public function test_abrir_mesa_ocupada_se_rechaza(): void
    {
        [$u,$m,$r] = $this->escenario();
        $this->actingAs($u);
        $d = ['mesa_id' => $m->id, 'tipo' => 'mesa', 'comensales' => 2, 'items' => [['receta_id' => $r->id, 'cantidad' => 2]]];
        $this->post(route('admin.comandas.store'), $d)->assertRedirect();
        $this->assertDatabaseHas('comandas', ['mesa_id' => $m->id, 'mesa' => 'Mesa prueba']);
        $this->post(route('admin.comandas.store'), $d)->assertStatus(422);
        $this->assertDatabaseCount('comandas', 1);
    }

    public function test_dividir_y_unir_conserva_precio_y_cantidad(): void
    {
        [$u,$m,$r] = $this->escenario();
        $this->actingAs($u);
        $c = Comanda::create(['mesa_id' => $m->id, 'mesa' => $m->nombre, 'tipo' => 'mesa', 'mesero_id' => $u->id]);
        $i = $c->items()->create(['receta_id' => $r->id, 'cantidad' => 3, 'precio_unitario' => 50]);
        $this->post(route('admin.comandas.dividir', $c), ['item_id' => $i->id, 'cantidad' => 1])->assertRedirect();
        $n = Comanda::where('id', '!=', $c->id)->firstOrFail();
        $this->assertEquals(100, $c->fresh()->total);
        $this->assertEquals(50, $n->total);
        $this->assertSame($m->id, $n->mesa_id);
        $this->post(route('admin.comandas.unir', $n), ['destino_id' => $c->id])->assertRedirect();
        $this->assertEquals(150, $c->fresh()->total);
        $this->assertSame('cancelada', $n->fresh()->estado);
    }
}
