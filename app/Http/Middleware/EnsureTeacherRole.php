<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTeacherRole
{
    /**
     * Allow access if user has teacher role (via role column or Spatie).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        if ($user->role === 'teacher' || $user->hasRole('teacher')) {
            return $next($request);
        }

        abort(403, __('You do not have access to the teacher area.'));
    }
}
