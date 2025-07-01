<?php

// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\UsuarioController;

// Rutas protegidas por roles
Route::middleware(['auth'])->group(function () {
    
    // Solo administradores
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('usuarios', UsuarioController::class);
        Route::resource('libros', LibroController::class);
        Route::get('prestamos/all', [PrestamoController::class, 'adminIndex'])->name('prestamos.admin');
    });
    
    // Usuarios autenticados (admin y usuarios normales)
    Route::resource('prestamos', PrestamoController::class);
    Route::get('libros', [LibroController::class, 'index'])->name('libros.public');
});
