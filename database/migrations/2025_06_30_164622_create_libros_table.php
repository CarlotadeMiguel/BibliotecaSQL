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
            Schema::create('libros', function (Blueprint $table) {
        $table->id();
        $table->string('titulo', 255);
        $table->string('autor', 150)->nullable();
        $table->string('isbn', 20)->unique()->nullable();
        $table->integer('ejemplares')->default(1);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('libros');
    }
};
