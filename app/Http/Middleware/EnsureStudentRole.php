<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStudentRole
{
    /**
     * Allow access if user has student role (via role column or Spatie).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        if ($user->role === 'student' || $user->hasRole('student')) {
            return $next($request);
        }

        abort(403, __('You do not have access to the student area.'));
    }
}
