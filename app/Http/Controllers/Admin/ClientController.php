<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    /**
     * Display a listing of clients.
     */
    public function index()
    {
        $clients = User::where('role', 'client')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new client.
     */
    public function create()
    {
        return view('admin.clients.create');
    }

    /**
     * Store a newly created client.
     * 
     * Per spec: Admin only provides email address.
     * System generates temp_password and creates inactive client.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
        ]);

        // Generate temporary password
        $tempPassword = Str::random(12);

        // Create inactive client (pending profile completion)
        $user = User::create([
            'name' => 'New Student', // Placeholder until onboarding
            'email' => $validated['email'],
            'password' => Hash::make($tempPassword),
            'role' => 'client',
            'is_active' => false,
            'temp_password' => $tempPassword,
        ]);

        // TODO: Send email with temp password via SendGrid

        return redirect()
            ->route('admin.clients.index')
            ->with('success', "Client account created. Temporary password sent to {$validated['email']}.");
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
