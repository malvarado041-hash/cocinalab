<?php

namespace Tests\Feature;

use App\Models\AlmacenProducto;
use App\Models\Ingrediente;
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
        $this->actingAs($u)->get(route('admin.recetas.create'))->assertOk()->assertViewHas('opciones', fn ($opciones) => count($opciones) === 1 && $opciones[0]['ingrediente_id'] === $activo->id)->assertSee('Datos del platillo')->assertSee('Resumen de la receta');
    }

    public function test_no_admite_ingrediente_sin_vinculo_aunque_se_envie_manualmente(): void
    {
        $u = User::factory()->create(['role' => 'admin', 'status' => 'activo', 'codigo_empleado' => '930002']);
        $i = Ingrediente::create(['Nombre' => 'Sin vínculo']);
        $this->actingAs($u)->post(route('admin.recetas.store'), ['Nombre' => 'Prueba inválida', 'TipoC' => 'Comida', 'porciones' => 1, 'Procedimiento' => 'Preparar todos los ingredientes', 'ingredientes' => [['ingrediente_id' => $i->id, 'cantidad_num' => 100, 'unidad' => 'g']]])->assertSessionHasErrors('ingredientes.0.ingrediente_id');
        $this->assertDatabaseCount('recetas', 0);
    }
}
