<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Roles que el editor actual puede asignar.
     * El admin solo gestiona roles operativos; sistemas gestiona todos.
     */
    private function assignableRoles(): array
    {
        $base = ['cocinero', 'mesero', 'capitan', 'almacen', 'cajero'];

        if (Auth::user()->isSistemas()) {
            return array_merge($base, ['admin', 'sistemas']);
        }

        return $base;
    }

    public function index()
    {
        $users = User::where('id', '!=', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(7);

        // Solo sistemas puede ver y restaurar bajas lógicas.
        $canRestore = Auth::user()->isSistemas();

        return view('admin.users.index', compact('users', 'canRestore'));
    }

    public function edit(User $user)
    {
        if ($user->id === Auth::id()) {
            abort(403, 'No puedes editar tu propia cuenta desde aquí.');
        }

        if ($user->isPrivileged() && !Auth::user()->isSistemas()) {
            abort(403, 'No puedes editar cuentas administradoras o de sistemas.');
        }

        $roles = $this->assignableRoles();
        $statuses = ['pendiente', 'activo', 'inactivo'];
        $canAssignPrivileged = Auth::user()->isSistemas();

        return view('admin.users.edit', compact('user', 'roles', 'statuses', 'canAssignPrivileged'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->id === Auth::id()) {
            abort(403, 'No puedes editar tu propia cuenta desde aquí.');
        }

        if ($user->isPrivileged() && !Auth::user()->isSistemas()) {
            abort(403, 'No puedes editar cuentas administradoras o de sistemas.');
        }

        $roles = $this->assignableRoles();

        $data = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:100', 'unique:users,name,' . $user->id],
            'codigo_empleado' => ['required', 'digits:6', 'unique:users,codigo_empleado,' . $user->id],
            'role' => 'required|in:' . implode(',', $roles),
            'status' => 'required|in:pendiente,activo,inactivo',
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $wasPending = $user->isPending();
        $update = [
            'name' => $data['name'],
            'codigo_empleado' => $data['codigo_empleado'],
            'role' => $data['role'],
            'status' => $data['status'],
            'approved_by' => $data['status'] === 'activo' && $wasPending ? Auth::id() : $user->approved_by,
            'approved_at' => $data['status'] === 'activo' && $wasPending ? now() : $user->approved_at,
        ];

        if (!empty($data['password'])) {
            $update['password'] = Hash::make($data['password']);
        }

        $user->update($update);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            abort(403, 'No puedes eliminar tu propia cuenta.');
        }

        // El admin solo puede dar de baja roles operativos.
        if ($user->isPrivileged() && !Auth::user()->isSistemas()) {
            abort(403, 'No puedes dar de baja a otro administrador o de sistemas.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario dado de baja correctamente.');
    }

    public function trashed()
    {
        if (!Auth::user()->isSistemas()) {
            abort(403, 'Solo sistemas puede ver los usuarios dados de baja.');
        }

        $users = User::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->paginate(7);

        return view('admin.users.trashed', compact('users'));
    }

    public function restore($id)
    {
        if (!Auth::user()->isSistemas()) {
            abort(403, 'Solo sistemas puede restaurar usuarios.');
        }

        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('admin.users.trashed')
            ->with('success', 'Usuario restaurado correctamente.');
    }

    public function forceDestroy($id)
    {
        if (!Auth::user()->isSistemas()) {
            abort(403, 'Solo sistemas puede eliminar usuarios definitivamente.');
        }

        $user = User::onlyTrashed()->findOrFail($id);
        $user->forceDelete();

        return redirect()->route('admin.users.trashed')
            ->with('success', 'Usuario eliminado definitivamente de la base de datos.');
    }
}
