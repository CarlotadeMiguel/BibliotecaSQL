<?php

namespace App\Providers;
// app/Providers/AppServiceProvider.php

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (config('app.debug')) {
            DB::listen(function ($query) {
                Log::channel('sql')->info('SQL Query Executed', [
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                    'time' => $query->time . 'ms',
                    'controller' => request()->route() ? request()->route()->getActionName() : 'Unknown'
                ]);
            });
        }
    }
}
