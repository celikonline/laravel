<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QueryLoggingMiddleware
{
    protected $slowQueryThreshold = 100; // milisaniye cinsinden

    public function handle($request, Closure $next)
    {
        // Query log'u aktifleştir
        DB::enableQueryLog();

        $response = $next($request);

        // Tüm sorguları al
        $queries = DB::getQueryLog();

        // Her sorgu için detaylı log oluştur
        foreach ($queries as $query) {
            $sql = $query['query'];
            $bindings = $query['bindings'];
            $time = $query['time'];

            // SQL sorgusunu ve parametreleri birleştir
            $fullQuery = $this->getFullQuery($sql, $bindings);

            // Yavaş sorgu kontrolü
            if ($time > $this->slowQueryThreshold) {
                Log::channel('query')->warning("SLOW QUERY: {$fullQuery} | Time: {$time}ms");
            } else {
                Log::channel('query')->info("Query: {$fullQuery} | Time: {$time}ms");
            }
        }

        return $response;
    }

    protected function getFullQuery($sql, $bindings)
    {
        $fullQuery = $sql;
        
        foreach ($bindings as $binding) {
            $value = is_numeric($binding) ? $binding : "'" . $binding . "'";
            $fullQuery = preg_replace('/\?/', $value, $fullQuery, 1);
        }
        
        return $fullQuery;
    }
} 