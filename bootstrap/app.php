<?php

use App\Http\Middleware\EnsureOtpPending;
use App\Http\Middleware\EnsureRecoveryAuthorized;
use App\Http\Middleware\EnsureRecoveryCodesAvailable;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'otp-pending' => EnsureOtpPending::class,
            'recovery-codes-available' => EnsureRecoveryCodesAvailable::class,
            'recovery-authorized' =>EnsureRecoveryAuthorized::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
