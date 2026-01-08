<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use App\Mail\AppointmentCancelled;
use App\Models\Appointment;
use App\Models\CancelReason;
use App\Models\CaseLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AppointmentController extends Controller
{
    /**
     * Display the appointments page with calendar.
     */
    public function index(Request $request)
    {
        $counselor = Auth::user();
        $selectedDate = $request->query('date');
        $selectedMonth = $request->query('month');

        // Parse current month for calendar
        $currentMonth = $selectedMonth 
            ? Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth()
            : now()->startOfMonth();
        
        $prevMonth = $currentMonth->copy()->subMonth();
        $nextMonth = $currentMonth->copy()->addMonth();

        // Today's appointments
        $todayAppointments = Appointment::with(['client', 'caseLog'])
            ->where('counselor_id', $counselor->id)
            ->today()
            ->whereIn('status', [
                Appointment::STATUS_PENDING,
                Appointment::STATUS_ACCEPTED,
            ])
            ->orderBy('scheduled_at')
            ->get();

        // Selected date appointments
        $selectedDateAppointments = collect();
        if ($selectedDate) {
            $selectedDateAppointments = Appointment::with(['client', 'caseLog'])
                ->where('counselor_id', $counselor->id)
                ->whereDate('scheduled_at', $selectedDate)
                ->orderBy('scheduled_at')
                ->get();
        }

        // Pending appointments (upcoming)
        $pendingAppointments = Appointment::with('client')
            ->where('counselor_id', $counselor->id)
            ->pending()
            ->where('scheduled_at', '>', now())
            ->orderBy('scheduled_at')
            ->get();

        // Stats
        $pendingCount = Appointment::where('counselor_id', $counselor->id)
            ->pending()
            ->whereMonth('scheduled_at', now()->month)
            ->whereYear('scheduled_at', now()->year)
            ->count();

        $monthlyCount = Appointment::where('counselor_id', $counselor->id)
            ->whereMonth('scheduled_at', $currentMonth->month)
            ->whereYear('scheduled_at', $currentMonth->year)
            ->count();

        // Build calendar days
        $calendarDays = $this->buildCalendarDays($currentMonth, $counselor->id);

        return view('counselor.appointments.index', compact(
            'todayAppointments',
            'selectedDateAppointments',
            'pendingAppointments',
            'selectedDate',
            'currentMonth',
            'prevMonth',
            'nextMonth',
            'calendarDays',
            'pendingCount',
            'monthlyCount'
        ));
    }

    /**
     * Build calendar days array for the month view.
     */
    private function buildCalendarDays(Carbon $month, int $counselorId): array
    {
        $startOfMonth = $month->copy()->startOfMonth();
        $endOfMonth = $month->copy()->endOfMonth();
        
        // Start from the beginning of the week containing the first day
        $startDate = $startOfMonth->copy()->startOfWeek(Carbon::SUNDAY);
        // End at the end of the week containing the last day
        $endDate = $endOfMonth->copy()->endOfWeek(Carbon::SATURDAY);

        // Get all appointments for the visible range
        $appointments = Appointment::with('client')
            ->where('counselor_id', $counselorId)
            ->whereBetween('scheduled_at', [$startDate, $endDate->endOfDay()])
            ->orderBy('scheduled_at')
            ->get()
            ->groupBy(function ($apt) {
                return $apt->scheduled_at->format('Y-m-d');
            });

        $days = [];
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            $dateKey = $currentDate->format('Y-m-d');
            $days[] = [
                'date' => $dateKey,
                'dayNumber' => $currentDate->day,
                'isToday' => $currentDate->isToday(),
                'isCurrentMonth' => $currentDate->month === $month->month,
                'isWeekend' => $currentDate->isWeekend(),
                'appointments' => $appointments->get($dateKey, collect()),
            ];
            $currentDate->addDay();
        }

        return $days;
    }

    /**
     * Accept a pending appointment.
     */
    public function accept(Appointment $appointment)
    {
        if ($appointment->counselor_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($appointment->status !== Appointment::STATUS_PENDING) {
            return redirect()
                ->back()
                ->with('error', 'This appointment is not pending.');
        }

        $appointment->update(['status' => Appointment::STATUS_ACCEPTED]);

        return redirect()
            ->back()
            ->with('success', 'Appointment accepted successfully.');
    }

    /**
     * Cancel an appointment.
     */
    public function cancel(Request $request, Appointment $appointment)
    {
        // Ensure the appointment belongs to the logged-in counselor
        if ($appointment->counselor_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        // Create cancel reason record
        $cancelReason = CancelReason::create([
            'appointment_id' => $appointment->id,
            'cancelled_by' => Auth::id(),
            'reason' => $validated['reason'],
            'email_sent' => false,
        ]);

        // Update appointment status
        $appointment->update(['status' => Appointment::STATUS_CANCELLED]);

        // Send email notification to client
        try {
            Mail::to($appointment->client->email)
                ->send(new AppointmentCancelled($appointment, $validated['reason']));
            
            $cancelReason->update(['email_sent' => true]);
        } catch (\Exception $e) {
            // Log error but don't fail the cancellation
            \Log::error('Failed to send cancellation email: ' . $e->getMessage());
        }

        return redirect()
            ->route('counselor.appointments.index')
            ->with('success', 'Appointment cancelled successfully.');
    }

    /**
     * Start a session for an appointment.
     */
    public function startSession(Appointment $appointment)
    {
        // Ensure the appointment belongs to the logged-in counselor
        if ($appointment->counselor_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if appointment is valid for starting a session
        if (!in_array($appointment->status, [Appointment::STATUS_PENDING, Appointment::STATUS_ACCEPTED])) {
            return redirect()
                ->back()
                ->with('error', 'Cannot start session for this appointment.');
        }

        // Check if case log already exists
        $caseLog = $appointment->caseLog;

        if (!$caseLog) {
            // Create a new case log with start time
            $caseLog = CaseLog::create([
                'appointment_id' => $appointment->id,
                'counselor_id' => Auth::id(),
                'client_id' => $appointment->client_id,
                'start_time' => now(),
            ]);
        } elseif (!$caseLog->start_time) {
            // Update existing case log with start time
            $caseLog->update(['start_time' => now()]);
        }

        // Update appointment status to accepted (in progress)
        $appointment->update(['status' => Appointment::STATUS_ACCEPTED]);

        return redirect()
            ->route('counselor.appointments.index', ['today' => true])
            ->with('success', 'Session started successfully.');
    }

    /**
     * End a session and redirect to case log form.
     */
    public function endSession(Appointment $appointment)
    {
        // Ensure the appointment belongs to the logged-in counselor
        if ($appointment->counselor_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $caseLog = $appointment->caseLog;

        if (!$caseLog || !$caseLog->start_time) {
            return redirect()
                ->back()
                ->with('error', 'Session was not started.');
        }

        // Set end time
        $caseLog->update(['end_time' => now()]);
        $caseLog->calculateDuration();

        // Redirect to case log form
        return redirect()
            ->route('counselor.case-logs.edit', $caseLog->id)
            ->with('info', 'Please complete the case log for this session.');
    }

    /**
     * Get the active session (if any) for display.
     */
    public function activeSession()
    {
        $counselor = Auth::user();

        // Find any appointment with an active session (has start_time but no end_time)
        $activeAppointment = Appointment::with(['client', 'caseLog'])
            ->where('counselor_id', $counselor->id)
            ->whereHas('caseLog', function ($query) {
                $query->whereNotNull('start_time')
                      ->whereNull('end_time');
            })
            ->first();

        if (!$activeAppointment) {
            return response()->json(['active' => false]);
        }

        return response()->json([
            'active' => true,
            'appointment_id' => $activeAppointment->id,
            'client_name' => $activeAppointment->client->name,
            'start_time' => $activeAppointment->caseLog->start_time->toISOString(),
        ]);
    }
}
