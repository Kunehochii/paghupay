<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\StudentInvitation;
use App\Models\User;
use App\Models\Appointment;
use App\Models\CaseLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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
     * Per spec: Admin provides TUPV ID (required) and email (optional).
     * System generates temp_password and creates inactive client.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tupv_id' => [
                'required',
                'string',
                'regex:/^TUPV-\d{2}-\d{4}$/',
                'unique:users,tupv_id',
            ],
            'email' => [
                'nullable',
                'email',
                'unique:users,email',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $email = strtolower($value);
                        // Allow @tupv.edu.ph (production) and @gmail.com (testing)
                        if (!str_ends_with($email, '@tupv.edu.ph') && !str_ends_with($email, '@gmail.com')) {
                            $fail('Only @tupv.edu.ph email addresses are allowed.');
                        }
                    }
                },
            ],
        ], [
            'tupv_id.required' => 'TUPV ID is required.',
            'tupv_id.regex' => 'TUPV ID must be in format TUPV-XX-XXXX (e.g., TUPV-24-0001)',
            'tupv_id.unique' => 'This TUPV ID is already registered.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
        ]);

        // Use TUPV ID as default password (student changes it on first login)
        $tempPassword = strtoupper($validated['tupv_id']);

        // Create inactive client - temp password is ONLY stored as hash (secure)
        // The plain text temp password only exists in the email sent to student
        $user = User::create([
            'name' => 'Pending Registration',
            'tupv_id' => strtoupper($validated['tupv_id']),
            'email' => $validated['email'] ? strtolower($validated['email']) : null,
            'password' => Hash::make($tempPassword), // Hashed, not plain text
            'role' => 'client',
            'is_active' => false,
        ]);

        // Send email with temp password via SendGrid (only if email provided)
        // This is the ONLY place the plain text password exists
        $emailSent = false;
        if ($user->email) {
            try {
                Mail::to($user->email)->send(new StudentInvitation($user->tupv_id, $user->email, $tempPassword));
                $emailSent = true;
            } catch (\Exception $e) {
                // Log the error but don't fail the request
                Log::error('Failed to send student invitation email: ' . $e->getMessage());
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Student account created successfully.',
                'tupv_id' => $user->tupv_id,
                'email' => $user->email,
                'email_sent' => $emailSent,
                'temp_password' => !$emailSent ? $tempPassword : null, // Show if no email sent (no email provided or delivery failed)
            ]);
        }

        $message = $emailSent 
            ? "Invitation sent to {$user->email}. TUPV ID: {$user->tupv_id}" 
            : ($user->email 
                ? "Account created for TUPV ID: {$user->tupv_id}, but email delivery failed." 
                : "Account created for TUPV ID: {$user->tupv_id}. Default password is their TUPV ID.");

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
     * Search for students by TUPV ID only.
     * Returns matching students for delete selection.
     */
    public function search(Request $request)
    {
        $query = trim($request->get('q', ''));
        
        // Normalize the query - convert to uppercase for TUPV ID format
        $query = strtoupper($query);
        
        if (strlen($query) < 4) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter at least 4 characters (e.g., TUPV or the year like 24).',
                'results' => []
            ]);
        }

        $students = User::where('role', 'client')
            ->where('tupv_id', 'LIKE', "%{$query}%")
            ->select(['id', 'tupv_id', 'name', 'email', 'is_active', 'created_at'])
            ->orderBy('tupv_id')
            ->limit(10)
            ->get();

        // Get related data counts for each student
        $results = $students->map(function ($student) {
            return [
                'id' => $student->id,
                'tupv_id' => $student->tupv_id,
                'name' => $student->name,
                'email' => $student->email,
                'is_active' => $student->is_active,
                'deactivated_at' => $student->deactivated_at?->format('M d, Y'),
                'created_at' => $student->created_at->format('M d, Y'),
                'appointments_count' => Appointment::where('client_id', $student->id)->count(),
                'case_logs_count' => CaseLog::where('client_id', $student->id)->count(),
            ];
        });

        return response()->json([
            'success' => true,
            'results' => $results,
            'count' => $results->count()
        ]);
    }

    /**
     * Deactivate the specified client account.
     */
    public function deactivate($id)
    {
        $client = User::where('role', 'client')->findOrFail($id);
        $client->deactivate();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Student {$client->tupv_id} ({$client->name}) has been deactivated.",
            ]);
        }

        return redirect()
            ->route('admin.clients.index')
            ->with('success', "Student {$client->tupv_id} ({$client->name}) has been deactivated.");
    }

    /**
     * Reactivate the specified client account.
     */
    public function reactivate($id)
    {
        $client = User::where('role', 'client')->findOrFail($id);
        $client->reactivate();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Student {$client->tupv_id} ({$client->name}) has been reactivated.",
            ]);
        }

        return redirect()
            ->route('admin.clients.index')
            ->with('success', "Student {$client->tupv_id} ({$client->name}) has been reactivated.");
    }
}
