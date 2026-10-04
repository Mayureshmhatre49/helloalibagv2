<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Refuses the public integration API over plain HTTP in production so a
 * bearer token never travels unencrypted. Local/testing stay unaffected
 * since dev environments often run without TLS.
 */
class EnsurePublicApiIsSecure
{
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->environment('production') && ! $request->secure()) {
            abort(403, 'HTTPS is required.');
        }

        return $next($request);
    }
}
