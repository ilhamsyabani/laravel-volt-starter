<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasPermission
{
    /**
     * Handle an incoming request.
     *
     * Check if user has specific permissions (for future RBAC expansion).
     *
     * Usage in routes:
     *   ->middleware('can:posts.create')
     *   ->middleware('can:users.manage')
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permission  Format: 'resource.action' (e.g., 'posts.create')
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // For now, superadmin and admin have all permissions
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return $next($request);
        }

        // TODO: Implement actual permission check
        // For now, allow regular users to access all routes
        // You can expand this with a permissions table later

        return $next($request);
    }
}
