<?php

use App\Http\Controllers\Admin\AlmacenController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RecetaController as AdminRecetaController;
use App\Http\Controllers\Admin\ReporteController;
use App\Http\Controllers\Admin\UserController;
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
Route::get('/olvide-mi-contrasena', [AuthController::class, 'showForgot'])->name('password.forgot');
Route::post('/olvide-mi-contrasena', [AuthController::class, 'forgot'])->name('password.forgot.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Zona privada
Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/recetas', [RecetaController::class, 'index'])->name('recetas.index');
    Route::get('/filtrar', [RecetaController::class, 'filtrar'])->name('recetas.filtrar');
    Route::get('/procedimiento', [RecetaController::class, 'show'])->name('recetas.show');
    Route::get('/usuario', [HomeController::class, 'usuario'])->name('usuario');
    Route::get('/info', [HomeController::class, 'info'])->name('info');
});

// Panel Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn () => view('admin.dashboard', [
        'totalUsers' => \App\Models\User::where(function ($q) { $q->where('role', '!=', 'sistemas')->orWhereNull('role'); })->count(),
        'pendingUsers' => \App\Models\User::where('status', 'pendiente')->where(function ($q) { $q->where('role', '!=', 'sistemas')->orWhereNull('role'); })->count(),
        'activeUsers' => \App\Models\User::where('status', 'activo')->where(function ($q) { $q->where('role', '!=', 'sistemas')->orWhereNull('role'); })->count(),
        'recentUsers' => \App\Models\User::where(function ($q) { $q->where('role', '!=', 'sistemas')->orWhereNull('role'); })->latest()->take(5)->get(),
        'bajoStockCount' => \App\Models\AlmacenProducto::bajoStock()->count(),
        'totalProductos' => \App\Models\AlmacenProducto::count(),
    ]))->name('dashboard');
    
    Route::get('almacen/dados-de-baja', [AlmacenController::class, 'trashed'])->name('almacen.trashed');
    Route::put('almacen/{id}/restaurar', [AlmacenController::class, 'restore'])->name('almacen.restore');
    Route::delete('almacen/{id}/eliminar-definitivo', [AlmacenController::class, 'forceDestroy'])->name('almacen.forceDestroy');
    Route::resource('almacen', AlmacenController::class);

    Route::get('reportes/inventario', [ReporteController::class, 'inventario'])->name('reportes.inventario');
    Route::get('reportes/inventario/csv', [ReporteController::class, 'inventarioCsv'])->name('reportes.inventario.csv');

    Route::get('users/dados-de-baja', [UserController::class, 'trashed'])->name('users.trashed');
    Route::put('users/{id}/restaurar', [UserController::class, 'restore'])->name('users.restore');
    Route::delete('users/{id}/eliminar-definitivo', [UserController::class, 'forceDestroy'])->name('users.forceDestroy');
    Route::resource('users', UserController::class)->except(['create', 'store']);

    Route::get('recetas/dadas-de-baja', [AdminRecetaController::class, 'trashed'])->name('recetas.trashed');
    Route::put('recetas/{id}/restaurar', [AdminRecetaController::class, 'restore'])->name('recetas.restore');
    Route::delete('recetas/{id}/eliminar-definitivo', [AdminRecetaController::class, 'forceDestroy'])->name('recetas.forceDestroy');
    Route::resource('recetas', AdminRecetaController::class);

    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/perfil', [ProfileController::class, 'update'])->name('profile.update');
});
