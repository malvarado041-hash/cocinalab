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

class RecetaController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'tipo' => ['nullable', Rule::in(Receta::TIPOS)],
        ]);

        $recetas = Receta::with(['imagenes', 'ingredientes.almacenProducto'])
            ->when($request->filled('q'), fn ($q) => $q->where('Nombre', 'like', '%' . $request->q . '%'))
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
        $productos = AlmacenProducto::orderBy('nombre')->get(['id', 'nombre', 'precio_unitario', 'unidad']);

        // La lista de ingredientes sale ÚNICAMENTE de almacén: solo
        // ingredientes vinculados a un producto (espejos creados por el
        // observer + backfill). Los legacy sin vínculo no se ofrecen.
        $items = Ingrediente::with('almacenProducto')
            ->whereNotNull('almacen_producto_id')
            ->orderBy('Nombre')
            ->get();

        // Mapa ingrediente_id -> vínculo de almacén para el autollenado JS.
        // (Precalculados aquí: los closures dentro de @json() en Blade
        // rompen el compilador con "Unclosed '[' does not match ')'").
        $mapaVinculos = [];
        $ingOpts = [];
        $opciones = [];
        foreach ($items as $ing) {
            $prod = $ing->almacenProducto;
            $mapaVinculos[$ing->id] = [
                'prod_id' => $ing->almacen_producto_id,
                'unidad' => $prod->unidad ?? null,
                'precio' => $prod->precio_unitario ?? null,
                'nombre' => $prod->nombre ?? $ing->Nombre,
            ];
            $ingOpts[] = ['id' => $ing->id, 'nombre' => $prod->nombre ?? $ing->Nombre];
            $opciones[] = [
                'ingrediente_id' => $ing->id,
                'nombre' => $prod->nombre ?? $ing->Nombre,
                'unidad' => $prod->unidad ?? null,
                'precio' => $prod->precio_unitario ?? null,
            ];
        }

        $prodSugerencias = [];
        foreach ($productos as $p) {
            $prodSugerencias[mb_strtolower($p->nombre)] = [
                'id' => $p->id,
                'nombre' => $p->nombre,
                'unidad' => $p->unidad,
                'precio' => $p->precio_unitario,
            ];
        }

        return [
            'tipos' => Receta::TIPOS,
            'opciones' => $opciones,
            'productos' => $productos,
            'mapaVinculos' => $mapaVinculos,
            'ingOpts' => $ingOpts,
            'prodSugerencias' => $prodSugerencias,
        ];
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'Nombre' => ['required', 'string', 'min:2', 'max:200'],
            'TipoC' => ['required', Rule::in(Receta::TIPOS)],
            'Procedimiento' => ['required', 'string', 'min:10'],
            'precio_platillo' => ['nullable', 'numeric', 'min:0', 'max:9999999.99'],
            'ingredientes' => ['nullable', 'array'],
            'ingredientes.*.ingrediente_id' => ['required', 'distinct', 'exists:ingredientes,id'],
            'ingredientes.*.cantidad' => ['nullable', 'string', 'max:100'],
            'ingredientes.*.cantidad_num' => ['nullable', 'numeric', 'min:0', 'max:9999999.99'],
            'ingredientes.*.unidad' => ['nullable', 'string', 'max:20'],
            'imagenes' => ['nullable', 'array', 'max:6'],
            'imagenes.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'eliminar_imagenes' => ['nullable', 'array'],
            'eliminar_imagenes.*' => ['exists:receta_imagenes,id'],
        ]);

        // Unidad compatible con el producto vinculado al ingrediente.
        $filas = $data['ingredientes'] ?? [];
        if (! empty($filas)) {
            $ids = collect($filas)->pluck('ingrediente_id')->filter()->unique()->all();
            $vinc = Ingrediente::whereIn('id', $ids)->with('almacenProducto')->get()->keyBy('id');

            foreach ($filas as $i => $fila) {
                $unidad = $fila['unidad'] ?? null;
                if ($unidad === null || $unidad === '') {
                    continue;
                }
                $ing = $vinc->get($fila['ingrediente_id'] ?? null);
                $compatibles = Receta::unidadesCompatibles($ing?->almacenProducto?->unidad);
                if (! in_array($unidad, $compatibles, true) && ! in_array(strtolower($unidad), array_map('strtolower', $compatibles), true)) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        "ingredientes.{$i}.unidad" => ["Unidad '{$unidad}' no compatible con el producto de almacén."],
                    ]);
                }
            }
        }

        return $data;
    }

    /**
     * Sincroniza receta_ingrediente. Sin alta al vuelo: el ingrediente
     * debe existir (se da de alta solo desde almacén vía observer).
     * El vínculo ingrediente↔almacén es dato maestro y no se toca aquí.
     */
    private function syncIngredientes(Receta $receta, array $filas): void
    {
        $sync = [];

        foreach ($filas as $fila) {
            if (empty($fila['ingrediente_id'])) {
                continue;
            }

            $ingredienteId = (int) $fila['ingrediente_id'];
            $cantidadNum = isset($fila['cantidad_num']) && $fila['cantidad_num'] !== ''
                ? (float) $fila['cantidad_num']
                : Receta::parseCantidad($fila['cantidad'] ?? null);

            $sync[$ingredienteId] = [
                'cantidad' => $fila['cantidad'] ?? null,
                'cantidad_num' => $cantidadNum,
                'unidad' => $fila['unidad'] ?? null ?: null,
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
