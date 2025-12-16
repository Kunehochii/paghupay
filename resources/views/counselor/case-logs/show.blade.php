@extends('layouts.app')

@section('title', 'View Case Log')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('counselor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('counselor.case-logs.index') }}">Case Logs</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $caseLog->case_log_id }}</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="text-success mb-0">
                        <i class="bi bi-journal-text"></i> Case Log Details
                    </h2>
                    <small class="text-muted">{{ $caseLog->case_log_id }}</small>
                </div>
                <a href="{{ route('counselor.case-logs.edit', $caseLog->id) }}" class="btn btn-success">
                    <i class="bi bi-pencil"></i> Edit Case Log
                </a>
            </div>
        </div>
    </div>

    {{-- Session Info Card --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Session Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="text-muted small d-block">Client</label>
                                <strong>{{ $caseLog->client->name }}</strong>
                                @if($caseLog->client->course_year_section)
                                <br><small class="text-muted">{{ $caseLog->client->course_year_section }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="text-muted small d-block">Appointment Date</label>
                                <strong>{{ $caseLog->appointment->scheduled_at->format('F j, Y') }}</strong>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="text-muted small d-block">Session Time</label>
                                <strong>
                                    {{ $caseLog->start_time ? $caseLog->start_time->format('g:i A') : 'N/A' }} - 
                                    {{ $caseLog->end_time ? $caseLog->end_time->format('g:i A') : 'N/A' }}
                                </strong>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="text-muted small d-block">Duration</label>
                                <span class="badge bg-info fs-6">
                                    @if($caseLog->session_duration)
                                        {{ floor($caseLog->session_duration / 60) }}h {{ $caseLog->session_duration % 60 }}m
                                    @else
                                        N/A
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label class="text-muted small d-block">Reason for Visit</label>
                            <p class="mb-0">{{ $caseLog->appointment->reason }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Session Notes --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="bi bi-file-text"></i> Progress Report</h5>
                </div>
                <div class="card-body">
                    @if($caseLog->progress_report)
                        <p class="mb-0" style="white-space: pre-wrap;">{{ $caseLog->progress_report }}</p>
                    @else
                        <p class="text-muted mb-0 fst-italic">No progress report recorded.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="bi bi-card-text"></i> Additional Notes</h5>
                </div>
                <div class="card-body">
                    @if($caseLog->additional_notes)
                        <p class="mb-0" style="white-space: pre-wrap;">{{ $caseLog->additional_notes }}</p>
                    @else
                        <p class="text-muted mb-0 fst-italic">No additional notes recorded.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Treatment Plan --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-clipboard-check"></i> Treatment Plan</h5>
                </div>
                <div class="card-body">
                    @if($caseLog->treatmentGoals->isEmpty())
                        <div class="text-center py-4">
                            <i class="bi bi-clipboard-x text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">No treatment plan recorded for this session.</p>
                        </div>
                    @else
                        @foreach($caseLog->treatmentGoals as $index => $goal)
                        <div class="border rounded p-3 mb-3">
                            <div class="row">
                                <div class="col-md-5">
                                    <h6 class="text-success">
                                        <i class="bi bi-bullseye"></i> Goal {{ $index + 1 }}
                                    </h6>
                                    <p class="mb-0" style="white-space: pre-wrap;">{{ $goal->description }}</p>
                                </div>
                                <div class="col-md-7">
                                    <h6 class="text-info">
                                        <i class="bi bi-list-task"></i> Activities
                                    </h6>
                                    @if($goal->activities->isEmpty())
                                        <p class="text-muted fst-italic mb-0">No activities defined.</p>
                                    @else
                                        <ul class="list-group list-group-flush">
                                            @foreach($goal->activities as $activity)
                                            <li class="list-group-item d-flex justify-content-between align-items-start px-0">
                                                <div>{{ $activity->description }}</div>
                                                @if($activity->activity_date)
                                                <span class="badge bg-light text-dark">
                                                    {{ $activity->activity_date->format('M j, Y') }}
                                                </span>
                                                @endif
                                            </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Footer Actions --}}
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between">
                <a href="{{ route('counselor.case-logs.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Case Logs
                </a>
                <a href="{{ route('counselor.case-logs.edit', $caseLog->id) }}" class="btn btn-success">
                    <i class="bi bi-pencil"></i> Edit Case Log
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
