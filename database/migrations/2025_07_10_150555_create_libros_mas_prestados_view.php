<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateLibrosMasPrestadosView extends Migration
{
    public function up()
    {
        DB::statement($this->createView());
    }

    public function down()
    {
        DB::statement($this->dropView());
    }

    private function createView(): string
    {
        return '
            CREATE VIEW libros_mas_prestados AS
            SELECT 
                l.id,
                l.titulo,
                l.autor,
                l.isbn,
                l.ejemplares,
                COUNT(p.id) as total_prestamos,
                COUNT(CASE WHEN p.estado IN ("prestado", "retrasado") THEN 1 END) as prestamos_activos,
                COUNT(CASE WHEN p.estado = "devuelto" THEN 1 END) as prestamos_devueltos,
                l.ejemplares as disponibles_actuales
            FROM libros l
            LEFT JOIN prestamos p ON l.id = p.libro_id
            GROUP BY l.id, l.titulo, l.autor, l.isbn, l.ejemplares
            ORDER BY total_prestamos DESC, l.titulo ASC
        ';
    }

    private function dropView(): string
    {
        return 'DROP VIEW IF EXISTS libros_mas_prestados';
    }
}
