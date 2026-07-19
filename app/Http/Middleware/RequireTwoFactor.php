<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireTwoFactor
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // 2FA switched off for this user by an administrator — skip entirely.
        if (!$user->two_factor_enabled) {
            return $next($request);
        }

        // 2FA not yet set up — redirect to setup
        if (!$user->two_factor_secret) {
            if (!$request->routeIs('2fa.setup') && !$request->routeIs('2fa.setup.confirm')) {
                return redirect()->route('2fa.setup');
            }

            return $next($request);
        }

        // 2FA set up but not verified in this session
        if (!session('2fa.verified')) {
            if (!$request->routeIs('2fa.challenge') && !$request->routeIs('2fa.verify')) {
                return redirect()->route('2fa.challenge');
            }

            return $next($request);
        }

        return $next($request);
    }
}
