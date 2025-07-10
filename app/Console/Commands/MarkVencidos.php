<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MarkVencidos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prestamos:mark-vencidos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Marca préstamos vencidos usando el procedimiento almacenado';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 1) Marcar vencidos
        DB::statement('CALL mark_vencidos()');
    
        // 2) Decrementar plazo en 1 para préstamos aún prestados
        $updated = DB::table('prestamos')
                     ->where('estado', 'prestado')
                     ->where('plazo', '>', 0)
                     ->decrement('plazo');
    
        $this->info("Préstamos con plazo decrementado: {$updated}");
    }
}
