<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show student login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Show counselor login form.
     */
    public function showCounselorLoginForm()
    {
        return view('auth.counselor-login');
    }

    /**
     * Show admin login form.
     */
    public function showAdminLoginForm()
    {
        return view('auth.admin-login');
    }

    /**
     * Handle student login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Ensure user is a client
            if ($user->role !== 'client') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Please use the appropriate login page for your role.',
                ]);
            }

            $request->session()->regenerate();

            // Check if profile needs completion
            if (!$user->is_active) {
                return redirect()->route('client.onboarding');
            }

            return redirect()->intended(route('client.welcome'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Handle counselor login.
     */
    public function counselorLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Ensure user is a counselor
            if ($user->role !== 'counselor') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Please use the appropriate login page for your role.',
                ]);
            }

            $request->session()->regenerate();
            return redirect()->intended(route('counselor.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Handle admin login.
     */
    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Ensure user is an admin
            if ($user->role !== 'admin') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Please use the appropriate login page for your role.',
                ]);
            }

            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Show registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle student registration.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'agree_terms' => 'required|accepted',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'client',
            'is_active' => false, // Requires profile completion
        ]);

        Auth::login($user);

        return redirect()->route('client.onboarding');
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
