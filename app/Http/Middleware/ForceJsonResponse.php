<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Forces every response on this route (errors included) to be JSON, so an
 * integration client that forgets to send "Accept: application/json" still
 * gets a clean 401/403/404/422 instead of a login redirect or an HTML page.
 * Must run before auth/ability/validation middleware.
 */
class ForceJsonResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}
