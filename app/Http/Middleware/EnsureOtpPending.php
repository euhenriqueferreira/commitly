<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOtpPending
{
    public function handle(Request $request, Closure $next): Response {
        if (! session()->has('otp_user_id')) {
            return redirect()->route('authentication.login');
        }

        return $next($request);
    }
}
