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
     * Handle student login using TUPV ID.
     */
    public function login(Request $request)
    {
        $request->validate([
            'tupv_id' => ['required', 'string', 'regex:/^TUPV-\d{2}-\d{4}$/'],
            'password' => 'required',
        ], [
            'tupv_id.required' => 'Please enter your TUPV ID.',
            'tupv_id.regex' => 'TUPV ID must be in format TUPV-XX-XXXX (e.g., TUPV-24-0001)',
        ]);

        $credentials = [
            'tupv_id' => strtoupper($request->tupv_id),
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Ensure user is a client
            if ($user->role !== 'client') {
                Auth::logout();
                return back()->withErrors([
                    'tupv_id' => 'Please use the appropriate login page for your role.',
                ]);
            }

            $request->session()->regenerate();

            // Check if profile needs completion (inactive = needs to change password & complete profile)
            if (!$user->is_active) {
                return redirect()->route('register')
                    ->with('info', 'Please change your password and complete your profile to continue.');
            }

            return redirect()->intended(route('client.welcome'));
        }

        return back()->withErrors([
            'tupv_id' => 'The provided credentials do not match our records.',
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
     * Handle admin login using Admin ID.
     */
    public function adminLogin(Request $request)
    {
        $request->validate([
            'admin_id' => 'required|string|min:3|max:20',
            'password' => 'required',
        ], [
            'admin_id.required' => 'Please enter your Admin ID.',
        ]);

        $credentials = [
            'admin_id' => strtoupper($request->admin_id),
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Ensure user is an admin
            if ($user->role !== 'admin') {
                Auth::logout();
                return back()->withErrors([
                    'admin_id' => 'Please use the appropriate login page for your role.',
                ]);
            }

            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'admin_id' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Show registration form (password change + profile completion).
     * 
     * Per spec: Student must be logged in with is_active = false.
     * They were redirected here after logging in with temp password.
     */
    public function showRegistrationForm()
    {
        // If user is logged in and inactive, show the registration form
        if (Auth::check() && !Auth::user()->is_active && Auth::user()->role === 'client') {
            return view('auth.register');
        }

        // If user is logged in and active, redirect to welcome
        if (Auth::check() && Auth::user()->is_active) {
            return redirect()->route('client.welcome');
        }

        // If not logged in, redirect to login with message
        return redirect()->route('login')
            ->with('info', 'Please log in with your temporary password first.');
    }

    /**
     * Handle student registration (password change + profile completion).
     * 
     * Per spec: Student must be logged in with is_active = false.
     * Student provides: current password (temp), new password, and profile fields.
     */
    public function register(Request $request)
    {
        // Ensure user is logged in and inactive
        if (!Auth::check() || Auth::user()->is_active || Auth::user()->role !== 'client') {
            return redirect()->route('login')
                ->with('error', 'Invalid registration attempt.');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            // Password change
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',

            // Personal information
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'nickname' => 'required|string|max:255',
            'course_year_section' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'birthplace' => 'required|string|max:255',
            'sex' => 'required|in:Male,Female',
            'contact_number' => 'required|string|max:20',
            'nationality' => 'required|string|max:100',
            'fb_account' => 'nullable|string|max:255',
            'address' => 'required|string|max:500',
            'home_address' => 'required|string|max:500',

            // Guardian information
            'guardian_name' => 'required|string|max:255',
            'guardian_relationship' => 'required|string|max:100',
            'guardian_contact' => 'required|string|max:20',

            // Terms
            'agree_terms' => 'required|accepted',
        ], [
            'current_password.required' => 'Please enter your temporary password.',
            'agree_terms.accepted' => 'You must agree to the Data Privacy Policy to proceed.',
        ]);

        // Verify current password (the temp password they logged in with)
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()
                ->withInput()
                ->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        // Update user with new password and profile data
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_active' => true,

            // Profile fields
            'nickname' => $validated['nickname'],
            'course_year_section' => $validated['course_year_section'],
            'birthdate' => $validated['birthdate'],
            'birthplace' => $validated['birthplace'],
            'sex' => $validated['sex'],
            'contact_number' => $validated['contact_number'],
            'nationality' => $validated['nationality'],
            'fb_account' => $validated['fb_account'],
            'address' => $validated['address'],
            'home_address' => $validated['home_address'],
            'guardian_name' => $validated['guardian_name'],
            'guardian_relationship' => $validated['guardian_relationship'],
            'guardian_contact' => $validated['guardian_contact'],
        ]);

        return redirect()->route('client.welcome')
            ->with('success', 'Registration completed successfully! Welcome to Paghupay.');
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
