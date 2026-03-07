<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectToRoleDashboard
{
    /**
     * Role to dashboard route mapping.
     */
    protected array $roleDashboards = [
        'admin'   => 'admin.dashboard',
        'teacher' => 'teacher.dashboard',
        'student' => 'student.dashboard',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return $next($request);
        }

        foreach ($this->roleDashboards as $role => $routeName) {
            if ($request->user()->hasRole($role)) {
                return redirect()->route($routeName);
            }
        }

        // No role or unknown role: redirect to student dashboard as default
        return redirect()->route('student.dashboard');
    }
}
