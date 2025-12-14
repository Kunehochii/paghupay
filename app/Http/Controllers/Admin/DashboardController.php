<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $stats = [
            'total_counselors' => User::where('role', 'counselor')->count(),
            'total_clients' => User::where('role', 'client')->count(),
            'active_clients' => User::where('role', 'client')
                ->where('is_active', true)
                ->count(),
            'pending_appointments' => Appointment::pending()->count(),
            'today_appointments' => Appointment::today()->count(),
        ];

        $recentAppointments = Appointment::with(['client', 'counselor'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentAppointments'));
    }
}
