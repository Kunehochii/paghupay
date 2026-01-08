@extends('layouts.counselor')

@section('title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-1">Welcome back, {{ auth()->user()->name }}!</h4>
        <p class="text-muted mb-0">Here's an overview of your counseling activities</p>
    </div>
</div>

{{-- Active Session Alert --}}
@if(isset($activeSession) && $activeSession)
<div class="alert alert-warning d-flex align-items-center justify-content-between mb-4" role="alert">
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
@endif

{{-- Stats Row --}}
<div class="row mb-4">
    {{-- Pending Appointment Requests --}}
    <div class="col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1">Pending Requests</p>
                        <h2 class="mb-0 text-warning">{{ $stats['pending_appointments'] }}</h2>
                        <small class="text-muted">Awaiting your review</small>
                    </div>
                    <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                        <i class="bi bi-hourglass-split text-warning" style="font-size: 1.75rem;"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <a href="{{ route('counselor.appointments.index') }}" class="btn btn-sm btn-outline-warning">
                    View Requests <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Today's Appointments --}}
    <div class="col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1">Today's Appointments</p>
                        <h2 class="mb-0 text-primary">{{ $stats['today_appointments'] }}</h2>
                        <small class="text-muted">Scheduled for {{ now()->format('M j, Y') }}</small>
                    </div>
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                        <i class="bi bi-calendar-check text-primary" style="font-size: 1.75rem;"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <a href="{{ route('counselor.appointments.index', ['today' => true]) }}" class="btn btn-sm btn-outline-primary">
                    View Today <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Additional Stats --}}
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm bg-success bg-opacity-10">
            <div class="card-body text-center py-4">
                <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                <h3 class="mt-2 mb-0 text-success">{{ $stats['completed_sessions'] }}</h3>
                <small class="text-muted">Completed Sessions</small>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm bg-info bg-opacity-10">
            <div class="card-body text-center py-4">
                <i class="bi bi-journal-text text-info" style="font-size: 2rem;"></i>
                <h3 class="mt-2 mb-0 text-info">{{ $stats['total_case_logs'] }}</h3>
                <small class="text-muted">Case Logs</small>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm bg-secondary bg-opacity-10">
            <div class="card-body text-center py-4">
                <i class="bi bi-calendar-month text-secondary" style="font-size: 2rem;"></i>
                <h3 class="mt-2 mb-0 text-secondary">{{ $stats['this_month_appointments'] ?? 0 }}</h3>
                <small class="text-muted">This Month</small>
            </div>
        </div>
    </div>
</div>

{{-- Today's Schedule --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-day text-primary me-2"></i>
                    Today's Schedule
                </h5>
                <a href="{{ route('counselor.appointments.index', ['today' => true]) }}" class="btn btn-sm btn-primary">
                    View All
                </a>
            </div>
            <div class="card-body">
                @if($todayAppointments->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3 mb-0">No appointments scheduled for today.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Time</th>
                                    <th>Student</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($todayAppointments as $appointment)
                                <tr>
                                    <td>
                                        <strong>{{ $appointment->scheduled_at->format('g:i A') }}</strong>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                 style="width: 36px; height: 36px; font-size: 14px;">
                                                {{ strtoupper(substr($appointment->client->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <strong>{{ $appointment->client->name }}</strong>
                                                @if($appointment->client->course_year_section)
                                                <br><small class="text-muted">{{ $appointment->client->course_year_section }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ Str::limit($appointment->reason, 50) }}</span>
                                    </td>
                                    <td>
                                        @if($appointment->caseLog && $appointment->caseLog->start_time && !$appointment->caseLog->end_time)
                                            <span class="badge bg-success">In Session</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($appointment->caseLog && $appointment->caseLog->start_time && !$appointment->caseLog->end_time)
                                            <form action="{{ route('counselor.appointments.end-session', $appointment->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="bi bi-stop-circle"></i> End
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('counselor.appointments.start-session', $appointment->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="bi bi-play-circle"></i> Start
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-outline-danger ms-1" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#cancelModal"
                                                    data-appointment-id="{{ $appointment->id }}"
                                                    data-client-name="{{ $appointment->client->name }}">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Upcoming Appointments --}}
@if($upcomingAppointments->count() > 0)
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-event text-warning me-2"></i>
                    Upcoming Appointments
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @foreach($upcomingAppointments as $appointment)
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning bg-opacity-10 rounded p-2 me-3">
                                <i class="bi bi-clock text-warning"></i>
                            </div>
                            <div>
                                <strong>{{ $appointment->client->name }}</strong>
                                <br>
                                <small class="text-muted">
                                    {{ $appointment->scheduled_at->format('M j, Y \a\t g:i A') }}
                                </small>
                            </div>
                        </div>
                        <span class="badge bg-warning text-dark">{{ $appointment->scheduled_at->diffForHumans() }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Cancel Modal --}}
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancel Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="cancelForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Are you sure you want to cancel the appointment with <strong id="cancelClientName"></strong>?</p>
                    <div class="mb-3">
                        <label for="cancelReason" class="form-label">Reason for cancellation <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="cancelReason" name="reason" rows="3" required></textarea>
                    </div>
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        The student will be notified via email about this cancellation.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Cancel Appointment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Active session timer
    const activeTimer = document.getElementById('activeTimer');
    if (activeTimer) {
        const startTime = new Date(activeTimer.dataset.start);
        setInterval(() => {
            const now = new Date();
            const diff = Math.floor((now - startTime) / 1000);
            const hours = Math.floor(diff / 3600).toString().padStart(2, '0');
            const minutes = Math.floor((diff % 3600) / 60).toString().padStart(2, '0');
            const seconds = (diff % 60).toString().padStart(2, '0');
            activeTimer.textContent = `${hours}:${minutes}:${seconds}`;
        }, 1000);
    }

    // Session timers for cards
    document.querySelectorAll('.session-timer').forEach(timer => {
        const startTime = new Date(timer.dataset.start);
        setInterval(() => {
            const now = new Date();
            const diff = Math.floor((now - startTime) / 1000);
            const hours = Math.floor(diff / 3600).toString().padStart(2, '0');
            const minutes = Math.floor((diff % 3600) / 60).toString().padStart(2, '0');
            const seconds = (diff % 60).toString().padStart(2, '0');
            timer.textContent = `${hours}:${minutes}:${seconds}`;
        }, 1000);
    });

    // Cancel modal handler
    const cancelModal = document.getElementById('cancelModal');
    if (cancelModal) {
        cancelModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const appointmentId = button.getAttribute('data-appointment-id');
            const clientName = button.getAttribute('data-client-name');
            
            document.getElementById('cancelClientName').textContent = clientName;
            document.getElementById('cancelForm').action = `/counselor/appointments/${appointmentId}/cancel`;
        });
    }
</script>
@endpush
