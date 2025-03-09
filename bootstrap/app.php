<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\IsSuperadmin;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsTeacher;
use App\Http\Middleware\isVPAA;
use App\Http\Middleware\isRegistrar;
use App\Http\Middleware\isHumanResource;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'is_superadmin' => IsSuperadmin::class,
            'is_admin' => IsAdmin::class,
            'is_teacher' => IsTeacher::class,
            'is_vpaa' => IsVPAA::class,
            'is_registrar' => isRegistrar::class,
            'is_hr' => IsHumanResource::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
