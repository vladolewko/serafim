<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Support\Facades\Log;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Усі можливі варіанти для платіжних маршрутів
        'payment/*',
        '/payment/*',
        '*/payment/*',
        'payment/success',
        '/payment/success',
        'payment/callback',
        '/payment/callback',
        'payment/failed',
        '/payment/failed',
        // Додайте повні URL для надійності
        'http://localhost:8888/payment/*',
        'https://localhost:8888/payment/*',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle($request, \Closure $next)
    {
        // Спеціальна перевірка для всіх платіжних запитів
        $path = $request->path();
        $url = $request->url();

        if (str_contains($path, 'payment') || str_contains($url, 'payment')) {
            Log::info('Payment request detected - bypassing CSRF', [
                'method' => $request->method(),
                'path' => $path,
                'url' => $url,
                'full_url' => $request->fullUrl(),
                'referer' => $request->header('referer'),
                'user_agent' => substr($request->userAgent(), 0, 100)
            ]);

            // Повністю пропускаємо CSRF перевірку для всіх платіжних запитів
            return $next($request);
        }

        return parent::handle($request, $next);
    }

    /**
     * Determine if the request has a URI that should pass through CSRF verification.
     */
    protected function inExceptArray($request)
    {
        // Додаткова перевірка для платіжних маршрутів
        $path = $request->path();
        if (str_contains($path, 'payment')) {
            Log::info('Payment path detected in inExceptArray', [
                'path' => $path,
                'result' => true
            ]);
            return true;
        }

        return parent::inExceptArray($request);
    }
}
