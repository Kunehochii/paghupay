@extends('layouts.counselor')

@section('title', 'Edit Case Log')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('counselor.case-logs.index') }}">Case Logs</a></li>
                <li class="breadcrumb-item active">Edit Case Log</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">Edit Case Log</h4>
                <code class="text-success">{{ $caseLog->case_log_id }}</code>
            </div>
            @if($caseLog->appointment && $caseLog->appointment->status !== 'completed')
            <span class="badge bg-warning text-dark fs-6">Draft - Not Yet Saved</span>
            @endif
        </div>
    </div>
</div>

{{-- Session Info Bar --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3 bg-light">
        <div class="row align-items-center">
            <div class="col-md-3">
                <small class="text-muted d-block">Client</small>
                <strong>{{ $caseLog->client->name }}</strong>
                <br><small class="text-muted">{{ $caseLog->client->tupv_id ?? 'N/A' }}</small>
            </div>
            <div class="col-md-3">
                <small class="text-muted d-block">Session Date</small>
                <strong>{{ $caseLog->start_time ? $caseLog->start_time->format('M j, Y') : 'N/A' }}</strong>
            </div>
            <div class="col-md-3">
                <small class="text-muted d-block">Session Time</small>
                <strong>
                    {{ $caseLog->start_time ? $caseLog->start_time->format('g:i A') : 'N/A' }} - 
                    {{ $caseLog->end_time ? $caseLog->end_time->format('g:i A') : 'N/A' }}
                </strong>
            </div>
            <div class="col-md-3">
                <small class="text-muted d-block">Duration</small>
                <strong class="text-primary">{{ $caseLog->formatted_duration }}</strong>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('counselor.case-logs.update', $caseLog->id) }}" method="POST" id="caseLogForm">
    @csrf
    @method('PUT')

    {{-- Session Notes - Split Screen --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0"><i class="bi bi-pencil-square text-primary me-2"></i>Session Notes</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="progress_report" class="form-label">
                            <i class="bi bi-file-text text-success"></i> Progress Report
                        </label>
                        <textarea class="form-control" id="progress_report" name="progress_report" 
                                  rows="10" placeholder="Document the session progress, observations, and key discussion points...">{{ old('progress_report', $caseLog->progress_report) }}</textarea>
                        <small class="text-muted"><i class="bi bi-shield-lock"></i> This information is encrypted.</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="additional_notes" class="form-label">
                            <i class="bi bi-card-text text-warning"></i> Additional Notes / Recommendations
                        </label>
                        <textarea class="form-control" id="additional_notes" name="additional_notes" 
                                  rows="10" placeholder="Additional observations, follow-up recommendations, referrals needed...">{{ old('additional_notes', $caseLog->additional_notes) }}</textarea>
                        <small class="text-muted"><i class="bi bi-shield-lock"></i> This information is encrypted.</small>
                    </div>
                </div>
            </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Treatment Plan Section --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-warning bg-opacity-10 d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0"><i class="bi bi-clipboard-check text-warning me-2"></i>Treatment Plan</h5>
                <button type="button" class="btn btn-sm btn-success" id="addGoalBtn">
                    <i class="bi bi-plus-circle"></i> Add Goal
                </button>
            </div>
            <div class="card-body" id="goalsContainer">
                @forelse($caseLog->treatmentGoals as $goalIndex => $goal)
                <div class="goal-item border rounded p-3 mb-3" data-goal-index="{{ $goalIndex }}">
                    <div class="row">
                        {{-- Goal Description (Left Column) --}}
                        <div class="col-md-5">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <label class="form-label fw-bold text-success">
                                    <i class="bi bi-bullseye"></i> Goal {{ $goalIndex + 1 }}
                                </label>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-goal-btn">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                            <textarea class="form-control goal-description" 
                                      name="goals[{{ $goalIndex }}][description]" 
                                      rows="4" 
                                      placeholder="Describe the treatment goal...">{{ $goal->description }}</textarea>
                        </div>

                        {{-- Activities (Right Column) --}}
                        <div class="col-md-7">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label fw-bold text-info">
                                    <i class="bi bi-list-task"></i> Activities
                                </label>
                                <button type="button" class="btn btn-sm btn-outline-info add-activity-btn">
                                    <i class="bi bi-plus"></i> Add Activity
                                </button>
                            </div>
                            <div class="activities-container">
                                @forelse($goal->activities as $activityIndex => $activity)
                                <div class="activity-item mb-2" data-activity-index="{{ $activityIndex }}">
                                    <div class="row g-2 align-items-start">
                                        <div class="col">
                                            <textarea class="form-control form-control-sm activity-description" 
                                                      name="goals[{{ $goalIndex }}][activities][{{ $activityIndex }}][description]" 
                                                      rows="2" 
                                                      placeholder="Activity description...">{{ $activity->description }}</textarea>
                                        </div>
                                        <div class="col-auto" style="width: 130px;">
                                            <input type="date" class="form-control form-control-sm activity-date" 
                                                   name="goals[{{ $goalIndex }}][activities][{{ $activityIndex }}][activity_date]"
                                                   value="{{ $activity->activity_date ? $activity->activity_date->format('Y-m-d') : '' }}">
                                        </div>
                                        <div class="col-auto">
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-activity-btn">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="activity-item mb-2" data-activity-index="0">
                                    <div class="row g-2 align-items-start">
                                        <div class="col">
                                            <textarea class="form-control form-control-sm activity-description" 
                                                      name="goals[{{ $goalIndex }}][activities][0][description]" 
                                                      rows="2" 
                                                      placeholder="Activity description..."></textarea>
                                        </div>
                                        <div class="col-auto" style="width: 130px;">
                                            <input type="date" class="form-control form-control-sm activity-date" 
                                                   name="goals[{{ $goalIndex }}][activities][0][activity_date]">
                                        </div>
                                        <div class="col-auto">
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-activity-btn">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                {{-- Empty state - will show Add Goal prompt --}}
                <div id="noGoalsMessage" class="text-center py-4 text-muted">
                    <i class="bi bi-clipboard-plus" style="font-size: 3rem;"></i>
                    <p class="mt-2">No treatment goals added yet. Click "Add Goal" to create a treatment plan.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Save Button --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    @if($caseLog->appointment && $caseLog->appointment->status !== 'completed')
                    <span class="text-warning">
                        <i class="bi bi-info-circle"></i>
                        Saving will mark the appointment as completed and notify the client.
                    </span>
                    @else
                    <span class="text-muted">
                        <i class="bi bi-check-circle"></i>
                        You can edit and update this case log.
                    </span>
                    @endif
                </div>
                <div>
                    <a href="{{ route('counselor.case-logs.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="bi bi-check-circle"></i> Save Case Log
                    </button>
                </div>
            </div>
        </div>
    </form>

{{-- Goal Template (Hidden) --}}
<template id="goalTemplate">
    <div class="goal-item border rounded p-3 mb-3" data-goal-index="__GOAL_INDEX__">
        <div class="row">
            <div class="col-md-5">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <label class="form-label fw-bold text-success">
                        <i class="bi bi-bullseye"></i> Goal <span class="goal-number">__GOAL_NUMBER__</span>
                    </label>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-goal-btn">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
                <textarea class="form-control goal-description" 
                          name="goals[__GOAL_INDEX__][description]" 
                          rows="4" 
                          placeholder="Describe the treatment goal..."></textarea>
            </div>
            <div class="col-md-7">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="form-label fw-bold text-info">
                        <i class="bi bi-list-task"></i> Activities
                    </label>
                    <button type="button" class="btn btn-sm btn-outline-info add-activity-btn">
                        <i class="bi bi-plus"></i> Add Activity
                    </button>
                </div>
                <div class="activities-container">
                    <div class="activity-item mb-2" data-activity-index="0">
                        <div class="row g-2 align-items-start">
                            <div class="col">
                                <textarea class="form-control form-control-sm activity-description" 
                                          name="goals[__GOAL_INDEX__][activities][0][description]" 
                                          rows="2" 
                                          placeholder="Activity description..."></textarea>
                            </div>
                            <div class="col-auto" style="width: 130px;">
                                <input type="date" class="form-control form-control-sm activity-date" 
                                       name="goals[__GOAL_INDEX__][activities][0][activity_date]">
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-activity-btn">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

{{-- Activity Template (Hidden) --}}
<template id="activityTemplate">
    <div class="activity-item mb-2" data-activity-index="__ACTIVITY_INDEX__">
        <div class="row g-2 align-items-start">
            <div class="col">
                <textarea class="form-control form-control-sm activity-description" 
                          name="goals[__GOAL_INDEX__][activities][__ACTIVITY_INDEX__][description]" 
                          rows="2" 
                          placeholder="Activity description..."></textarea>
            </div>
            <div class="col-auto" style="width: 130px;">
                <input type="date" class="form-control form-control-sm activity-date" 
                       name="goals[__GOAL_INDEX__][activities][__ACTIVITY_INDEX__][activity_date]">
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn-sm btn-outline-danger remove-activity-btn">
                    <i class="bi bi-x"></i>
                </button>
            </div>
        </div>
    </div>
</template>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const goalsContainer = document.getElementById('goalsContainer');
    const goalTemplate = document.getElementById('goalTemplate');
    const activityTemplate = document.getElementById('activityTemplate');
    const addGoalBtn = document.getElementById('addGoalBtn');
    const noGoalsMessage = document.getElementById('noGoalsMessage');

    // Get the current highest goal index
    function getNextGoalIndex() {
        const goals = goalsContainer.querySelectorAll('.goal-item');
        let maxIndex = -1;
        goals.forEach(goal => {
            const index = parseInt(goal.dataset.goalIndex);
            if (index > maxIndex) maxIndex = index;
        });
        return maxIndex + 1;
    }

    // Update goal numbers
    function updateGoalNumbers() {
        const goals = goalsContainer.querySelectorAll('.goal-item');
        goals.forEach((goal, index) => {
            const numberSpan = goal.querySelector('.goal-number');
            if (numberSpan) numberSpan.textContent = index + 1;
        });
        
        // Show/hide no goals message
        if (noGoalsMessage) {
            noGoalsMessage.style.display = goals.length === 0 ? 'block' : 'none';
        }
    }

    // Add new goal
    addGoalBtn.addEventListener('click', function() {
        const goalIndex = getNextGoalIndex();
        let goalHtml = goalTemplate.innerHTML
            .replace(/__GOAL_INDEX__/g, goalIndex)
            .replace(/__GOAL_NUMBER__/g, goalIndex + 1);
        
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = goalHtml;
        const goalElement = tempDiv.firstElementChild;
        
        goalsContainer.appendChild(goalElement);
        updateGoalNumbers();
        
        // Attach event listeners to the new goal
        attachGoalListeners(goalElement);
    });

    // Attach listeners to a goal element
    function attachGoalListeners(goalElement) {
        // Remove goal button
        const removeBtn = goalElement.querySelector('.remove-goal-btn');
        removeBtn.addEventListener('click', function() {
            goalElement.remove();
            updateGoalNumbers();
        });

        // Add activity button
        const addActivityBtn = goalElement.querySelector('.add-activity-btn');
        addActivityBtn.addEventListener('click', function() {
            const goalIndex = goalElement.dataset.goalIndex;
            const activitiesContainer = goalElement.querySelector('.activities-container');
            const activities = activitiesContainer.querySelectorAll('.activity-item');
            
            let maxActivityIndex = -1;
            activities.forEach(activity => {
                const index = parseInt(activity.dataset.activityIndex);
                if (index > maxActivityIndex) maxActivityIndex = index;
            });
            const activityIndex = maxActivityIndex + 1;

            let activityHtml = activityTemplate.innerHTML
                .replace(/__GOAL_INDEX__/g, goalIndex)
                .replace(/__ACTIVITY_INDEX__/g, activityIndex);
            
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = activityHtml;
            const activityElement = tempDiv.firstElementChild;
            
            activitiesContainer.appendChild(activityElement);
            attachActivityListeners(activityElement);
        });

        // Attach listeners to existing activities
        goalElement.querySelectorAll('.activity-item').forEach(attachActivityListeners);
    }

    // Attach listeners to an activity element
    function attachActivityListeners(activityElement) {
        const removeBtn = activityElement.querySelector('.remove-activity-btn');
        removeBtn.addEventListener('click', function() {
            const activitiesContainer = activityElement.closest('.activities-container');
            activityElement.remove();
            
            // Ensure at least one activity row remains
            if (activitiesContainer.querySelectorAll('.activity-item').length === 0) {
                // Get goal index and add a new empty activity
                const goalItem = activitiesContainer.closest('.goal-item');
                const goalIndex = goalItem.dataset.goalIndex;
                
                let activityHtml = activityTemplate.innerHTML
                    .replace(/__GOAL_INDEX__/g, goalIndex)
                    .replace(/__ACTIVITY_INDEX__/g, 0);
                
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = activityHtml;
                const activityElement = tempDiv.firstElementChild;
                
                activitiesContainer.appendChild(activityElement);
                attachActivityListeners(activityElement);
            }
        });
    }

    // Initialize existing goals
    document.querySelectorAll('.goal-item').forEach(attachGoalListeners);
    updateGoalNumbers();
});
</script>
@endpush
