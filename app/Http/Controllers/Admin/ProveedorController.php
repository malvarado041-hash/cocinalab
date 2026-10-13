<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * Catálogo de proveedores.
 * Admin, sistemas y rol almacen pueden ver, dar de alta, editar y
 * dar de baja. Solo sistemas puede restaurar y eliminar definitivamente.
 */
class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $query = Proveedor::query()->orderBy('nombre');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('nombre', 'like', '%' . $q . '%')
                    ->orWhere('telefono', 'like', '%' . $q . '%')
                    ->orWhere('email', 'like', '%' . $q . '%');
            });
        }

        $proveedores = $query->paginate(10)->withQueryString();
        $canRestore = Auth::user()->isSistemas();

        return view('admin.proveedores.index', [
            'proveedores' => $proveedores,
            'filtros' => $request->only(['q']),
            'canRestore' => $canRestore,
        ]);
    }

    public function show(Proveedor $proveedor)
    {
        return view('admin.proveedores.show', ['proveedor' => $proveedor]);
    }

    public function create()
    {
        return view('admin.proveedores.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        Proveedor::create($data);

        return redirect()->route('admin.proveedores.index')
            ->with('success', 'Proveedor registrado correctamente.');
    }

    public function edit(Proveedor $proveedor)
    {
        return view('admin.proveedores.edit', ['proveedor' => $proveedor]);
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        $data = $this->validated($request, $proveedor->id);

        $proveedor->update($data);

        return redirect()->route('admin.proveedores.index')
            ->with('success', 'Proveedor actualizado correctamente.');
    }

    public function destroy(Proveedor $proveedor)
    {
        $proveedor->delete();

        return redirect()->route('admin.proveedores.index')
            ->with('success', 'Proveedor dado de baja correctamente.');
    }

    public function trashed()
    {
        if (!Auth::user()->isSistemas()) {
            abort(403, 'Solo sistemas puede ver los proveedores dados de baja.');
        }

        $proveedores = Proveedor::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->paginate(10);

        return view('admin.proveedores.trashed', compact('proveedores'));
    }

    public function restore($id)
    {
        if (!Auth::user()->isSistemas()) {
            abort(403, 'Solo sistemas puede restaurar proveedores.');
        }

        $proveedor = Proveedor::onlyTrashed()->findOrFail($id);
        $proveedor->restore();

        return redirect()->route('admin.proveedores.trashed')
            ->with('success', 'Proveedor restaurado correctamente.');
    }

    public function forceDestroy($id)
    {
        if (!Auth::user()->isSistemas()) {
            abort(403, 'Solo sistemas puede eliminar proveedores definitivamente.');
        }

        $proveedor = Proveedor::onlyTrashed()->findOrFail($id);
        $proveedor->forceDelete();

        return redirect()->route('admin.proveedores.trashed')
            ->with('success', 'Proveedor eliminado definitivamente de la base de datos.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'nombre' => ['required', 'string', 'min:2', 'max:200', Rule::unique('proveedores', 'nombre')->ignore($ignoreId)],
            'telefono' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:200'],
            'direccion' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
