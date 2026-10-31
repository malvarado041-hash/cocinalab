<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlmacenProducto;
use App\Models\Ingrediente;
use App\Models\Receta;
use App\Models\RecetaImagen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RecetaController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'tipo' => ['nullable', Rule::in(Receta::TIPOS)],
        ]);

        $recetas = Receta::with(['imagenes', 'ingredientes.almacenProducto'])
            ->when($request->filled('q'), fn ($q) => $q->where('Nombre', 'like', '%'.$request->q.'%'))
            ->when($request->filled('tipo'), fn ($q) => $q->where('TipoC', $request->tipo))
            ->orderBy('Nombre')
            ->paginate(10)
            ->withQueryString();

        return view('admin.recetas.index', [
            'recetas' => $recetas,
            'tipos' => Receta::TIPOS,
            'filtros' => $request->only(['q', 'tipo']),
            'canRestore' => Auth::user()->isSistemas(),
        ]);
    }

    public function show(Receta $receta)
    {
        $receta->load(['imagenes', 'ingredientes.almacenProducto']);
        $costo = $receta->costoEstimado();

        return view('admin.recetas.show', compact('receta', 'costo'));
    }

    public function create()
    {
        return view('admin.recetas.create', $this->formData());
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $receta = DB::transaction(function () use ($data, $request) {
            $receta = Receta::create([
                'Nombre' => $data['Nombre'],
                'porciones' => $data['porciones'],
                'Procedimiento' => $data['Procedimiento'],
                'TipoC' => $data['TipoC'],
                'precio_platillo' => $data['precio_platillo'] ?? null,
            ]);

            $this->syncIngredientes($receta, $data['ingredientes'] ?? []);
            $this->guardarImagenes($receta, $request);

            return $receta;
        });

        return redirect()->route('admin.recetas.show', $receta)
            ->with('success', 'Receta creada correctamente.');
    }

    public function edit(Receta $receta)
    {
        $receta->load(['imagenes', 'ingredientes.almacenProducto']);

        return view('admin.recetas.edit', array_merge(
            $this->formData(),
            ['receta' => $receta]
        ));
    }

    public function update(Request $request, Receta $receta)
    {
        $data = $this->validated($request);

        DB::transaction(function () use ($data, $request, $receta) {
            $receta->update([
                'Nombre' => $data['Nombre'],
                'porciones' => $data['porciones'],
                'Procedimiento' => $data['Procedimiento'],
                'TipoC' => $data['TipoC'],
                'precio_platillo' => $data['precio_platillo'] ?? null,
            ]);

            $this->syncIngredientes($receta, $data['ingredientes'] ?? []);
            $this->eliminarImagenes($receta, $request->input('eliminar_imagenes', []));
            $this->guardarImagenes($receta, $request);
        });

        return redirect()->route('admin.recetas.show', $receta)
            ->with('success', 'Receta actualizada correctamente.');
    }

    /** Admin y sistemas: solo dar de baja (soft delete). */
    public function destroy(Receta $receta)
    {
        $receta->delete();

        return redirect()->route('admin.recetas.index')
            ->with('success', 'Receta dada de baja correctamente.');
    }

    public function trashed()
    {
        $this->soloSistemas();

        $recetas = Receta::onlyTrashed()
            ->withCount('ingredientes')
            ->orderBy('deleted_at', 'desc')
            ->paginate(10);

        return view('admin.recetas.trashed', compact('recetas'));
    }

    public function restore($id)
    {
        $this->soloSistemas();

        Receta::onlyTrashed()->findOrFail($id)->restore();

        return redirect()->route('admin.recetas.trashed')
            ->with('success', 'Receta restaurada correctamente.');
    }

    public function forceDestroy($id)
    {
        $this->soloSistemas();

        $receta = Receta::onlyTrashed()->findOrFail($id);

        DB::transaction(function () use ($receta) {
            foreach ($receta->imagenes as $img) {
                Storage::disk('public')->delete($img->path);
            }
            $receta->forceDelete();
        });

        return redirect()->route('admin.recetas.trashed')
            ->with('success', 'Receta eliminada definitivamente.');
    }

    private function soloSistemas(): void
    {
        if (! Auth::user()->isSistemas()) {
            abort(403, 'Solo sistemas puede ver las recetas dadas de baja.');
        }
    }

    private function formData(): array
    {
        // El catálogo viene directamente de almacén, incluso para productos
        // antiguos que todavía no tienen un ingrediente espejo.
        $productos = AlmacenProducto::orderBy('nombre')->get();
        $mapaVinculos = [];
        $opciones = [];
        foreach ($productos as $producto) {
            $mapaVinculos[$producto->id] = [
                'prod_id' => $producto->id,
                'unidad' => $producto->unidad,
                'precio' => $producto->precio_unitario,
                'nombre' => $producto->nombre,
            ];
            $opciones[] = [
                'almacen_producto_id' => $producto->id,
                'nombre' => $producto->nombre,
            ];
        }

        return [
            'tipos' => Receta::TIPOS,
            'opciones' => $opciones,
            'mapaVinculos' => $mapaVinculos,
        ];
    }

    private function validated(Request $request): array
    {
        // Compatibilidad con formularios anteriores: traduce el ingrediente
        // existente a su producto, sin aceptar ingredientes sin almacén.
        $filas = $request->input('ingredientes');
        if (is_array($filas)) {
            foreach ($filas as $i => &$fila) {
                if (is_array($fila) && empty($fila['almacen_producto_id']) && ! empty($fila['ingrediente_id']) && is_scalar($fila['ingrediente_id'])) {
                    $ingrediente = Ingrediente::with('almacenProducto')->find($fila['ingrediente_id']);
                    if (! $ingrediente?->almacenProducto) {
                        throw ValidationException::withMessages(["ingredientes.{$i}.ingrediente_id" => 'Selecciona un producto activo del almacén.']);
                    }
                    $fila['almacen_producto_id'] = $ingrediente->almacen_producto_id;
                }
            }
            unset($fila);
            $request->merge(['ingredientes' => $filas]);
        }

        $data = $request->validate([
            'porciones' => ['required', 'integer', 'min:1', 'max:1000'],
            'Nombre' => ['required', 'string', 'min:2', 'max:200'],
            'TipoC' => ['required', Rule::in(Receta::TIPOS)],
            'Procedimiento' => ['required', 'string', 'min:10'],
            'precio_platillo' => ['nullable', 'numeric', 'min:0', 'max:9999999.99'],
            'ingredientes' => ['required', 'array', 'min:1'],
            'ingredientes.*' => ['array'],
            'ingredientes.*.almacen_producto_id' => ['required', 'integer', 'distinct', Rule::exists('almacen_productos', 'id')->whereNull('deleted_at')],
            'ingredientes.*.cantidad' => ['nullable', 'string', 'max:100'],
            'ingredientes.*.cantidad_num' => ['required', 'numeric', 'gt:0', 'max:9999999.99'],
            'ingredientes.*.unidad' => ['required', 'string', 'max:20'],
            'imagenes' => ['nullable', 'array', 'max:6'],
            'imagenes.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'eliminar_imagenes' => ['nullable', 'array'],
            'eliminar_imagenes.*' => ['exists:receta_imagenes,id'],
        ]);

        $productos = AlmacenProducto::whereIn('id', collect($data['ingredientes'])->pluck('almacen_producto_id'))->get()->keyBy('id');
        foreach ($data['ingredientes'] as $i => $fila) {
            $producto = $productos->get($fila['almacen_producto_id']);
            $compatibles = Receta::unidadesCompatibles($producto?->unidad);
            if (! in_array(strtolower($fila['unidad']), array_map('strtolower', $compatibles), true)) {
                throw ValidationException::withMessages([
                    "ingredientes.{$i}.unidad" => 'La unidad no es compatible con el producto del almacén.',
                ]);
            }
        }

        return $data;
    }

    /** Resuelve el ingrediente espejo al guardar, sin modificar existencias. */
    private function syncIngredientes(Receta $receta, array $filas): void
    {
        $sync = [];
        foreach (collect($filas)->sortBy('almacen_producto_id') as $fila) {
            $producto = AlmacenProducto::lockForUpdate()->findOrFail($fila['almacen_producto_id']);
            $ingrediente = $receta->ingredientes()->where('almacen_producto_id', $producto->id)->first()
                ?? $producto->ingredientes()->orderBy('id')->first();
            if (! $ingrediente) {
                $ingrediente = Ingrediente::where('Nombre', $producto->nombre)->whereNull('almacen_producto_id')->lockForUpdate()->first();
                if ($ingrediente) {
                    $ingrediente->update(['almacen_producto_id' => $producto->id]);
                } else {
                    $ingrediente = Ingrediente::create(['Nombre' => $producto->nombre, 'almacen_producto_id' => $producto->id]);
                }
            }
            $sync[$ingrediente->id] = [
                'cantidad' => $fila['cantidad'] ?? null,
                'cantidad_num' => $fila['cantidad_num'],
                'unidad' => $fila['unidad'],
            ];
        }
        $receta->ingredientes()->sync($sync);
    }

    private function guardarImagenes(Receta $receta, Request $request): void
    {
        if (! $request->hasFile('imagenes')) {
            return;
        }

        $orden = (int) ($receta->imagenes()->max('orden') ?? -1) + 1;

        foreach ($request->file('imagenes') as $file) {
            $path = $file->store('recetas', 'public');
            RecetaImagen::create([
                'receta_id' => $receta->id,
                'path' => $path,
                'orden' => $orden++,
            ]);
        }
    }

    private function eliminarImagenes(Receta $receta, array $ids): void
    {
        if (empty($ids)) {
            return;
        }

        $imgs = RecetaImagen::where('receta_id', $receta->id)->whereIn('id', $ids)->get();

        foreach ($imgs as $img) {
            Storage::disk('public')->delete($img->path);
            $img->delete();
        }
    }
}
