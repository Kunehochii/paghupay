<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use App\Models\CounselorUnavailableDate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvailabilityController extends Controller
{
    /**
     * Display the availability calendar where counselors can mark dates unavailable.
     */
    public function index(Request $request)
    {
        $counselor = Auth::user();
        $selectedMonth = $request->query('month');

        // Parse current month for calendar
        $currentMonth = $selectedMonth
            ? Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth()
            : now()->startOfMonth();

        $prevMonth = $currentMonth->copy()->subMonth();
        $nextMonth = $currentMonth->copy()->addMonth();

        // Get counselor's unavailable dates
        $unavailableDates = CounselorUnavailableDate::where('counselor_id', $counselor->id)
            ->pluck('unavailable_date')
            ->map(fn($date) => $date->format('Y-m-d'))
            ->toArray();

        // Build calendar days
        $calendarDays = $this->buildCalendarDays($currentMonth, $unavailableDates);

        return view('counselor.availability.index', compact(
            'currentMonth',
            'prevMonth',
            'nextMonth',
            'calendarDays',
            'unavailableDates'
        ));
    }

    /**
     * Toggle a date's availability (mark as unavailable or available).
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'reason' => 'nullable|string|max:255',
        ]);

        $counselor = Auth::user();
        $date = $request->date;

        // Don't allow toggling weekends
        if (Carbon::parse($date)->isWeekend()) {
            return response()->json([
                'success' => false,
                'message' => 'Weekends are already unavailable.',
            ], 422);
        }

        // Check if date is already marked unavailable
        $existing = CounselorUnavailableDate::where('counselor_id', $counselor->id)
            ->where('unavailable_date', $date)
            ->first();

        if ($existing) {
            // Remove the unavailable date (make it available again)
            $existing->delete();

            return response()->json([
                'success' => true,
                'available' => true,
                'message' => 'Date is now available.',
            ]);
        }

        // Mark date as unavailable
        CounselorUnavailableDate::create([
            'counselor_id' => $counselor->id,
            'unavailable_date' => $date,
            'reason' => $request->reason,
        ]);

        return response()->json([
            'success' => true,
            'available' => false,
            'message' => 'Date marked as unavailable.',
        ]);
    }

    /**
     * Build calendar days array.
     */
    private function buildCalendarDays(Carbon $month, array $unavailableDates): array
    {
        $startOfMonth = $month->copy()->startOfMonth();
        $endOfMonth = $month->copy()->endOfMonth();

        $startDate = $startOfMonth->copy()->startOfWeek(Carbon::SUNDAY);
        $endDate = $endOfMonth->copy()->endOfWeek(Carbon::SATURDAY);

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
                'isPast' => $currentDate->isBefore(today()),
                'isUnavailable' => in_array($dateKey, $unavailableDates),
            ];
            $currentDate->addDay();
        }

        return $days;
    }
}
