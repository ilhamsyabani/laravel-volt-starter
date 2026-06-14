<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsOwner
{
    /**
     * Handle an incoming request.
     *
     * Ensure the authenticated user owns the resource being accessed.
     * Use with Folio route parameters.
     *
     * Usage:
     *   Route::get('/posts/{post}/edit')
     *       ->middleware('owner:post')
     *
     * Your model needs an `user_id` or `created_by` column.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $resourceParam  The route parameter name (e.g., 'post')
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $resourceParam): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Superadmin bypasses ownership check
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        $resource = $request->route($resourceParam);

        if (!$resource) {
            return $next($request);
        }

        // Check ownership via user_id or created_by
        $isOwner = $resource->user_id === $user->id
            || $resource->created_by === $user->id
            || $resource->author_id === $user->id;

        if ($isOwner) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return redirect()->back()->with('error', 'Anda tidak memiliki akses ke resource ini.');
    }
}
