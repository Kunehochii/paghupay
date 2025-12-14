<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Show the welcome page (landing after login).
     */
    public function welcome()
    {
        $client = Auth::user();
        
        $upcomingAppointments = Appointment::with('counselor')
            ->where('client_id', $client->id)
            ->upcoming()
            ->take(3)
            ->get();

        return view('client.welcome', compact('upcomingAppointments'));
    }

    /**
     * Step 1: Choose a counselor.
     */
    public function chooseCounselor()
    {
        $counselors = User::where('role', 'counselor')
            ->with('counselorProfile')
            ->where('is_active', true)
            ->get();

        return view('client.booking.choose-counselor', compact('counselors'));
    }

    /**
     * Step 2: Select date and time.
     */
    public function schedule($counselorId)
    {
        $counselor = User::where('role', 'counselor')
            ->with('counselorProfile')
            ->findOrFail($counselorId);

        // Get existing appointments for this counselor (for calendar blocking)
        $bookedSlots = Appointment::where('counselor_id', $counselorId)
            ->whereIn('status', [
                Appointment::STATUS_PENDING,
                Appointment::STATUS_ACCEPTED,
            ])
            ->where('scheduled_at', '>=', now())
            ->pluck('scheduled_at')
            ->toArray();

        return view('client.booking.schedule', compact('counselor', 'bookedSlots'));
    }

    /**
     * Step 3: Enter reason for appointment.
     */
    public function reason(Request $request)
    {
        $counselorId = $request->input('counselor_id');
        $scheduledAt = $request->input('scheduled_at');

        // Store in session for the final step
        session([
            'booking.counselor_id' => $counselorId,
            'booking.scheduled_at' => $scheduledAt,
        ]);

        return view('client.booking.reason');
    }

    /**
     * Step 4: Store the appointment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $counselorId = session('booking.counselor_id');
        $scheduledAt = session('booking.scheduled_at');

        if (!$counselorId || !$scheduledAt) {
            return redirect()
                ->route('booking.choose-counselor')
                ->with('error', 'Please start the booking process again.');
        }

        $appointment = Appointment::create([
            'client_id' => Auth::id(),
            'counselor_id' => $counselorId,
            'status' => Appointment::STATUS_PENDING,
            'scheduled_at' => $scheduledAt,
            'reason' => $validated['reason'],
            'email_sent' => false,
        ]);

        // Clear booking session
        session()->forget(['booking.counselor_id', 'booking.scheduled_at']);

        // TODO: Send confirmation email via SendGrid

        return redirect()->route('booking.confirmation');
    }

    /**
     * Show confirmation page.
     */
    public function confirmation()
    {
        return view('client.booking.confirmation');
    }

    /**
     * Show client's appointments.
     */
    public function appointments()
    {
        $appointments = Appointment::with('counselor')
            ->where('client_id', Auth::id())
            ->orderBy('scheduled_at', 'desc')
            ->paginate(10);

        return view('client.appointments', compact('appointments'));
    }
}
