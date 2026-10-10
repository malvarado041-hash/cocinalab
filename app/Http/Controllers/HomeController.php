<?php

namespace App\Http\Controllers;

use App\Models\Receta;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $destacadas = Receta::with('imagenes')
            ->orderBy('Nombre')
            ->take(12)
            ->get();

        return view('home', compact('destacadas'));
    }

    public function usuario()
    {
        return view('usuario');
    }

    public function info()
    {
        return view('info');
    }
}
