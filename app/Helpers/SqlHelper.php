<?php

namespace App\Helpers;
// app/Helpers/SqlHelper.php
use Illuminate\Support\Facades\DB;

class SqlHelper
{
    public static function enableQueryLog()
    {
        DB::enableQueryLog();
    }

    public static function dumpQueries()
    {
        if (app()->environment('local')) {
            $queries = DB::getQueryLog();
            foreach ($queries as $query) {
                $sql = str_replace_array('?', $query['bindings'], $query['query']);
                echo "<pre>SQL: {$sql}\nTime: {$query['time']}ms</pre>";
            }
        }
    }

    public static function toSqlWithBindings($query)
    {
        return vsprintf(str_replace('?', '%s', $query->toSql()), collect($query->getBindings())->map(function ($binding) {
            return is_numeric($binding) ? $binding : "'{$binding}'";
        })->toArray());
    }
}
