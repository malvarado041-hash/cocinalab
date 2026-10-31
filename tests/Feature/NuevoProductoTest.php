<?php

namespace Tests\Feature;

use App\Models\AlmacenProducto;
use App\Models\Ingrediente;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NuevoProductoTest extends TestCase
{
    use RefreshDatabase;

    private function guardarProducto(): AlmacenProducto
    {
        $u = User::factory()->create(['role' => 'admin', 'status' => 'activo', 'codigo_empleado' => '940001']);
        $this->actingAs($u)->post(route('admin.almacen.store'), [
            'nombre' => 'Tomate de prueba', 'categoria' => 'ingrediente', 'subcategoria' => 'verdura', 'unidad' => 'kg',
            'stock' => 5, 'stock_minimo' => 1, 'precio_unitario' => 30,
        ])->assertRedirect(route('admin.almacen.index'));

        return AlmacenProducto::where('nombre', 'Tomate de prueba')->firstOrFail();
    }

    public function test_nuevo_producto_guarda_en_almacen_y_queda_disponible_en_recetas(): void
    {
        $p = $this->guardarProducto();
        $this->assertDatabaseHas('almacen_productos', ['id' => $p->id, 'stock' => 5]);
        $i = Ingrediente::where('almacen_producto_id', $p->id)->firstOrFail();
        $this->get(route('admin.recetas.create'))->assertViewHas('opciones', fn ($opciones) => collect($opciones)->contains('ingrediente_id', $i->id));
        $this->assertDatabaseHas('movimientos_inventario', ['almacen_producto_id' => $p->id, 'tipo' => 'entrada', 'cantidad' => 5]);
    }

    public function test_reutiliza_ingrediente_sin_vinculo(): void
    {
        $i = Ingrediente::create(['Nombre' => 'Tomate de prueba']);
        $p = $this->guardarProducto();
        $this->assertSame($p->id, $i->fresh()->almacen_producto_id);
        $this->assertDatabaseCount('ingredientes', 1);
    }

    public function test_nombre_coincidente_no_roba_vinculo_de_otro_producto(): void
    {
        $anterior = AlmacenProducto::create(['nombre' => 'Otro tomate', 'categoria' => 'ingrediente', 'subcategoria' => 'verdura', 'unidad' => 'kg', 'stock' => 0]);
        $i = Ingrediente::where('almacen_producto_id', $anterior->id)->firstOrFail();
        $i->update(['Nombre' => 'Tomate de prueba']);
        $nuevo = $this->guardarProducto();
        $this->assertSame($anterior->id, $i->fresh()->almacen_producto_id);
        $this->assertDatabaseHas('ingredientes', ['Nombre' => 'Tomate de prueba', 'almacen_producto_id' => $nuevo->id]);
    }
}
