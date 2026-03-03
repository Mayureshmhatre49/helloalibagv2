<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user() || !$request->user()->role) {
            return redirect()->route('home')->with('error', 'Please complete your registration.');
        }

        if (!in_array($request->user()->role->slug, $roles)) {
            return redirect()->route('home')->with('error', 'You do not have permission to access that page.');
        }

        return $next($request);
    }
}
