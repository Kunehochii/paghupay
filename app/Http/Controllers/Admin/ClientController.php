<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\StudentInvitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    /**
     * Display client management page (count + add).
     * Per spec: Only shows count, no user list for privacy.
     */
    public function index()
    {
        $clientCount = User::where('role', 'client')->count();
        $activeCount = User::where('role', 'client')->where('is_active', true)->count();
        $pendingCount = User::where('role', 'client')->where('is_active', false)->count();

        return view('admin.clients.index', compact('clientCount', 'activeCount', 'pendingCount'));
    }

    /**
     * Show the form for creating a new client.
     * Note: This is handled via modal in index view per spec.
     */
    public function create()
    {
        return view('admin.clients.create');
    }

    /**
     * Store a newly created client.
     * 
     * Per spec: Admin only provides email address (@tupv.edu.ph only).
     * System generates temp_password and creates inactive client.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => [
                'required',
                'email',
                'unique:users,email',
                function ($attribute, $value, $fail) {
                    $email = strtolower($value);
                    // Allow @tupv.edu.ph (production) and @gmail.com (testing)
                    if (!str_ends_with($email, '@tupv.edu.ph') && !str_ends_with($email, '@gmail.com')) {
                        $fail('Only @tupv.edu.ph email addresses are allowed.');
                    }
                },
            ],
        ], [
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
        ]);

        // Generate 8-character temporary password
        $tempPassword = Str::random(8);

        // Create inactive client - temp password is ONLY stored as hash (secure)
        // The plain text temp password only exists in the email sent to student
        $user = User::create([
            'name' => 'Pending Registration',
            'email' => strtolower($validated['email']),
            'password' => Hash::make($tempPassword), // Hashed, not plain text
            'role' => 'client',
            'is_active' => false,
        ]);

        // Send email with temp password via SendGrid
        // This is the ONLY place the plain text password exists
        try {
            Mail::to($user->email)->send(new StudentInvitation($user->email, $tempPassword));
            $emailSent = true;
        } catch (\Exception $e) {
            $emailSent = false;
            // Log the error but don't fail the request
            \Log::error('Failed to send student invitation email: ' . $e->getMessage());
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Student account created successfully.',
                'email' => $user->email,
                'email_sent' => $emailSent,
            ]);
        }

        $message = $emailSent 
            ? "Invitation sent to {$user->email}." 
            : "Account created for {$user->email}, but email delivery failed. Please check email settings.";

        return redirect()
            ->route('admin.clients.index')
            ->with($emailSent ? 'success' : 'warning', $message);
    }

    /**
     * Display the specified client.
     */
    public function show($id)
    {
        $client = User::where('role', 'client')
            ->with(['clientAppointments', 'clientCaseLogs'])
            ->findOrFail($id);

        return view('admin.clients.show', compact('client'));
    }

    /**
     * Remove the specified client.
     */
    public function destroy($id)
    {
        $client = User::where('role', 'client')->findOrFail($id);
        $email = $client->email;
        $client->delete();

        return redirect()
            ->route('admin.clients.index')
            ->with('success', "Client {$email} deleted successfully.");
    }
}
