<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Ingrediente;
use App\Models\AlmacenProducto;
use Illuminate\Http\Request;
class IngredienteController extends Controller {
    public function index() {
        return view('admin.ingredientes', ['ingredientes' => Ingrediente::with('almacenProducto')->orderBy('Nombre')->get(), 'productos' => AlmacenProducto::orderBy('nombre')->get()]);
    }
    public function update(Request $request, Ingrediente $ingrediente) {
        $data = $request->validate(['almacen_producto_id' => 'nullable|integer|exists:almacen_productos,id']);
        if (!empty($data['almacen_producto_id'])) { AlmacenProducto::findOrFail($data['almacen_producto_id']); }
        $ingrediente->update($data);
        return back()->with('success', 'Vínculo actualizado. Las existencias no se modificaron. Revisa cantidades y unidades en las recetas.');
    }
}
