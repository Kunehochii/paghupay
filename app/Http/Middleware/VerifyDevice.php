<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class VerifyDevice
{
    /**
     * Handle an incoming request.
     *
     * Implements "Trust on First Use" (TOFU) device binding for counselors.
     * - First login: Generates device token and binds to browser
     * - Subsequent logins: Validates stored token against cookie
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Only apply to counselors
        if ($user->role !== 'counselor') {
            return $next($request);
        }

        $profile = $user->counselorProfile;

        // Counselor must have a profile
        if (!$profile) {
            Auth::logout();
            return redirect('/counselor/login')->with('error', 
                'Counselor profile not found. Contact admin.');
        }

        // Case 1: First-time login (Trust on First Use)
        if (is_null($profile->device_token)) {
            $deviceToken = $this->generateDeviceToken($request);

            $profile->update([
                'device_token' => $deviceToken,
                'device_bound_at' => now()
            ]);

            // Set long-lived cookie (1 year = 525600 minutes)
            // httpOnly: true - Prevents JavaScript access (XSS protection)
            // secure: true - Only sent over HTTPS (enable in production)
            Cookie::queue('counselor_device_id', $deviceToken, 525600, '/', null, true, true);

            return $next($request);
        }

        // Case 2: Verify existing device
        $storedToken = $profile->device_token;
        $currentToken = $request->cookie('counselor_device_id');

        if ($storedToken !== $currentToken) {
            Auth::logout();
            return redirect('/counselor/login')->with('error', 
                'Unauthorized Device. This account is locked to a different device. Contact admin to reset.');
        }

        return $next($request);
    }

    /**
     * Generate a unique device token using SHA-256.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    private function generateDeviceToken(Request $request): string
    {
        return hash('sha256', 
            uniqid(mt_rand(), true) . 
            $request->userAgent() . 
            $request->ip()
        );
    }
}
