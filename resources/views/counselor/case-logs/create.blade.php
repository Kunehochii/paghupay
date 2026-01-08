@extends('layouts.counselor')

@section('title', 'Create Case Log')

@push('styles')
<style>
    .goal-card {
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
    }

    .goal-header {
        background: #f8f9fa;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #dee2e6;
        border-radius: 0.5rem 0.5rem 0 0;
    }

    .goal-body {
        padding: 1rem;
    }

    .activity-item {
        background: #f8f9fa;
        padding: 0.75rem;
        border-radius: 0.375rem;
        margin-bottom: 0.5rem;
    }

    .remove-btn {
        opacity: 0.5;
        transition: opacity 0.2s;
    }

    .remove-btn:hover {
        opacity: 1;
    }
</style>
@endpush

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('counselor.case-logs.index') }}">Case Logs</a></li>
                <li class="breadcrumb-item active">Create New</li>
            </ol>
        </nav>
        <h4 class="mb-1">Create New Case Log</h4>
        <p class="text-muted mb-0">Document a counseling session</p>
    </div>
</div>

<form action="{{ route('counselor.case-logs.store') }}" method="POST" id="caseLogForm">
    @csrf

    <div class="row">
        {{-- Left Column: Main Info --}}
        <div class="col-lg-8">
            {{-- Student Selection --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="bi bi-person text-primary me-2"></i>Student Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="client_id" class="form-label">Select Student <span class="text-danger">*</span></label>
                        <select class="form-select @error('client_id') is-invalid @enderror" id="client_id" name="client_id" required>
                            <option value="">-- Select a student --</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }} 
                                    @if($client->course_year_section) - {{ $client->course_year_section }} @endif
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Search or select the student for this case log</small>
                    </div>
                </div>
            </div>

            {{-- Session Details --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="bi bi-clock text-info me-2"></i>Session Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control @error('start_time') is-invalid @enderror" 
                                   id="start_time" name="start_time" value="{{ old('start_time') }}" required>
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_time" class="form-label">End Time <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control @error('end_time') is-invalid @enderror" 
                                   id="end_time" name="end_time" value="{{ old('end_time') }}" required>
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div id="durationDisplay" class="alert alert-info d-none">
                        <i class="bi bi-clock me-2"></i>
                        Session Duration: <strong id="durationText">--</strong>
                    </div>
                </div>
            </div>

            {{-- Progress Report --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="bi bi-file-text text-success me-2"></i>Progress Report</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="progress_report" class="form-label">Session Notes</label>
                        <textarea class="form-control @error('progress_report') is-invalid @enderror" 
                                  id="progress_report" name="progress_report" rows="5" 
                                  placeholder="Document the session progress, observations, and outcomes...">{{ old('progress_report') }}</textarea>
                        @error('progress_report')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            <i class="bi bi-shield-lock"></i> This field is encrypted for privacy
                        </small>
                    </div>

                    <div class="mb-0">
                        <label for="additional_notes" class="form-label">Additional Notes & Recommendations</label>
                        <textarea class="form-control @error('additional_notes') is-invalid @enderror" 
                                  id="additional_notes" name="additional_notes" rows="3" 
                                  placeholder="Any follow-up recommendations or additional observations...">{{ old('additional_notes') }}</textarea>
                        @error('additional_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Treatment Plan --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-bullseye text-warning me-2"></i>Treatment Plan</h5>
                    <button type="button" class="btn btn-sm btn-outline-success" onclick="addGoal()">
                        <i class="bi bi-plus-circle"></i> Add Goal
                    </button>
                </div>
                <div class="card-body">
                    <div id="goalsContainer">
                        {{-- Goals will be added here dynamically --}}
                    </div>
                    <div id="noGoalsMessage" class="text-center text-muted py-4">
                        <i class="bi bi-bullseye" style="font-size: 2rem;"></i>
                        <p class="mt-2 mb-0">No treatment goals added yet. Click "Add Goal" to create one.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Summary & Actions --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 1rem;">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0"><i class="bi bi-check-circle me-2"></i>Summary</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-4">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Student</span>
                            <strong id="summaryStudent">Not selected</strong>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Duration</span>
                            <strong id="summaryDuration">--</strong>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Goals</span>
                            <strong id="summaryGoals">0</strong>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted">Activities</span>
                            <strong id="summaryActivities">0</strong>
                        </li>
                    </ul>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-check-circle me-2"></i>Save Case Log
                        </button>
                        <a href="{{ route('counselor.case-logs.index') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- Goal Template --}}
<template id="goalTemplate">
    <div class="goal-card" data-goal-index="INDEX">
        <div class="goal-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">
                <i class="bi bi-bullseye text-warning me-2"></i>
                Goal #<span class="goal-number">INDEX</span>
            </h6>
            <button type="button" class="btn btn-sm btn-outline-danger remove-btn" onclick="removeGoal(this)">
                <i class="bi bi-trash"></i>
            </button>
        </div>
        <div class="goal-body">
            <div class="mb-3">
                <label class="form-label">Goal Description <span class="text-danger">*</span></label>
                <textarea class="form-control" name="goals[INDEX][description]" rows="2" 
                          placeholder="Describe the treatment goal..." required></textarea>
            </div>
            <div class="activities-container">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-muted fw-semibold">Activities</small>
                    <button type="button" class="btn btn-sm btn-link p-0" onclick="addActivity(this, 'INDEX')">
                        <i class="bi bi-plus"></i> Add Activity
                    </button>
                </div>
                <div class="activities-list">
                    {{-- Activities added here --}}
                </div>
            </div>
        </div>
    </div>
</template>

{{-- Activity Template --}}
<template id="activityTemplate">
    <div class="activity-item" data-activity-index="ACTIVITY_INDEX">
        <div class="d-flex gap-2 align-items-start">
            <div class="flex-grow-1">
                <input type="text" class="form-control form-control-sm mb-2" 
                       name="goals[GOAL_INDEX][activities][ACTIVITY_INDEX][description]"
                       placeholder="Activity description..." required>
                <input type="date" class="form-control form-control-sm" 
                       name="goals[GOAL_INDEX][activities][ACTIVITY_INDEX][activity_date]" required>
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger remove-btn" onclick="removeActivity(this)">
                <i class="bi bi-x"></i>
            </button>
        </div>
    </div>
</template>
@endsection

@push('scripts')
<script>
    let goalIndex = 0;
    let activityCounters = {};

    function addGoal() {
        goalIndex++;
        activityCounters[goalIndex] = 0;
        
        const template = document.getElementById('goalTemplate').innerHTML;
        const html = template.replace(/INDEX/g, goalIndex);
        
        document.getElementById('goalsContainer').insertAdjacentHTML('beforeend', html);
        document.getElementById('noGoalsMessage').classList.add('d-none');
        updateSummary();
    }

    function removeGoal(btn) {
        btn.closest('.goal-card').remove();
        
        if (document.querySelectorAll('.goal-card').length === 0) {
            document.getElementById('noGoalsMessage').classList.remove('d-none');
        }
        updateSummary();
    }

    function addActivity(btn, goalIdx) {
        activityCounters[goalIdx] = (activityCounters[goalIdx] || 0) + 1;
        const activityIdx = activityCounters[goalIdx];
        
        const template = document.getElementById('activityTemplate').innerHTML;
        const html = template
            .replace(/GOAL_INDEX/g, goalIdx)
            .replace(/ACTIVITY_INDEX/g, activityIdx);
        
        const activitiesList = btn.closest('.goal-body').querySelector('.activities-list');
        activitiesList.insertAdjacentHTML('beforeend', html);
        updateSummary();
    }

    function removeActivity(btn) {
        btn.closest('.activity-item').remove();
        updateSummary();
    }

    function updateSummary() {
        // Goals count
        const goalsCount = document.querySelectorAll('.goal-card').length;
        document.getElementById('summaryGoals').textContent = goalsCount;
        
        // Activities count
        const activitiesCount = document.querySelectorAll('.activity-item').length;
        document.getElementById('summaryActivities').textContent = activitiesCount;
    }

    // Student selection
    document.getElementById('client_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        document.getElementById('summaryStudent').textContent = 
            this.value ? selectedOption.text.split(' - ')[0] : 'Not selected';
    });

    // Duration calculation
    function calculateDuration() {
        const startTime = document.getElementById('start_time').value;
        const endTime = document.getElementById('end_time').value;
        
        if (startTime && endTime) {
            const start = new Date(startTime);
            const end = new Date(endTime);
            const diffMs = end - start;
            
            if (diffMs > 0) {
                const diffMins = Math.floor(diffMs / 60000);
                const hours = Math.floor(diffMins / 60);
                const mins = diffMins % 60;
                
                let durationText = '';
                if (hours > 0) durationText += hours + 'h ';
                durationText += mins + 'm';
                
                document.getElementById('durationText').textContent = durationText;
                document.getElementById('durationDisplay').classList.remove('d-none');
                document.getElementById('summaryDuration').textContent = durationText;
            } else {
                document.getElementById('durationDisplay').classList.add('d-none');
                document.getElementById('summaryDuration').textContent = '--';
            }
        }
    }

    document.getElementById('start_time').addEventListener('change', calculateDuration);
    document.getElementById('end_time').addEventListener('change', calculateDuration);
</script>
@endpush
