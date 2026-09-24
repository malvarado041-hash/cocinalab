<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RecetaController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

// Acceso
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::view('/error-contrasena', 'auth.error-pass')->name('login.fail.pass');
Route::view('/error-usuario', 'auth.error-user')->name('login.fail.user');

// Zona privada
Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/recetas', [RecetaController::class, 'index'])->name('recetas.index');
    Route::get('/filtrar', [RecetaController::class, 'filtrar'])->name('recetas.filtrar');
    Route::get('/procedimiento', [RecetaController::class, 'show'])->name('recetas.show');
    Route::get('/usuario', [HomeController::class, 'usuario'])->name('usuario');
    Route::get('/info', [HomeController::class, 'info'])->name('info');
});
