<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Виключаємо CSRF для API роутів WayForPay
        $middleware->validateCsrfTokens(except: [
            'api/orders/payment/callback',
            'api/test/callback', // для тестування
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withSchedule(function (Schedule $schedule) {
        // Синхронізація Nova Poshta щотижня в неділю о 01:00
        $schedule->command('novaposhta:sync')
            ->timezone('Europe/Kiev')
            ->weeklyOn(0, '01:00')  // 0 = неділя
            ->onOneServer()         // Запускати тільки на одному сервері (важливо для кластерів)
            ->withoutOverlapping(120) // Запобігати перекриванню на 2 години
            ->runInBackground()
            ->sendOutputTo(storage_path('logs/novaposhta-sync.log'))
            ->before(function () {
                Log::info('Starting Nova Poshta synchronization');
            })
            ->after(function () {
                Log::info('Nova Poshta synchronization completed');
            })
            ->onFailure(function () {
                Log::error('Nova Poshta synchronization failed');
                // Можна додати Telegram/Slack сповіщення
            });
    })
    ->create();
