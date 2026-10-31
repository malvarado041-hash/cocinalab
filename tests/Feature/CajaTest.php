<?php

namespace Tests\Feature;

use App\Models\Caja;
use App\Models\Comanda;
use App\Models\Receta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CajaTest extends TestCase
{
    use RefreshDatabase;

    private function escenario(): array
    {
        $u = User::factory()->create(['role' => 'cajero', 'status' => 'activo', 'codigo_empleado' => '700001']);
        $r = Receta::create(['Nombre' => 'Prueba', 'porciones' => 1, 'precio_platillo' => 100]);
        $c = Comanda::create(['mesa' => '1', 'tipo' => 'mesa', 'mesero_id' => $u->id, 'estado' => 'servida']);
        $c->items()->create(['receta_id' => $r->id, 'cantidad' => 1, 'precio_unitario' => 100, 'estado' => 'servido']);
        $caja = Caja::create(['user_id' => $u->id, 'fondo' => 200]);

        return [$u, $c, $caja];
    }

    public function test_pago_mixto_cambio_y_cierre(): void
    {
        [$u,$c,$caja] = $this->escenario();
        $this->actingAs($u)->post(route('admin.caja.cobrar', $c), ['pagos' => [['metodo' => 'efectivo', 'importe' => 60, 'recibido' => 100], ['metodo' => 'tarjeta', 'importe' => 40]]])->assertRedirect(route('admin.caja.ticket', $c));
        $this->assertNotNull($c->fresh()->cerrada_at);
        $this->assertDatabaseHas('cobros', ['comanda_id' => $c->id, 'cambio' => 40]);
        $this->put(route('admin.caja.cerrar', $caja), ['efectivo_contado' => 255])->assertRedirect();
        $this->assertEquals(260, $caja->fresh()->efectivo_esperado);
        $this->assertEquals(-5, $caja->fresh()->diferencia);
    }

    public function test_no_admite_cobro_duplicado_ni_importe_incorrecto(): void
    {
        [$u,$c,$caja] = $this->escenario();
        $this->actingAs($u);
        $this->post(route('admin.caja.cobrar', $c), ['pagos' => [['metodo' => 'tarjeta', 'importe' => 99]]])->assertSessionHasErrors('pago');
        $this->assertDatabaseCount('cobros', 0);
        $this->post(route('admin.caja.cobrar', $c), ['pagos' => [['metodo' => 'tarjeta', 'importe' => 100]]])->assertRedirect();
        $this->post(route('admin.caja.cobrar', $c), ['pagos' => [['metodo' => 'tarjeta', 'importe' => 100]]])->assertSessionHasErrors('pago');
        $this->assertDatabaseCount('cobros',1);
    }
}
