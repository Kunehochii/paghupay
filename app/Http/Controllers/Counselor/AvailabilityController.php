<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use App\Models\CounselorUnavailableDate;
use App\Models\CounselorUnavailableSlot;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvailabilityController extends Controller
{
    /**
     * Display the availability calendar where counselors can mark dates and slots unavailable.
     */
    public function index(Request $request)
    {
        $counselor = Auth::user();
        $selectedMonth = $request->query('month');

        $currentMonth = $selectedMonth
            ? Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth()
            : now()->startOfMonth();

        $prevMonth = $currentMonth->copy()->subMonth();
        $nextMonth = $currentMonth->copy()->addMonth();

        $unavailableDates = CounselorUnavailableDate::where('counselor_id', $counselor->id)
            ->pluck('unavailable_date')
            ->map(fn ($date) => $date->format('Y-m-d'))
            ->toArray();

        $unavailableSlotsByDate = CounselorUnavailableSlot::where('counselor_id', $counselor->id)
            ->whereBetween('unavailable_date', [
                $currentMonth->copy()->startOfMonth()->startOfWeek(Carbon::SUNDAY)->format('Y-m-d'),
                $currentMonth->copy()->endOfMonth()->endOfWeek(Carbon::SATURDAY)->format('Y-m-d'),
            ])
            ->get()
            ->groupBy(fn ($slot) => $slot->unavailable_date->format('Y-m-d'))
            ->map(fn ($slots) => $slots->pluck('time_slot_id')->toArray())
            ->toArray();

        $calendarDays = $this->buildCalendarDays($currentMonth, $unavailableDates, $unavailableSlotsByDate);

        $timeSlots = TimeSlot::active()->get();

        return view('counselor.availability.index', compact(
            'currentMonth',
            'prevMonth',
            'nextMonth',
            'calendarDays',
            'unavailableDates',
            'unavailableSlotsByDate',
            'timeSlots'
        ));
    }

    /**
     * Toggle a date's full availability (mark as unavailable or available).
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'reason' => 'nullable|string|max:255',
        ]);

        $counselor = Auth::user();
        $date = $request->date;

        if (Carbon::parse($date)->isWeekend()) {
            return response()->json([
                'success' => false,
                'message' => 'Weekends are already unavailable.',
            ], 422);
        }

        $existing = CounselorUnavailableDate::where('counselor_id', $counselor->id)
            ->where('unavailable_date', $date)
            ->first();

        if ($existing) {
            $existing->delete();

            return response()->json([
                'success' => true,
                'available' => true,
                'message' => 'Date is now available.',
            ]);
        }

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
     * Toggle an individual time slot's availability for a specific date.
     */
    public function toggleSlot(Request $request)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'time_slot_id' => 'required|integer|exists:time_slots,id',
        ]);

        $counselor = Auth::user();
        $date = $request->date;

        if (Carbon::parse($date)->isWeekend()) {
            return response()->json([
                'success' => false,
                'message' => 'Weekends are not available.',
            ], 422);
        }

        $existing = CounselorUnavailableSlot::where('counselor_id', $counselor->id)
            ->where('unavailable_date', $date)
            ->where('time_slot_id', $request->time_slot_id)
            ->first();

        if ($existing) {
            $existing->delete();

            $remainingCount = CounselorUnavailableSlot::where('counselor_id', $counselor->id)
                ->where('unavailable_date', $date)
                ->count();

            return response()->json([
                'success' => true,
                'available' => true,
                'time_slot_id' => (int) $request->time_slot_id,
                'all_available' => $remainingCount === 0,
                'message' => 'Slot is now available.',
            ]);
        }

        CounselorUnavailableSlot::create([
            'counselor_id' => $counselor->id,
            'unavailable_date' => $date,
            'time_slot_id' => $request->time_slot_id,
        ]);

        $totalSlots = TimeSlot::active()->count();
        $unavailableCount = CounselorUnavailableSlot::where('counselor_id', $counselor->id)
            ->where('unavailable_date', $date)
            ->count();

        return response()->json([
            'success' => true,
            'available' => false,
            'time_slot_id' => (int) $request->time_slot_id,
            'all_blocked' => $unavailableCount >= $totalSlots,
            'message' => 'Slot marked as unavailable.',
        ]);
    }

    /**
     * Get slot availability for a specific date (AJAX endpoint for the modal).
     */
    public function getSlotsForDate(Request $request)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
        ]);

        $counselor = Auth::user();
        $date = $request->date;

        if (Carbon::parse($date)->isWeekend()) {
            return response()->json([
                'success' => false,
                'message' => 'Weekends are not available.',
            ], 422);
        }

        $unavailableSlotIds = CounselorUnavailableSlot::getUnavailableSlotIdsForDate($counselor->id, $date);

        $isFullyBlocked = CounselorUnavailableDate::isUnavailable($counselor->id, $date);

        $timeSlots = TimeSlot::active()->get()->map(function ($slot) use ($unavailableSlotIds, $isFullyBlocked) {
            return [
                'id' => $slot->id,
                'type' => $slot->type,
                'formatted_time' => $slot->formatted_time,
                'is_available' => ! $isFullyBlocked && ! in_array($slot->id, $unavailableSlotIds),
            ];
        });

        return response()->json([
            'success' => true,
            'slots' => $timeSlots,
            'morning' => $timeSlots->where('type', 'morning')->values(),
            'afternoon' => $timeSlots->where('type', 'afternoon')->values(),
            'isFullyBlocked' => $isFullyBlocked,
        ]);
    }

    /**
     * Build calendar days array.
     */
    private function buildCalendarDays(Carbon $month, array $unavailableDates, array $unavailableSlotsByDate): array
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
                'isPartiallyUnavailable' => ! in_array($dateKey, $unavailableDates) && isset($unavailableSlotsByDate[$dateKey]) && count($unavailableSlotsByDate[$dateKey]) > 0,
                'unavailableSlotIds' => $unavailableSlotsByDate[$dateKey] ?? [],
            ];
            $currentDate->addDay();
        }

        return $days;
    }
}
