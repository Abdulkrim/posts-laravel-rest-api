<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\VerifyCsrfToken; 
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // إضافة Middleware الخاص بتحديد اللغة
    $middleware->append(\App\Http\Middleware\LocalizationMiddleware::class);
    // استثناء CSRF لبعض المسارات
    // $middleware->remove(VerifyCsrfToken::class); 
    $middleware->validateCsrfTokens(
        except : [
        'api/*'
        ]
    );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();