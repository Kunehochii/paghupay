<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use App\Mail\AppointmentCompleted;
use App\Models\Appointment;
use App\Models\CaseLog;
use App\Models\TreatmentActivity;
use App\Models\TreatmentGoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CaseLogController extends Controller
{
    /**
     * Display a listing of case logs.
     */
    public function index()
    {
        $caseLogs = CaseLog::with(['client', 'appointment'])
            ->where('counselor_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('counselor.case-logs.index', compact('caseLogs'));
    }

    /**
     * Show the form for creating a new case log.
     */
    public function create($appointmentId)
    {
        $appointment = Appointment::with('client')
            ->where('counselor_id', Auth::id())
            ->findOrFail($appointmentId);

        return view('counselor.case-logs.create', compact('appointment'));
    }

    /**
     * Store a newly created case log.
     */
    public function store(Request $request, $appointmentId)
    {
        $appointment = Appointment::where('counselor_id', Auth::id())
            ->findOrFail($appointmentId);

        $validated = $request->validate([
            'progress_report' => 'nullable|string',
            'additional_notes' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'goals' => 'nullable|array',
            'goals.*.description' => 'required_with:goals|string',
            'goals.*.activities' => 'nullable|array',
            'goals.*.activities.*.description' => 'required_with:goals.*.activities|string',
            'goals.*.activities.*.activity_date' => 'required_with:goals.*.activities|date',
        ]);

        DB::beginTransaction();

        try {
            // Create case log (progress_report and additional_notes auto-encrypted)
            $caseLog = CaseLog::create([
                'appointment_id' => $appointment->id,
                'counselor_id' => Auth::id(),
                'client_id' => $appointment->client_id,
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'progress_report' => $validated['progress_report'],
                'additional_notes' => $validated['additional_notes'],
            ]);

            // Calculate session duration
            $caseLog->calculateDuration();

            // Create treatment goals and activities
            if (!empty($validated['goals'])) {
                foreach ($validated['goals'] as $goalData) {
                    $goal = TreatmentGoal::create([
                        'case_log_id' => $caseLog->id,
                        'description' => $goalData['description'],
                    ]);

                    if (!empty($goalData['activities'])) {
                        foreach ($goalData['activities'] as $activityData) {
                            TreatmentActivity::create([
                                'goal_id' => $goal->id,
                                'description' => $activityData['description'],
                                'activity_date' => $activityData['activity_date'],
                            ]);
                        }
                    }
                }
            }

            // Mark appointment as completed
            $appointment->update(['status' => Appointment::STATUS_COMPLETED]);

            // Send completion email to client
            try {
                Mail::to($appointment->client->email)
                    ->send(new AppointmentCompleted($appointment, $caseLog));
            } catch (\Exception $e) {
                \Log::error('Failed to send completion email: ' . $e->getMessage());
            }

            DB::commit();

            return redirect()
                ->route('counselor.case-logs.index')
                ->with('success', 'Case log created successfully. Client has been notified via email.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create case log: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified case log.
     */
    public function show($id)
    {
        $caseLog = CaseLog::with(['client', 'appointment', 'treatmentGoals.activities'])
            ->where('counselor_id', Auth::id())
            ->findOrFail($id);

        return view('counselor.case-logs.show', compact('caseLog'));
    }

    /**
     * Show the form for editing the specified case log.
     */
    public function edit($id)
    {
        $caseLog = CaseLog::with(['client', 'appointment', 'treatmentGoals.activities'])
            ->where('counselor_id', Auth::id())
            ->findOrFail($id);

        return view('counselor.case-logs.edit', compact('caseLog'));
    }

    /**
     * Update the specified case log.
     */
    public function update(Request $request, $id)
    {
        $caseLog = CaseLog::with('appointment')
            ->where('counselor_id', Auth::id())
            ->findOrFail($id);

        $validated = $request->validate([
            'progress_report' => 'nullable|string',
            'additional_notes' => 'nullable|string',
            'goals' => 'nullable|array',
            'goals.*.id' => 'nullable|integer|exists:treatment_goals,id',
            'goals.*.description' => 'required_with:goals|string',
            'goals.*.activities' => 'nullable|array',
            'goals.*.activities.*.id' => 'nullable|integer|exists:treatment_activities,id',
            'goals.*.activities.*.description' => 'required_with:goals.*.activities|string',
            'goals.*.activities.*.activity_date' => 'required_with:goals.*.activities|date',
        ]);

        DB::beginTransaction();

        try {
            // Update case log
            $caseLog->update([
                'progress_report' => $validated['progress_report'],
                'additional_notes' => $validated['additional_notes'],
            ]);

            // Check if this is the first save (appointment not yet completed)
            $isFirstSave = $caseLog->appointment->status !== Appointment::STATUS_COMPLETED;

            // Delete old goals and activities (we'll recreate them)
            $caseLog->treatmentGoals()->each(function ($goal) {
                $goal->activities()->delete();
                $goal->delete();
            });

            // Create new treatment goals and activities
            if (!empty($validated['goals'])) {
                foreach ($validated['goals'] as $goalData) {
                    $goal = TreatmentGoal::create([
                        'case_log_id' => $caseLog->id,
                        'description' => $goalData['description'],
                    ]);

                    if (!empty($goalData['activities'])) {
                        foreach ($goalData['activities'] as $activityData) {
                            TreatmentActivity::create([
                                'goal_id' => $goal->id,
                                'description' => $activityData['description'],
                                'activity_date' => $activityData['activity_date'],
                            ]);
                        }
                    }
                }
            }

            // If first save, mark appointment as completed and send email
            if ($isFirstSave) {
                $caseLog->appointment->update(['status' => Appointment::STATUS_COMPLETED]);

                try {
                    Mail::to($caseLog->appointment->client->email)
                        ->send(new AppointmentCompleted($caseLog->appointment, $caseLog));
                } catch (\Exception $e) {
                    \Log::error('Failed to send completion email: ' . $e->getMessage());
                }
            }

            DB::commit();

            return redirect()
                ->route('counselor.case-logs.index')
                ->with('success', 'Case log saved successfully.' . ($isFirstSave ? ' Client has been notified via email.' : ''));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update case log: ' . $e->getMessage());
        }
    }
}
