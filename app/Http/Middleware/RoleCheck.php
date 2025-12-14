<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleCheck
{
    /**
     * Handle an incoming request.
     *
     * Ensures strict role segregation - users cannot access routes
     * designated for other roles.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role  The required role for this route
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->role !== $role) {
            // Redirect to appropriate dashboard based on user's actual role
            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard')
                    ->with('error', 'Access denied. You do not have permission to access that area.'),
                'counselor' => redirect()->route('counselor.dashboard')
                    ->with('error', 'Access denied. You do not have permission to access that area.'),
                'client' => redirect()->route('client.welcome')
                    ->with('error', 'Access denied. You do not have permission to access that area.'),
                default => redirect()->route('login')
                    ->with('error', 'Invalid user role.'),
            };
        }

        return $next($request);
    }
}
