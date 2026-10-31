<?php

namespace Tests\Feature;

use App\Models\Ajuste;
use App\Models\Caja;
use App\Models\Cobro;
use App\Models\Comanda;
use App\Models\Receta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdministracionTest extends TestCase
{
    use RefreshDatabase;

    private function admin()
    {
        return User::factory()->create(['role' => 'admin', 'status' => 'activo', 'codigo_empleado' => '900001']);
    }

    public function test_pantallas_nuevas_renderizan_y_busqueda_muestra_todas_las_coincidencias(): void
    {
        $this->actingAs($this->admin());
        foreach (['admin.mesas.index', 'admin.caja.index', 'admin.movimientos.index', 'admin.ingredientes.index', 'admin.configuracion.index', 'admin.auditoria', 'admin.reportes.ventas', 'admin.reportes.inventario', 'admin.comandas.create', 'admin.recetas.create'] as $ruta) {
            $this->get(route($ruta))->assertOk();
        }
        foreach (['Arroz primero', 'Arroz segundo'] as $n) {
            Receta::create(['Nombre' => $n]);
        }
        $this->get(route('recetas.show', ['buscar' => 'Arroz']))->assertOk()->assertSee('Arroz primero')->assertSee('Arroz segundo');
    }

    public function test_configuracion_y_auditoria_no_exponen_password(): void
    {
        $u = $this->admin();
        $this->actingAs($u);
        $this->put(route('admin.configuracion.update'), ['nombre_restaurante' => 'Prueba', 'moneda' => 'MXN', 'zona_horaria' => 'America/Tijuana'])->assertRedirect();
        $this->assertSame('Prueba', Ajuste::valor('nombre_restaurante'));
        $u->update(['password' => 'secreto-de-prueba']);
        $this->get(route('admin.auditoria'))->assertOk()->assertDontSee('secreto-de-prueba');
        $this->assertDatabaseHas('eventos_operacion', ['accion' => 'configuracion_actualizada']);
    }

    public function test_reporte_respeta_dia_local_y_costo_incompleto_historico(): void
    {
        $u = $this->admin();
        $this->actingAs($u);
        $r = Receta::create(['Nombre' => 'Histórico']);
        $c = Comanda::create(['mesa' => 'Prueba', 'mesero_id' => $u->id, 'tipo' => 'mesa', 'estado' => 'servida', 'cerrada_at' => '2026-11-01 01:00:00']);
        $c->items()->create(['receta_id' => $r->id, 'cantidad' => 1, 'precio_unitario' => 100, 'estado' => 'servido']);
        $caja = Caja::create(['user_id' => $u->id, 'fondo' => 0]);
        Cobro::create(['comanda_id' => $c->id, 'caja_id' => $caja->id, 'user_id' => $u->id, 'metodo' => 'tarjeta', 'importe' => 100, 'recibido' => 100, 'created_at' => '2026-11-01 01:00:00']);
        $this->get(route('admin.reportes.ventas', ['desde' => '2026-10-31', 'hasta' => '2026-10-31']))->assertOk()->assertSee('Histórico')->assertSee('Costo incompleto')->assertSee('$100.00');
    }

    public function test_almacen_no_puede_acceder_a_caja_ni_ajustes(): void
    {
        $u = User::factory()->create(['role' => 'almacen', 'status' => 'activo', 'codigo_empleado' => '900002']);
        $this->actingAs($u)->get(route('admin.caja.index'))->assertForbidden();
        $this->getJson(route('admin.configuracion.index'))->assertForbidden();
        $this->get(route('admin.movimientos.index'))->assertOk();
    }
}
