<?php

namespace App\Http;

use App\Http\Middleware\Cors;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\TrimStrings;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Http\Middleware\TrustProxies;

class Kernel extends HttpKernel
{
    protected $middleware = [
        CheckForMaintenanceMode::class,
        ValidatePostSize::class,
        TrimStrings::class,
        ConvertEmptyStringsToNull::class,
        TrustProxies::class,
        Cors::class,
    ];
    protected $routeMiddleware = [
        // ... outros middlewares
        'auth.admin' => \App\Http\Middleware\AdminMiddleware::class,
        'auth.user' => \App\Http\Middleware\UserMiddleware::class,
    ];
}
