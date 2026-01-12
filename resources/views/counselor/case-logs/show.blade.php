@extends('layouts.counselor')

@section('title', 'View Case Log')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('counselor.case-logs.index') }}">Case Logs</a></li>
                <li class="breadcrumb-item active">{{ $caseLog->case_log_id }}</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">Case Log Details</h4>
                <code class="text-success">{{ $caseLog->case_log_id }}</code>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('counselor.case-logs.export-pdf', $caseLog->id) }}" class="btn btn-outline-secondary" target="_blank">
                    <i class="bi bi-file-pdf"></i> Export PDF
                </a>
                <a href="{{ route('counselor.case-logs.edit', $caseLog->id) }}" class="btn btn-success">
                    <i class="bi bi-pencil"></i> Edit
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Session Info Card --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-primary bg-opacity-10 py-3">
        <h5 class="mb-0 text-primary"><i class="bi bi-info-circle me-2"></i>Session Information</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="text-muted small d-block">Student</label>
                <div class="d-flex align-items-center">
                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2" 
                         style="width: 36px; height: 36px; font-size: 14px;">
                        {{ strtoupper(substr($caseLog->client->name, 0, 1)) }}
                    </div>
                    <div>
                        <strong>{{ $caseLog->client->name }}</strong>
                        @if($caseLog->client->course_year_section)
                        <br><small class="text-muted">{{ $caseLog->client->course_year_section }}</small>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <label class="text-muted small d-block">Session Date</label>
                <strong>{{ $caseLog->start_time ? $caseLog->start_time->format('F j, Y') : 'N/A' }}</strong>
            </div>
            <div class="col-md-3 mb-3">
                <label class="text-muted small d-block">Session Time</label>
                <strong>
                    {{ $caseLog->start_time ? $caseLog->start_time->format('g:i A') : 'N/A' }} - 
                    {{ $caseLog->end_time ? $caseLog->end_time->format('g:i A') : 'N/A' }}
                </strong>
            </div>
            <div class="col-md-3 mb-3">
                <label class="text-muted small d-block">Duration</label>
                @if($caseLog->session_duration)
                    <span class="badge bg-info fs-6">{{ $caseLog->formatted_duration }}</span>
                @else
                    <span class="text-muted">N/A</span>
                @endif
            </div>
        </div>
        @if($caseLog->appointment)
        <div class="row">
            <div class="col-12">
                <label class="text-muted small d-block">Reason for Visit</label>
                <p class="mb-0">{{ $caseLog->appointment->reason }}</p>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Session Notes --}}
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="bi bi-file-text text-success me-2"></i>Progress Report</h5>
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
    <div class="col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="bi bi-sticky text-warning me-2"></i>Additional Notes</h5>
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
<div class="card border-0 shadow-sm">
    <div class="card-header bg-warning bg-opacity-10 py-3">
        <h5 class="mb-0 text-dark"><i class="bi bi-bullseye text-warning me-2"></i>Treatment Plan</h5>
    </div>
    <div class="card-body">
        @if($caseLog->treatmentGoals->isEmpty())
            <div class="text-center py-4">
                <i class="bi bi-bullseye text-muted" style="font-size: 2rem;"></i>
                <p class="text-muted mt-2 mb-0">No treatment goals recorded for this session.</p>
            </div>
        @else
            @foreach($caseLog->treatmentGoals as $index => $goal)
            <div class="card mb-3 {{ $loop->last ? 'mb-0' : '' }}">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="bi bi-bullseye text-warning me-2"></i>
                        Goal #{{ $index + 1 }}
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-3" style="white-space: pre-wrap;">{{ $goal->description }}</p>
                    
                    @if($goal->activities->isNotEmpty())
                        <h6 class="text-muted small mb-2">
                            <i class="bi bi-check-square me-1"></i> Activities ({{ $goal->activities->count() }})
                        </h6>
                        <div class="list-group list-group-flush">
                            @foreach($goal->activities as $activity)
                            <div class="list-group-item px-0 d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="mb-0">{{ $activity->description }}</p>
                                </div>
                                <span class="badge bg-secondary">
                                    {{ $activity->activity_date ? \Carbon\Carbon::parse($activity->activity_date)->format('M j, Y') : 'N/A' }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted small mb-0 fst-italic">No activities recorded for this goal.</p>
                    @endif
                </div>
            </div>
            @endforeach
        @endif
    </div>
</div>

{{-- Footer Actions --}}
<div class="d-flex justify-content-between mt-4">
    <a href="{{ route('counselor.case-logs.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back to List
    </a>
    <div class="d-flex gap-2">
        <a href="{{ route('counselor.case-logs.edit', $caseLog->id) }}" class="btn btn-success">
            <i class="bi bi-pencil"></i> Edit Case Log
        </a>
    </div>
</div>
@endsection
