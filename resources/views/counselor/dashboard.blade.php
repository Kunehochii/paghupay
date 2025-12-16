@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="text-success">
                <i class="bi bi-speedometer2"></i> Counselor Dashboard
            </h2>
            <p class="text-muted">Welcome back, {{ auth()->user()->name }}</p>
        </div>
    </div>

    {{-- Active Session Alert --}}
    @if(isset($activeSession) && $activeSession)
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-warning d-flex align-items-center justify-content-between" role="alert">
                <div>
                    <i class="bi bi-clock-history me-2"></i>
                    <strong>Active Session:</strong> You have an ongoing session with <strong>{{ $activeSession->appointment->client->name }}</strong>
                    <span class="ms-3 badge bg-dark" id="activeTimer" data-start="{{ $activeSession->start_time->toISOString() }}">
                        00:00:00
                    </span>
                </div>
                <form action="{{ route('counselor.appointments.end-session', $activeSession->appointment_id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="bi bi-stop-circle"></i> End Session
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Stats Cards --}}
    <div class="row mb-4">
        {{-- Pending Appointments Card --}}
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card border-warning h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2 text-muted">Pending Requests</h6>
                            <h2 class="card-title text-warning mb-0">{{ $stats['pending_appointments'] }}</h2>
                        </div>
                        <div class="text-warning">
                            <i class="bi bi-hourglass-split" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Today's Appointments Card --}}
        <div class="col-md-6 col-lg-3 mb-3">
            <a href="{{ route('counselor.appointments.index', ['today' => true]) }}" class="text-decoration-none">
                <div class="card border-primary h-100 card-hover">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-subtitle mb-2 text-muted">Today's Appointments</h6>
                                <h2 class="card-title text-primary mb-0">{{ $stats['today_appointments'] }}</h2>
                            </div>
                            <div class="text-primary">
                                <i class="bi bi-calendar-check" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-primary bg-opacity-10 text-primary text-center">
                        <small>View Today's Schedule <i class="bi bi-arrow-right"></i></small>
                    </div>
                </div>
            </a>
        </div>

        {{-- Completed Sessions Card --}}
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card border-success h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2 text-muted">Completed Sessions</h6>
                            <h2 class="card-title text-success mb-0">{{ $stats['completed_sessions'] }}</h2>
                        </div>
                        <div class="text-success">
                            <i class="bi bi-check-circle" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Case Logs Card --}}
        <div class="col-md-6 col-lg-3 mb-3">
            <a href="{{ route('counselor.case-logs.index') }}" class="text-decoration-none">
                <div class="card border-info h-100 card-hover">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-subtitle mb-2 text-muted">Total Case Logs</h6>
                                <h2 class="card-title text-info mb-0">{{ $stats['total_case_logs'] }}</h2>
                            </div>
                            <div class="text-info">
                                <i class="bi bi-journal-text" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-info bg-opacity-10 text-info text-center">
                        <small>View Case Logs <i class="bi bi-arrow-right"></i></small>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row">
        {{-- Today's Appointments --}}
        <div class="col-lg-7 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-calendar-day"></i> Today's Appointments</h5>
                    <a href="{{ route('counselor.appointments.index', ['today' => true]) }}" class="btn btn-sm btn-light">
                        View All <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="card-body">
                    @if($todayAppointments->isEmpty())
                        <div class="text-center py-4">
                            <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">No appointments scheduled for today.</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($todayAppointments as $appointment)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $appointment->client->name }}</h6>
                                    <small class="text-muted">
                                        <i class="bi bi-clock"></i> {{ $appointment->scheduled_at->format('g:i A') }}
                                    </small>
                                    @if($appointment->caseLog && $appointment->caseLog->start_time && !$appointment->caseLog->end_time)
                                        <span class="badge bg-success ms-2">In Session</span>
                                    @endif
                                </div>
                                <div>
                                    @if($appointment->caseLog && $appointment->caseLog->start_time && !$appointment->caseLog->end_time)
                                        {{-- Session in progress --}}
                                        <form action="{{ route('counselor.appointments.end-session', $appointment->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-stop-circle"></i> End
                                            </button>
                                        </form>
                                    @else
                                        {{-- Session not started --}}
                                        <form action="{{ route('counselor.appointments.start-session', $appointment->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="bi bi-play-circle"></i> Start
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#cancelModal"
                                                data-appointment-id="{{ $appointment->id }}"
                                                data-client-name="{{ $appointment->client->name }}">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Upcoming Appointments --}}
        <div class="col-lg-5 mb-4">
            <div class="card h-100">
                <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-hourglass-split"></i> Pending Requests</h5>
                    <a href="{{ route('counselor.appointments.index') }}" class="btn btn-sm btn-dark">
                        View All <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="card-body">
                    @if($upcomingAppointments->isEmpty())
                        <div class="text-center py-4">
                            <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">No pending appointment requests.</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($upcomingAppointments as $appointment)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-1">{{ $appointment->client->name }}</h6>
                                    <small class="text-warning">
                                        <i class="bi bi-clock-history"></i> Pending
                                    </small>
                                </div>
                                <small class="text-muted">
                                    <i class="bi bi-calendar"></i> {{ $appointment->scheduled_at->format('M j, Y') }}
                                    at {{ $appointment->scheduled_at->format('g:i A') }}
                                </small>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Cancel Appointment Modal --}}
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="cancelModalLabel">
                    <i class="bi bi-x-circle"></i> Cancel Appointment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="cancelForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>You are about to cancel the appointment with <strong id="cancelClientName"></strong>.</p>
                    <p class="text-muted small">An email notification will be sent to the student.</p>
                    
                    <div class="mb-3">
                        <label for="cancelReason" class="form-label">Reason for Cancellation <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="cancelReason" name="reason" rows="3" required
                                  placeholder="Please provide a reason for cancelling this appointment..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keep Appointment</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-x-circle"></i> Cancel Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card-hover:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    .card {
        transition: all 0.3s ease;
    }
</style>
@endpush

@push('scripts')
<script>
    // Active session timer
    const timerElement = document.getElementById('activeTimer');
    if (timerElement) {
        const startTime = new Date(timerElement.dataset.start);
        
        function updateTimer() {
            const now = new Date();
            const diff = Math.floor((now - startTime) / 1000);
            
            const hours = Math.floor(diff / 3600);
            const minutes = Math.floor((diff % 3600) / 60);
            const seconds = diff % 60;
            
            timerElement.textContent = 
                String(hours).padStart(2, '0') + ':' +
                String(minutes).padStart(2, '0') + ':' +
                String(seconds).padStart(2, '0');
        }
        
        updateTimer();
        setInterval(updateTimer, 1000);
    }

    // Cancel modal
    const cancelModal = document.getElementById('cancelModal');
    if (cancelModal) {
        cancelModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const appointmentId = button.getAttribute('data-appointment-id');
            const clientName = button.getAttribute('data-client-name');
            
            document.getElementById('cancelClientName').textContent = clientName;
            document.getElementById('cancelForm').action = `/counselor/appointments/${appointmentId}/cancel`;
        });
    }
</script>
@endpush
