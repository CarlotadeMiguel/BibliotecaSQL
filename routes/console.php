<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('prestamos:mark-vencidos', function () {
    \DB::statement('CALL mark_vencidos()');
    $this->info('Préstamos vencidos actualizados.');
})->describe('Marca los préstamos vencidos usando el SP');
