<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirige la raíz al listado de libros
Route::get('/', function () {
    return redirect()->route('libros.index');
});

// Rutas de login, registro y logout web (sesión)
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Todas las rutas siguientes requieren estar autenticado
Route::middleware(['auth'])->group(function () {

    Route::get('libros/disponibles', [LibroController::class,'disponibles'])
         ->name('libros.disponibles');
    Route::get('libros/disponibilidad-detallada', [LibroController::class,'disponibilidadDetallada'])
         ->name('libros.disponibilidad-detallada');

    // Ruta de búsqueda avanzada
    Route::get('libros/buscar', [LibroController::class, 'buscar'])
         ->name('libros.buscar');

    // 2) Luego el CRUD genérico de recursos
    Route::resource('libros', LibroController::class);
    // Rutas accesibles solo por administradores
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('usuarios', UsuarioController::class);
        // Mostrar todos los préstamos, vista de administrador
        Route::get('prestamos/all', [PrestamoController::class, 'adminIndex'])
             ->name('prestamos.admin');
    });

    // CRUD de préstamos (index, create, store, show, edit, update, destroy)
    Route::resource('prestamos', PrestamoController::class);
    
});
