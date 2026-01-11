<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\AppointmentConfirmation;
use App\Models\Appointment;
use App\Models\BlockedDate;
use App\Models\TimeSlot;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    /**
     * Show the welcome page (landing after login).
     */
    public function welcome()
    {
        $client = Auth::user();
        
        // Check if user has agreed to confidentiality
        if (!$client->agreed_to_confidentiality) {
            return redirect()->route('client.agreement');
        }
        
        $upcomingAppointments = Appointment::with('counselor')
            ->where('client_id', $client->id)
            ->upcoming()
            ->take(3)
            ->get();

        return view('client.welcome', compact('upcomingAppointments'));
    }

    /**
     * Show the confidentiality agreement page.
     */
    public function showAgreement()
    {
        $client = Auth::user();
        
        // If already agreed, redirect to welcome
        if ($client->agreed_to_confidentiality) {
            return redirect()->route('client.welcome');
        }
        
        return view('client.agreement');
    }

    /**
     * Accept the confidentiality agreement.
     */
    public function acceptAgreement(Request $request)
    {
        $client = Auth::user();
        
        $client->update([
            'agreed_to_confidentiality' => true,
            'agreed_at' => now(),
        ]);
        
        return redirect()->route('client.welcome');
    }

    /**
     * Display the booking start page.
     */
    public function index()
    {
        return view('client.booking.index');
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
     * Store selected counselor and redirect to schedule.
     */
    public function selectCounselor(Request $request)
    {
        $request->validate([
            'counselor_id' => 'required|exists:users,id',
        ]);

        $counselor = User::where('role', 'counselor')->findOrFail($request->counselor_id);
        
        // Store in session
        $request->session()->put('booking.counselor_id', $counselor->id);

        return redirect()->route('booking.schedule', $counselor);
    }

    /**
     * Step 2: Select date and time.
     */
    public function schedule($counselorId)
    {
        $counselor = User::where('role', 'counselor')
            ->with('counselorProfile')
            ->findOrFail($counselorId);

        // Get active time slots grouped by type
        $morningSlots = TimeSlot::active()->morning()->get();
        $afternoonSlots = TimeSlot::active()->afternoon()->get();

        // Get blocked dates for the calendar
        $blockedDates = BlockedDate::getBlockedDatesArray();

        // Get dates that are fully booked for this counselor
        $bookedDates = $this->getFullyBookedDates($counselor->id);

        // Get existing appointments for this counselor (for calendar blocking)
        $bookedSlots = Appointment::where('counselor_id', $counselorId)
            ->whereIn('status', [
                Appointment::STATUS_PENDING,
                Appointment::STATUS_ACCEPTED,
            ])
            ->where('scheduled_at', '>=', now())
            ->pluck('scheduled_at')
            ->toArray();

        return view('client.booking.schedule', compact(
            'counselor',
            'morningSlots',
            'afternoonSlots',
            'blockedDates',
            'bookedDates',
            'bookedSlots'
        ));
    }

    /**
     * Store selected schedule and redirect to reason.
     */
    public function selectSchedule(Request $request, User $counselor)
    {
        // DEBUG: Log incoming request
        Log::info('=== SELECT SCHEDULE - INCOMING REQUEST ===', [
            'raw_scheduled_date' => $request->scheduled_date,
            'raw_time_slot_id' => $request->time_slot_id,
            'all_input' => $request->all(),
            'server_now' => now()->toDateTimeString(),
            'server_today' => today()->toDateString(),
        ]);

        $request->validate([
            'scheduled_date' => 'required|date|after_or_equal:today',
            'time_slot_id' => 'required|exists:time_slots,id',
        ]);

        $scheduledDate = Carbon::parse($request->scheduled_date);
        
        // DEBUG: Log parsed date
        Log::info('=== SELECT SCHEDULE - PARSED DATE ===', [
            'input_date_string' => $request->scheduled_date,
            'parsed_carbon' => $scheduledDate->toDateTimeString(),
            'parsed_date_only' => $scheduledDate->toDateString(),
        ]);

        // Check if it's a weekend
        if ($scheduledDate->isWeekend()) {
            return back()->with('error', 'Appointments cannot be booked on weekends.');
        }

        // Check if the date is blocked
        if (BlockedDate::isBlocked($request->scheduled_date)) {
            return back()->with('error', 'This date is not available for booking.');
        }

        // Check if this slot is already taken for this counselor on this date
        $timeSlot = TimeSlot::findOrFail($request->time_slot_id);
        $existingAppointment = Appointment::where('counselor_id', $counselor->id)
            ->whereDate('scheduled_at', $request->scheduled_date)
            ->whereTime('scheduled_at', $timeSlot->start_time)
            ->whereNotIn('status', ['cancelled'])
            ->exists();

        if ($existingAppointment) {
            return back()->with('error', 'This time slot is already booked. Please select another.');
        }

        // Store in session
        $request->session()->put('booking.counselor_id', $counselor->id);
        $request->session()->put('booking.scheduled_date', $request->scheduled_date);
        $request->session()->put('booking.time_slot_id', $request->time_slot_id);
        
        // DEBUG: Log session storage
        Log::info('=== SELECT SCHEDULE - SESSION STORED ===', [
            'stored_date' => $request->session()->get('booking.scheduled_date'),
        ]);

        return redirect()->route('booking.reason');
    }

    /**
     * Step 3: Enter reason for appointment.
     */
    public function reason(Request $request)
    {
        // Validate that we have the required session data
        if (!$request->session()->has('booking.counselor_id') || 
            !$request->session()->has('booking.scheduled_date') ||
            !$request->session()->has('booking.time_slot_id')) {
            return redirect()->route('booking.choose-counselor')
                ->with('error', 'Please select a counselor and schedule first.');
        }

        $counselor = User::find($request->session()->get('booking.counselor_id'));
        $timeSlot = TimeSlot::find($request->session()->get('booking.time_slot_id'));
        $scheduledDate = $request->session()->get('booking.scheduled_date');

        return view('client.booking.reason', compact('counselor', 'timeSlot', 'scheduledDate'));
    }

    /**
     * Step 4: Store the appointment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reason' => 'required|string|min:10|max:1000',
        ]);

        // Validate session data
        if (!$request->session()->has('booking.counselor_id') || 
            !$request->session()->has('booking.scheduled_date') ||
            !$request->session()->has('booking.time_slot_id')) {
            return redirect()->route('booking.choose-counselor')
                ->with('error', 'Session expired. Please start the booking process again.');
        }

        $counselorId = $request->session()->get('booking.counselor_id');
        $scheduledDate = $request->session()->get('booking.scheduled_date');
        $timeSlotId = $request->session()->get('booking.time_slot_id');

        // DEBUG: Log session data before creating appointment
        Log::info('=== STORE APPOINTMENT - SESSION DATA ===', [
            'counselor_id' => $counselorId,
            'scheduled_date_from_session' => $scheduledDate,
            'time_slot_id' => $timeSlotId,
        ]);

        $timeSlot = TimeSlot::findOrFail($timeSlotId);
        $counselor = User::findOrFail($counselorId);

        // DEBUG: Log time slot info
        Log::info('=== STORE APPOINTMENT - TIME SLOT ===', [
            'time_slot_start_time' => $timeSlot->start_time,
            'time_slot_raw' => $timeSlot->getOriginal('start_time'),
        ]);

        // Combine date and time
        $scheduledAt = Carbon::parse($scheduledDate)->setTimeFromTimeString($timeSlot->start_time);

        // DEBUG: Log final scheduled_at
        Log::info('=== STORE APPOINTMENT - FINAL DATETIME ===', [
            'input_date' => $scheduledDate,
            'input_time' => $timeSlot->start_time,
            'combined_scheduled_at' => $scheduledAt->toDateTimeString(),
        ]);

        $appointment = Appointment::create([
            'client_id' => Auth::id(),
            'counselor_id' => $counselorId,
            'status' => Appointment::STATUS_PENDING,
            'scheduled_at' => $scheduledAt,
            'reason' => $validated['reason'],
            'email_sent' => false,
        ]);

        // DEBUG: Log created appointment
        Log::info('=== STORE APPOINTMENT - CREATED ===', [
            'appointment_id' => $appointment->id,
            'saved_scheduled_at' => $appointment->scheduled_at,
            'saved_scheduled_at_raw' => $appointment->getOriginal('scheduled_at'),
        ]);

        // Send confirmation email
        try {
            Mail::to(Auth::user()->email)->send(new AppointmentConfirmation($appointment));
            $appointment->update(['email_sent' => true]);
        } catch (\Exception $e) {
            // Log the error but don't fail the booking
            Log::error('Failed to send appointment confirmation email: ' . $e->getMessage());
        }

        // Clear booking session
        session()->forget(['booking.counselor_id', 'booking.scheduled_date', 'booking.time_slot_id']);

        // Store appointment ID for thank you page
        $request->session()->put('last_appointment_id', $appointment->id);

        return redirect()->route('booking.thankyou');
    }

    /**
     * Show thank you / confirmation page.
     */
    public function thankyou(Request $request)
    {
        $appointmentId = $request->session()->get('last_appointment_id');
        
        if (!$appointmentId) {
            return redirect()->route('booking.index');
        }

        $appointment = Appointment::with(['counselor', 'client'])->find($appointmentId);

        if (!$appointment || $appointment->client_id !== Auth::id()) {
            return redirect()->route('booking.index');
        }

        return view('client.booking.thankyou', compact('appointment'));
    }

    /**
     * Show confirmation page (legacy route).
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

    /**
     * Get dates that are fully booked for a counselor.
     */
    private function getFullyBookedDates(int $counselorId): array
    {
        $totalSlots = TimeSlot::active()->count();
        
        if ($totalSlots === 0) {
            return [];
        }

        // Get appointment counts per date for the next 3 months
        $startDate = Carbon::today();
        $endDate = Carbon::today()->addMonths(3);

        $appointmentCounts = Appointment::where('counselor_id', $counselorId)
            ->whereNotIn('status', ['cancelled'])
            ->whereBetween('scheduled_at', [$startDate, $endDate])
            ->selectRaw('DATE(scheduled_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        // Return dates where all slots are taken
        $fullyBooked = [];
        foreach ($appointmentCounts as $date => $count) {
            if ($count >= $totalSlots) {
                $fullyBooked[] = $date;
            }
        }

        return $fullyBooked;
    }

    /**
     * API endpoint to get available time slots for a specific date and counselor.
     */
    public function getAvailableSlots(Request $request, User $counselor)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
        ]);

        $date = $request->date;

        // Get all active time slots
        $allSlots = TimeSlot::active()->get();

        // Get booked slots for this date and counselor
        $bookedSlots = Appointment::where('counselor_id', $counselor->id)
            ->whereDate('scheduled_at', $date)
            ->whereNotIn('status', ['cancelled'])
            ->get()
            ->map(function ($appointment) {
                return Carbon::parse($appointment->scheduled_at)->format('H:i:s');
            })
            ->toArray();

        // Mark slots as available or not
        $availableSlots = $allSlots->map(function ($slot) use ($bookedSlots) {
            return [
                'id' => $slot->id,
                'type' => $slot->type,
                'start_time' => $slot->start_time,
                'end_time' => $slot->end_time,
                'formatted_time' => $slot->formatted_time,
                'is_available' => !in_array($slot->start_time, $bookedSlots),
            ];
        });

        return response()->json([
            'morning' => $availableSlots->where('type', 'morning')->values(),
            'afternoon' => $availableSlots->where('type', 'afternoon')->values(),
        ]);
    }
}
