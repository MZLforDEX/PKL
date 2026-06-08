<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! $request->user() || ! $request->user()->is_approved || $request->user()->role !== $role) {
            Log::warning('RoleMiddleware: Unauthorized access', [
                'user_id' => $request->user()?->id,
                'user_role' => $request->user()?->role,
                'required_role' => $role,
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
            ]);
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
