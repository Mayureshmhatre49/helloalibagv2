<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Audits every call to the public integration API so a compromised or
 * misbehaving token is visible without digging through the general app log.
 */
class LogPublicApiAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $token = $request->user()?->currentAccessToken();

        Log::channel('public_api')->info('public_api.access', [
            'token_id' => $token?->id,
            'token_name' => $token?->name,
            'ip' => $request->ip(),
            'method' => $request->method(),
            'path' => $request->path(),
            'status' => $response->getStatusCode(),
            'timestamp' => now()->toIso8601String(),
        ]);

        return $response;
    }
}
