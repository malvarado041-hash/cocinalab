<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlmacenProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AlmacenController extends Controller
{
    public function index(Request $request)
    {
        $subsDisponibles = $request->categoria
            ? AlmacenProducto::subcategoriasPara($request->categoria)
            : collect(AlmacenProducto::MAPA)->flatMap(fn ($subs) => array_keys($subs))->unique()->values()->all();

        $request->validate([
            'categoria' => ['nullable', Rule::in(array_keys(AlmacenProducto::CATEGORIAS))],
            'subcategoria' => ['nullable', Rule::in($subsDisponibles)],
            'bajo_stock' => ['nullable', 'in:1'],
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $query = AlmacenProducto::query()
            ->porCategoria($request->categoria)
            ->orderBy('nombre');

        if ($request->filled('subcategoria')) {
            $query->where('subcategoria', $request->subcategoria);
        }

        if ($request->boolean('bajo_stock')) {
            $query->bajoStock();
        }

        if ($request->filled('q')) {
            $query->where('nombre', 'like', '%' . $request->q . '%');
        }

        $productos = $query->paginate(10)->withQueryString();
        $bajoStockCount = AlmacenProducto::bajoStock()->count();
        $canRestore = Auth::user()->isSistemas();

        $viewData = [
            'productos' => $productos,
            'categorias' => AlmacenProducto::CATEGORIAS,
            'mapa' => AlmacenProducto::MAPA,
            'subcategorias' => $request->categoria ? AlmacenProducto::subcategoriasPara($request->categoria) : [],
            'filtros' => $request->only(['categoria', 'subcategoria', 'bajo_stock', 'q']),
            'bajoStockCount' => $bajoStockCount,
            'canRestore' => $canRestore,
        ];

        // Búsqueda en vivo: devuelve solo la tabla renderizada.
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.almacen._table', $viewData)->render(),
            ]);
        }

        return view('admin.almacen.index', $viewData);
    }

    public function show(AlmacenProducto $almacen)
    {
        return view('admin.almacen.show', [
            'producto' => $almacen,
            'unidades' => AlmacenProducto::UNIDADES,
        ]);
    }

    public function create()
    {
        return view('admin.almacen.create', [
            'mapa' => AlmacenProducto::MAPA,
            'categorias' => AlmacenProducto::CATEGORIAS,
            'unidades' => AlmacenProducto::UNIDADES,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        AlmacenProducto::create($data);

        return redirect()->route('admin.almacen.index', $this->filtrosActivos($request))
            ->with('success', 'Producto registrado en almacén.');
    }

    public function edit(AlmacenProducto $almacen)
    {
        return view('admin.almacen.edit', [
            'producto' => $almacen,
            'mapa' => AlmacenProducto::MAPA,
            'categorias' => AlmacenProducto::CATEGORIAS,
            'unidades' => AlmacenProducto::UNIDADES,
        ]);
    }

    public function update(Request $request, AlmacenProducto $almacen)
    {
        $data = $this->validated($request, $almacen->id);

        $almacen->update($data);

        return redirect()->route('admin.almacen.index', $this->filtrosActivos($request))
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Request $request, AlmacenProducto $almacen)
    {
        $almacen->delete();

        return redirect()->route('admin.almacen.index', $this->filtrosActivos($request))
            ->with('success', 'Producto dado de baja correctamente.');
    }

    public function trashed()
    {
        if (!Auth::user()->isSistemas()) {
            abort(403, 'Solo sistemas puede ver los productos dados de baja.');
        }

        $productos = AlmacenProducto::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->paginate(10);

        return view('admin.almacen.trashed', compact('productos'));
    }

    public function restore($id)
    {
        if (!Auth::user()->isSistemas()) {
            abort(403, 'Solo sistemas puede restaurar productos.');
        }

        $producto = AlmacenProducto::onlyTrashed()->findOrFail($id);
        $producto->restore();

        return redirect()->route('admin.almacen.trashed')
            ->with('success', 'Producto restaurado correctamente.');
    }

    public function forceDestroy($id)
    {
        if (!Auth::user()->isSistemas()) {
            abort(403, 'Solo sistemas puede eliminar productos definitivamente.');
        }

        $producto = AlmacenProducto::onlyTrashed()->findOrFail($id);
        $producto->forceDelete();

        return redirect()->route('admin.almacen.trashed')
            ->with('success', 'Producto eliminado definitivamente de la base de datos.');
    }

    /**
     * Filtros del listado para conservarlos tras guardar/eliminar.
     * Nota: viajan como query string con los mismos nombres que los campos
     * del form, pero en store/update el cuerpo POST tiene prioridad sobre el
     * query en $request->input(), así que validated() no se contamina.
     */
    private function filtrosActivos(Request $request): array
    {
        // Solo query string: los campos del form viajan en el cuerpo POST y
        // no deben confundirse con el filtro del listado del que se vino.
        $filtros = array_intersect_key(
            $request->query(),
            array_flip(['categoria', 'subcategoria', 'bajo_stock', 'q'])
        );

        return array_filter($filtros, fn ($v) => $v !== null && $v !== '');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        $categoria = $request->input('categoria');
        $subcategoria = $request->input('subcategoria');

        $subsValidas = AlmacenProducto::subcategoriasPara((string) $categoria);
        $unidadesValidas = AlmacenProducto::unidadesPara((string) $categoria, (string) $subcategoria);

        $data = $request->validate([
            'nombre' => ['required', 'string', 'min:2', 'max:200', Rule::unique('almacen_productos', 'nombre')->ignore($ignoreId)],
            'categoria' => ['required', Rule::in(array_keys(AlmacenProducto::CATEGORIAS))],
            'subcategoria' => ['required', 'string', Rule::in($subsValidas)],
            'unidad' => ['required', Rule::in($unidadesValidas ?: AlmacenProducto::todasUnidades())],
            'stock' => ['required', 'numeric', 'min:0', 'max:9999999.99'],
            'stock_minimo' => ['required', 'numeric', 'min:0', 'max:9999999.99'],
            'precio_unitario' => ['nullable', 'numeric', 'min:0', 'max:9999999.99'],
            'perecedero' => ['nullable', 'boolean'],
            'fecha_caducidad' => ['nullable', 'date', 'after_or_equal:today'],
            'proveedor' => ['nullable', 'string', 'max:200'],
        ]);

        $data['perecedero'] = (bool) ($request->boolean('perecedero'));

        if ($data['perecedero'] && empty($data['fecha_caducidad'])) {
            unset($data['fecha_caducidad']);
        }

        if (!$data['perecedero']) {
            $data['fecha_caducidad'] = null;
        }

        return $data;
    }
}
