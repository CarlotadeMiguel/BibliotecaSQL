<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('prestamos', function (Blueprint $table) {
            $table->index('usuario_id');
            $table->index('libro_id');
            $table->index('fecha_prestamo');
            $table->index('estado');
        });
        Schema::table('libros', function (Blueprint $table) {
            $table->index('titulo');
            $table->index('autor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
            Schema::table('prestamos', function (Blueprint $table) {
                $table->dropIndex(['usuario_id']);
                $table->dropIndex(['libro_id']);
                $table->dropIndex(['fecha_prestamo']);
                $table->dropIndex(['estado']);
            });
            Schema::table('libros', function (Blueprint $table) {
                $table->dropIndex(['titulo']);
                $table->dropIndex(['autor']);
        });
    }
};
