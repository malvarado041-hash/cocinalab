<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        if ($user->id === Auth::id()) {
            abort(403, 'No puedes editar tu propia cuenta desde aquí.');
        }

        $roles = ['cocinero', 'mesero', 'capitan', 'almacen', 'cajero', 'admin'];
        $statuses = ['pendiente', 'activo', 'inactivo'];

        return view('admin.users.edit', compact('user', 'roles', 'statuses'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->id === Auth::id()) {
            abort(403, 'No puedes editar tu propia cuenta desde aquí.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:100', 'unique:users,name,' . $user->id],
            'codigo_empleado' => ['required', 'digits:6', 'unique:users,codigo_empleado,' . $user->id],
            'role' => 'required|in:cocinero,mesero,capitan,almacen,cajero,admin',
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

        if ($user->isAdmin()) {
            abort(403, 'No puedes eliminar a otro administrador.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}
