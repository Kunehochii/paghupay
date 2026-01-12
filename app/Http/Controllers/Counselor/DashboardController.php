<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\CaseLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the counselor dashboard.
     */
    public function index()
    {
        $counselor = Auth::user();

        $stats = [
            'pending_appointments' => Appointment::where('counselor_id', $counselor->id)
                ->pending()
                ->count(),
            'today_appointments' => Appointment::where('counselor_id', $counselor->id)
                ->today()
                ->whereIn('status', [Appointment::STATUS_PENDING, Appointment::STATUS_ACCEPTED])
                ->count(),
            'completed_sessions' => Appointment::where('counselor_id', $counselor->id)
                ->where('status', Appointment::STATUS_COMPLETED)
                ->count(),
            'total_case_logs' => CaseLog::where('counselor_id', $counselor->id)->count(),
            'this_month_appointments' => Appointment::where('counselor_id', $counselor->id)
                ->whereMonth('scheduled_at', now()->month)
                ->whereYear('scheduled_at', now()->year)
                ->count(),
        ];

        // Check for active session
        $activeSession = CaseLog::with(['appointment.client'])
            ->where('counselor_id', $counselor->id)
            ->whereNotNull('start_time')
            ->whereNull('end_time')
            ->first();

        $todayAppointments = Appointment::with(['client', 'caseLog'])
            ->where('counselor_id', $counselor->id)
            ->today()
            ->whereIn('status', [Appointment::STATUS_PENDING, Appointment::STATUS_ACCEPTED])
            ->orderBy('scheduled_at')
            ->get();

        $upcomingAppointments = Appointment::with('client')
            ->where('counselor_id', $counselor->id)
            ->pending()
            ->where('scheduled_at', '>', now())
            ->take(5)
            ->get();

        return view('counselor.dashboard', compact(
            'stats',
            'todayAppointments',
            'upcomingAppointments',
            'activeSession'
        ));
    }
}
