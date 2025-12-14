<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    /**
     * Show the onboarding/profile completion form.
     */
    public function show()
    {
        $user = Auth::user();

        // If already active, redirect to welcome
        if ($user->is_active) {
            return redirect()->route('client.welcome');
        }

        return view('client.onboarding');
    }

    /**
     * Complete the profile and activate user.
     */
    public function complete(Request $request)
    {
        $validated = $request->validate([
            'nickname' => 'nullable|string|max:255',
            'course_year_section' => 'required|string|max:255',
            'birthdate' => 'required|date|before:today',
            'birthplace' => 'nullable|string|max:255',
            'sex' => 'required|in:Male,Female',
            'contact_number' => 'required|string|max:20',
            'fb_account' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:255',
            'address' => 'required|string',
            'home_address' => 'nullable|string',
            'guardian_name' => 'required|string|max:255',
            'guardian_relationship' => 'required|string|max:255',
            'guardian_contact' => 'required|string|max:20',
            'agree_terms' => 'required|accepted',
        ]);

        // Remove agree_terms from data
        unset($validated['agree_terms']);

        // Update user profile
        $user = Auth::user();
        $user->update(array_merge($validated, [
            'is_active' => true,
            'temp_password' => null, // Clear temp password after profile completion
        ]));

        return redirect()
            ->route('client.welcome')
            ->with('success', 'Profile completed successfully! Welcome to Paghupay.');
    }
}
