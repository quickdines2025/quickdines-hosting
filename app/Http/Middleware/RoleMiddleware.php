<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect('login');
        }

        $user = auth()->user();

        \Log::debug('RoleMiddleware check', [
            'path' => $request->path(),
            'user_id' => $user->id ?? null,
            'role' => $user->role ?? null,
            'required_roles' => $roles,
        ]);

        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        return abort(403, 'Unauthorized action.');
    }
}
