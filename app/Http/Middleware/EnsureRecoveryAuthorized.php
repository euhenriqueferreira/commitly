<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRecoveryAuthorized
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! session('recovery_authorized')) {
            return redirect()->route('authentication.recovery');
        }

        return $next($request);
    }
}
