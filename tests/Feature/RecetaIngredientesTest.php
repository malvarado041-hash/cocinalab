<?php

namespace Tests\Feature;

use App\Models\AlmacenProducto;
use App\Models\Ingrediente;
use App\Models\Receta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecetaIngredientesTest extends TestCase
{
    use RefreshDatabase;

    public function test_formulario_solo_ofrece_ingredientes_con_producto_activo(): void
    {
        $u = User::factory()->create(['role' => 'admin', 'status' => 'activo', 'codigo_empleado' => '930001']);
        $p = AlmacenProducto::create(['nombre' => 'Ingrediente activo', 'categoria' => 'ingrediente', 'subcategoria' => 'verdura', 'unidad' => 'kg', 'stock' => 1]);
        $activo = Ingrediente::where('almacen_producto_id', $p->id)->firstOrFail();
        Ingrediente::create(['Nombre' => 'Ingrediente sin vínculo']);
        $baja = AlmacenProducto::create(['nombre' => 'Ingrediente dado de baja', 'categoria' => 'ingrediente', 'subcategoria' => 'verdura', 'unidad' => 'kg', 'stock' => 1]);
        $baja->delete();
        $this->actingAs($u)->get(route('admin.recetas.create'))->assertOk()->assertViewHas('opciones', fn ($opciones) => count($opciones) === 1 && $opciones[0]['almacen_producto_id'] === $p->id)->assertSee('Datos del platillo')->assertSee('Resumen de la receta');
    }

    public function test_no_admite_ingrediente_sin_vinculo_aunque_se_envie_manualmente(): void
    {
        $u = User::factory()->create(['role' => 'admin', 'status' => 'activo', 'codigo_empleado' => '930002']);
        $i = Ingrediente::create(['Nombre' => 'Sin vínculo']);
        $this->actingAs($u)->post(route('admin.recetas.store'), ['Nombre' => 'Prueba inválida', 'TipoC' => 'Comida', 'porciones' => 1, 'Procedimiento' => 'Preparar todos los ingredientes', 'ingredientes' => [['ingrediente_id' => $i->id, 'cantidad_num' => 100, 'unidad' => 'g']]])->assertSessionHasErrors('ingredientes.0.ingrediente_id');
        $this->assertDatabaseCount('recetas', 0);
    }

    public function test_receta_usa_producto_sin_espejo_y_crea_vinculo_al_guardar(): void
    {
        $u = User::factory()->create(['role' => 'admin', 'status' => 'activo', 'codigo_empleado' => '930003']);
        $p = AlmacenProducto::create(['nombre' => 'Producto antiguo sin espejo', 'categoria' => 'ingrediente', 'subcategoria' => 'verdura', 'unidad' => 'kg', 'stock' => 5, 'precio_unitario' => 30]);
        Ingrediente::where('almacen_producto_id', $p->id)->delete();
        $this->actingAs($u)->get(route('admin.recetas.create'))->assertOk()->assertViewHas('opciones', fn ($opciones) => collect($opciones)->contains('almacen_producto_id', $p->id));
        $this->assertDatabaseCount('ingredientes', 0);
        $datos = ['Nombre' => 'Receta directa de almacén', 'TipoC' => 'Comida', 'porciones' => 1, 'Procedimiento' => 'Cocinar y servir los ingredientes', 'ingredientes' => [['almacen_producto_id' => $p->id, 'cantidad_num' => 200, 'unidad' => 'g']]];
        $this->post(route('admin.recetas.store'), $datos)->assertRedirect();
        $i = Ingrediente::where('almacen_producto_id', $p->id)->firstOrFail();
        $this->assertDatabaseHas('receta_ingrediente', ['ingrediente_id' => $i->id, 'cantidad_num' => 200, 'unidad' => 'g']);
        $this->assertEquals(5, $p->fresh()->stock);
        $this->assertDatabaseCount('movimientos_inventario', 0);
        $receta = Receta::firstOrFail();
        $this->assertEquals(6, $receta->costoEstimado()['total']);
        $this->put(route('admin.recetas.update', $receta), $datos)->assertRedirect();
        $this->assertDatabaseCount('ingredientes', 1);
    }

    public function test_no_guarda_receta_con_producto_dado_de_baja(): void
    {
        $u = User::factory()->create(['role' => 'admin', 'status' => 'activo', 'codigo_empleado' => '930004']);
        $p = AlmacenProducto::create(['nombre' => 'Producto inactivo', 'categoria' => 'ingrediente', 'subcategoria' => 'verdura', 'unidad' => 'kg', 'stock' => 1]);
        $p->delete();
        $this->actingAs($u)->post(route('admin.recetas.store'), ['Nombre' => 'No guardar', 'TipoC' => 'Comida', 'porciones' => 1, 'Procedimiento' => 'Preparar todos los ingredientes', 'ingredientes' => [['almacen_producto_id' => $p->id, 'cantidad_num' => 100, 'unidad' => 'g']]])->assertSessionHasErrors('ingredientes.0.almacen_producto_id');
        $this->assertDatabaseCount('recetas', 0);
    }
}
