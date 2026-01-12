<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\CaseLog;
use App\Models\CounselorProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CounselorController extends Controller
{
    /**
     * Display a listing of counselors.
     */
    public function index()
    {
        $counselors = User::where('role', 'counselor')
            ->with('counselorProfile')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.counselors.index', compact('counselors'));
    }

    /**
     * Show the form for creating a new counselor.
     */
    public function create()
    {
        return view('admin.counselors.create');
    }

    /**
     * Store a newly created counselor.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'position' => 'nullable|string|max:255',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Generate temporary password
        $tempPassword = Str::random(12);

        // Create user with counselor role
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($tempPassword),
            'role' => 'counselor',
            'is_active' => true,
        ]);

        // Handle picture upload (local storage like multer)
        $pictureUrl = null;
        if ($request->hasFile('picture')) {
            $pictureUrl = $request->file('picture')
                ->store('uploads/counselors', 'public');
        }

        // Create counselor profile with device_token NULL
        CounselorProfile::create([
            'user_id' => $user->id,
            'position' => $validated['position'],
            'picture_url' => $pictureUrl,
            'temp_password' => $tempPassword,
            'device_token' => null, // Set on first login
        ]);

        // TODO: Send email with temp password via SendGrid

        return redirect()
            ->route('admin.counselors.index')
            ->with('success', "Counselor {$user->name} created successfully. Temporary password: {$tempPassword}");
    }

    /**
     * Show the form for editing the specified counselor.
     */
    public function edit($id)
    {
        $counselor = User::with('counselorProfile')
            ->where('role', 'counselor')
            ->findOrFail($id);

        return view('admin.counselors.edit', compact('counselor'));
    }

    /**
     * Update the specified counselor.
     */
    public function update(Request $request, $id)
    {
        $counselor = User::with('counselorProfile')
            ->where('role', 'counselor')
            ->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'position' => 'nullable|string|max:255',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $counselor->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        $profileData = ['position' => $validated['position']];

        // Handle picture upload
        if ($request->hasFile('picture')) {
            // Delete old picture if exists
            if ($counselor->counselorProfile->picture_url) {
                Storage::disk('public')->delete($counselor->counselorProfile->picture_url);
            }
            $profileData['picture_url'] = $request->file('picture')
                ->store('uploads/counselors', 'public');
        }

        $counselor->counselorProfile->update($profileData);

        return redirect()
            ->route('admin.counselors.index')
            ->with('success', "Counselor {$counselor->name} updated successfully.");
    }

    /**
     * Remove the specified counselor.
     * Note: All related records (appointments, case_logs, etc.) will be
     * automatically deleted due to CASCADE foreign keys.
     */
    public function destroy($id)
    {
        $counselor = User::with('counselorProfile')
            ->where('role', 'counselor')
            ->findOrFail($id);

        // Get counts for logging/confirmation
        $appointmentsCount = Appointment::where('counselor_id', $counselor->id)->count();
        $caseLogsCount = CaseLog::where('counselor_id', $counselor->id)->count();

        // Delete picture if exists
        if ($counselor->counselorProfile?->picture_url) {
            Storage::disk('public')->delete($counselor->counselorProfile->picture_url);
        }

        $name = $counselor->name;
        $counselor->delete(); // Cascades to counselor_profile, appointments, case_logs

        return redirect()
            ->route('admin.counselors.index')
            ->with('success', "Counselor {$name} deleted successfully. {$appointmentsCount} appointment(s) and {$caseLogsCount} case log(s) were also removed.");
    }

    /**
     * Reset device lock for a counselor.
     * 
     * Use Cases:
     * - Counselor cleared browser cookies/cache
     * - Counselor switched to a different workstation
     * - Device was reformatted/replaced
     * - Troubleshooting login issues
     */
    public function resetDevice($id)
    {
        $counselor = User::with('counselorProfile')
            ->where('role', 'counselor')
            ->findOrFail($id);

        $profile = $counselor->counselorProfile;

        // Reset device lock
        $profile->update([
            'device_token' => null,
            'device_bound_at' => null
        ]);

        // Optional: Log this action for audit trail
        // AuditLog::create([
        //     'admin_id' => auth()->id(),
        //     'counselor_id' => $counselor->id,
        //     'action' => 'device_reset',
        //     'timestamp' => now(),
        // ]);

        return redirect()
            ->route('admin.counselors.index')
            ->with('success', "Device lock reset for {$counselor->name}. They can now log in from a new device.");
    }
}
