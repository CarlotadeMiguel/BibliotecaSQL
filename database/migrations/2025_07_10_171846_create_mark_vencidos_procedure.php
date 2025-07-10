<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateMarkVencidosProcedure extends Migration
{
    public function up()
    {
        DB::unprepared(<<<'SQL'
DROP PROCEDURE IF EXISTS mark_vencidos;
CREATE PROCEDURE mark_vencidos()
BEGIN
  UPDATE prestamos
  SET estado = 'retrasado'
  WHERE fecha_devolucion IS NULL
    AND DATE_ADD(fecha_prestamo, INTERVAL plazo DAY) < NOW();
END;
SQL
        );
    }

    public function down()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS mark_vencidos;');
    }
}
