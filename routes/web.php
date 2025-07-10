<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EstadisticasController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => redirect()->route('libros.index'));

// Autenticación
Route::get('login',    [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login',   [LoginController::class, 'login']);
Route::post('logout',  [LoginController::class, 'logout'])->name('logout');
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register',[RegisterController::class, 'register']);

Route::middleware('auth')->group(function () {
    // Estadísticas públicas (para todos los usuarios autenticados)
    Route::get('libros/populares',               [EstadisticasController::class, 'publicas'])
         ->name('estadisticas.publicas');
    Route::get('libros/mas-prestados',           [EstadisticasController::class, 'librosMasPrestadosPublicos'])
         ->name('estadisticas.libros-publicos');

    // Búsqueda y disponibilidad
    Route::get('libros/buscar',                  [LibroController::class, 'buscar'])                 ->name('libros.buscar');
    Route::get('libros/disponibles',             [LibroController::class,'disponibles'])            ->name('libros.disponibles');
    Route::get('libros/disponibilidad-detallada',[LibroController::class,'disponibilidadDetallada'])->name('libros.disponibilidad-detallada');

    // CRUD de libros
    Route::resource('libros', LibroController::class);

    // Rutas administrativas
    Route::middleware('role:admin')->group(function () {
        // Dashboard completo
        Route::get('estadisticas',                     [EstadisticasController::class, 'index'])
             ->name('estadisticas.index');
        Route::get('estadisticas/libros-mas-prestados',[EstadisticasController::class, 'librosMasPrestados'])
             ->name('estadisticas.libros-mas-prestados');

        // Préstamos modo admin
        Route::get('prestamos/all',      [PrestamoController::class,'adminIndex'])  ->name('prestamos.admin');
        Route::get('prestamos/vencidos', [PrestamoController::class,'vencidos'])     ->name('prestamos.vencidos');
        Route::post('prestamos/marcar-vencidos',[PrestamoController::class,'marcarVencidos'])
             ->name('prestamos.marcar-vencidos');

        // CRUD de usuarios
        Route::resource('usuarios', UsuarioController::class);
    });

    // CRUD de préstamos (para cualquier usuario autenticado)
    Route::resource('prestamos', PrestamoController::class);
});
