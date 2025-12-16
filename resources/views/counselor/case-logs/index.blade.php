@extends('layouts.app')

@section('title', 'Case Logs')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('counselor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Case Logs</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="text-success">
                    <i class="bi bi-journal-text"></i> Case Logs
                </h2>
                <span class="badge bg-success fs-6">{{ $caseLogs->total() }} Total Records</span>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Case Logs Table --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-archive"></i> Previous Sessions</h5>
                </div>
                <div class="card-body">
                    @if($caseLogs->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-journal-x text-muted" style="font-size: 4rem;"></i>
                            <h4 class="text-muted mt-3">No Case Logs Yet</h4>
                            <p class="text-muted">Case logs will appear here after you complete counseling sessions.</p>
                            <a href="{{ route('counselor.appointments.index', ['today' => true]) }}" class="btn btn-primary">
                                <i class="bi bi-calendar-check"></i> View Today's Appointments
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Case Log ID</th>
                                        <th>Student</th>
                                        <th>Session Date</th>
                                        <th>Duration</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($caseLogs as $caseLog)
                                    <tr>
                                        <td>
                                            <code class="text-primary">{{ $caseLog->case_log_id }}</code>
                                        </td>
                                        <td>
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
                                        </td>
                                        <td>
                                            @if($caseLog->appointment)
                                                {{ $caseLog->appointment->scheduled_at->format('M j, Y') }}
                                                <br><small class="text-muted">{{ $caseLog->appointment->scheduled_at->format('g:i A') }}</small>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($caseLog->session_duration)
                                                <span class="badge bg-info">
                                                    {{ floor($caseLog->session_duration / 60) }}h {{ $caseLog->session_duration % 60 }}m
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $caseLog->created_at->diffForHumans() }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('counselor.case-logs.show', $caseLog->id) }}" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   title="View Details">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('counselor.case-logs.edit', $caseLog->id) }}" 
                                                   class="btn btn-sm btn-outline-success" 
                                                   title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="d-flex justify-content-center mt-4">
                            {{ $caseLogs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
