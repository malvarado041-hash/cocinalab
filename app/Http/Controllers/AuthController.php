<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    // Login con Usuario
    public function login(Request $request)
    {
        $data = $request->validate([
            'Usuario' => 'required|string',
            'contrasena' => 'required|string',
        ]);

        $user = User::where('name', $data['Usuario'])->first();

        if (! $user) {
            return redirect()->route('login.fail.user');
        }

        if (! Hash::check($data['contrasena'], $user->password)) {
            return redirect()->route('login.fail.pass');
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->route('home');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    // Registro validado
    public function register(Request $request)
    {
        $data = $request->validate([
            'txtNombre' => 'required|string|max:200|unique:users,name',
            'txtCorreo' => 'required|email|max:200|unique:users,email',
            'contrasena' => 'required|string|min:4',
        ]);

        $user = User::create([
            'name' => $data['txtNombre'],
            'email' => $data['txtCorreo'],
            'password' => Hash::make($data['contrasena']),
        ]);

        Auth::login($user);
        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
