<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\UsuarioController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aquí se registran las rutas web de la aplicación. Estas rutas se
| cargan mediante el RouteServiceProvider dentro del grupo de
| middleware "web". Asegúrate de haber configurado auth:sanctum
| o auth para protegerlas correctamente.
|
*/

// Redirect desde la raíz al listado de libros
Route::get('/', function () {
    return redirect()->route('libros.index');
});

// Ruta “dummy” para login requerida por el middleware auth
Route::get('login', function () {
    // Si no tienes vistas de login web, redirige al endpoint de API
    return redirect('/api/login');
})->name('login');

// Todas las rutas siguientes requieren estar autenticado
Route::middleware(['auth'])->group(function () {

    // Rutas accesibles solo por administradores
    Route::middleware(['role:admin'])->group(function () {
        // CRUD completo de usuarios
        Route::resource('usuarios', UsuarioController::class);

        // CRUD completo de libros
        Route::resource('libros', LibroController::class);

        // Mostrar todos los préstamos, vista de administrador
        Route::get('prestamos/all', [PrestamoController::class, 'adminIndex'])
            ->name('prestamos.admin');
    });

    // Rutas accesibles por cualquier usuario autenticado (admin o normal)
    // CRUD de préstamos (index, create, store, show, edit, update, destroy)
    Route::resource('prestamos', PrestamoController::class);

    // Listado público de libros (redefine la ruta index para usuarios normales)
    Route::get('libros', [LibroController::class, 'index'])
        ->name('libros.public');
});
