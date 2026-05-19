<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRecoveryCodesAvailable
{
    public function handle(Request $request, Closure $next): Response {
        if (! session()->has('recovery_codes')) {
            return redirect()->route('authentication.otp-success');
        }

        return $next($request);
    }
}
