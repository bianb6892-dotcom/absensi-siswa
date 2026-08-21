<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! auth()->check()) {
            abort(403, 'Unauthorized. Please login first.');
        }

        // Cek apakah role user sesuai (role:admin,guru -> ['admin', 'guru'])
        if (! in_array(auth()->user()->role, $roles, true)) {
            abort(403, 'Unauthorized access. You do not have permission to access this page.');
        }

        return $next($request);
    }
}
