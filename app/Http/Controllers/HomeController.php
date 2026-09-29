<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
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
