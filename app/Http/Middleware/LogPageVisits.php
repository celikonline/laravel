<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class LogPageVisits
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Sadece önemli route'ları logla
        if ($this->shouldLog($request)) {
            // POST verilerini güvenli bir şekilde al (hassas verileri filtrele)
            $postData = $request->except(['password', 'password_confirmation', 'credit_card', 'cvv']);

            // Ziyaret edilen sayfanın bilgilerini logla
            $output = sprintf(
                "\nURL: %s\nMethod: %s\nUser: %s\nTime: %s\n",
                $request->fullUrl(),
                $request->method(),
                auth()->check() ? auth()->user()->id : 'guest',
                now()->format('Y-m-d H:i:s')
            );

            // Eğer POST isteği ise ve veri varsa
            if ($request->isMethod('POST') && !empty($postData)) {
                $output .= "POST Data:\n";
                foreach ($postData as $key => $value) {
                    if (is_array($value)) {
                        $output .= sprintf("%s: %s\n", $key, json_encode($value, JSON_UNESCAPED_UNICODE));
                    } else {
                        $output .= sprintf("%s: %s\n", $key, $value);
                    }
                }
            }

            $output .= "------------------------\n";

            // Terminal'e yazdır
            file_put_contents('php://stdout', $output);

            // Ayrıca Laravel log'una da kaydedelim
            Log::info('Sayfa ziyareti', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'user' => auth()->check() ? auth()->user()->id : 'guest',
                'time' => now()->format('Y-m-d H:i:s'),
                'post_data' => $postData
            ]);
        }

        return $response;
    }

    protected function shouldLog($request)
    {
        // Loglanması gereken route'ları belirle
        $importantRoutes = [
            'payment',
            'admin',
            'reports',
            'audit-logs'
        ];

        foreach ($importantRoutes as $route) {
            if (strpos($request->path(), $route) !== false) {
                return true;
            }
        }

        return false;
    }
} 