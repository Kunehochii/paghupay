@extends('layouts.app')

@section('title', 'Appointments')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('counselor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Appointments</li>
                </ol>
            </nav>
            <h2 class="text-success">
                <i class="bi bi-calendar-event"></i> Appointments
            </h2>
        </div>
    </div>

    {{-- Today's Appointments Section (Expanded if ?today=true) --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card {{ $showToday ? 'border-primary' : '' }}">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center"
                     data-bs-toggle="collapse" 
                     data-bs-target="#todayCollapse" 
                     style="cursor: pointer;">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-day"></i> Today's Appointments
                        <span class="badge bg-light text-primary ms-2">{{ $todayAppointments->count() }}</span>
                    </h5>
                    <i class="bi bi-chevron-{{ $showToday ? 'up' : 'down' }}" id="todayChevron"></i>
                </div>
                <div class="collapse {{ $showToday ? 'show' : '' }}" id="todayCollapse">
                    <div class="card-body">
                        @if($todayAppointments->isEmpty())
                            <div class="text-center py-4">
                                <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-2">No appointments scheduled for today.</p>
                            </div>
                        @else
                            {{-- Horizontal scrollable cards --}}
                            <div class="d-flex overflow-auto pb-3" style="gap: 1rem;">
                                @foreach($todayAppointments as $appointment)
                                <div class="card flex-shrink-0" style="width: 320px;">
                                    <div class="card-header {{ $appointment->caseLog && $appointment->caseLog->start_time && !$appointment->caseLog->end_time ? 'bg-success' : 'bg-light' }} {{ $appointment->caseLog && $appointment->caseLog->start_time && !$appointment->caseLog->end_time ? 'text-white' : '' }}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">{{ $appointment->client->name }}</h6>
                                            @if($appointment->caseLog && $appointment->caseLog->start_time && !$appointment->caseLog->end_time)
                                                <span class="badge bg-light text-success">In Session</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-2">
                                            <i class="bi bi-clock text-muted"></i>
                                            <strong>{{ $appointment->scheduled_at->format('g:i A') }}</strong>
                                        </p>
                                        <p class="mb-3 text-muted small">
                                            <i class="bi bi-chat-text"></i>
                                            {{ Str::limit($appointment->reason, 80) }}
                                        </p>

                                        @if($appointment->caseLog && $appointment->caseLog->start_time && !$appointment->caseLog->end_time)
                                            {{-- Session Timer --}}
                                            <div class="text-center mb-3">
                                                <div class="bg-dark text-white rounded p-2 mb-2">
                                                    <small class="d-block text-muted">Session Duration</small>
                                                    <span class="session-timer fs-4" data-start="{{ $appointment->caseLog->start_time->toISOString() }}">
                                                        00:00:00
                                                    </span>
                                                </div>
                                            </div>
                                            <form action="{{ route('counselor.appointments.end-session', $appointment->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-danger w-100">
                                                    <i class="bi bi-stop-circle"></i> End Session
                                                </button>
                                            </form>
                                        @else
                                            {{-- Start/Cancel buttons --}}
                                            <div class="d-flex gap-2">
                                                <form action="{{ route('counselor.appointments.start-session', $appointment->id) }}" method="POST" class="flex-grow-1">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success w-100">
                                                        <i class="bi bi-play-circle"></i> Start Session
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-outline-danger" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#cancelModal"
                                                        data-appointment-id="{{ $appointment->id }}"
                                                        data-client-name="{{ $appointment->client->name }}">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Pending Appointments Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center"
                     data-bs-toggle="collapse" 
                     data-bs-target="#pendingCollapse" 
                     style="cursor: pointer;">
                    <h5 class="mb-0">
                        <i class="bi bi-hourglass-split"></i> Pending Appointment Requests
                        <span class="badge bg-dark ms-2">{{ $pendingAppointments->count() }}</span>
                    </h5>
                    <i class="bi bi-chevron-down" id="pendingChevron"></i>
                </div>
                <div class="collapse" id="pendingCollapse">
                    <div class="card-body">
                        @if($pendingAppointments->isEmpty())
                            <div class="text-center py-4">
                                <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-2">No pending appointment requests.</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Student</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Reason</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pendingAppointments as $appointment)
                                        <tr>
                                            <td>{{ $appointment->client->name }}</td>
                                            <td>{{ $appointment->scheduled_at->format('M j, Y') }}</td>
                                            <td>{{ $appointment->scheduled_at->format('g:i A') }}</td>
                                            <td>{{ Str::limit($appointment->reason, 50) }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#cancelModal"
                                                        data-appointment-id="{{ $appointment->id }}"
                                                        data-client-name="{{ $appointment->client->name }}">
                                                    <i class="bi bi-x-circle"></i> Cancel
                                                </button>
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
    </div>

    {{-- All Appointments History --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-archive"></i> Appointment History
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Date & Time</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($allAppointments as $appointment)
                                <tr>
                                    <td>{{ $appointment->client->name }}</td>
                                    <td>{{ $appointment->scheduled_at->format('M j, Y \a\t g:i A') }}</td>
                                    <td>{{ Str::limit($appointment->reason, 40) }}</td>
                                    <td>
                                        @switch($appointment->status)
                                            @case('pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                                @break
                                            @case('accepted')
                                                <span class="badge bg-info">In Progress</span>
                                                @break
                                            @case('completed')
                                                <span class="badge bg-success">Completed</span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge bg-danger">Cancelled</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ ucfirst($appointment->status) }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        @if($appointment->status === 'completed' && $appointment->caseLog)
                                            <a href="{{ route('counselor.case-logs.show', $appointment->caseLog->id) }}" class="btn btn-sm btn-outline-info">
                                                <i class="bi bi-journal-text"></i> View Log
                                            </a>
                                        @elseif($appointment->status === 'cancelled')
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No appointments found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center">
                        {{ $allAppointments->links() }}
                    </div>
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
    .d-flex.overflow-auto::-webkit-scrollbar {
        height: 8px;
    }
    .d-flex.overflow-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    .d-flex.overflow-auto::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }
    .d-flex.overflow-auto::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>
@endpush

@push('scripts')
<script>
    // Session timers
    document.querySelectorAll('.session-timer').forEach(function(timer) {
        const startTime = new Date(timer.dataset.start);
        
        function updateTimer() {
            const now = new Date();
            const diff = Math.floor((now - startTime) / 1000);
            
            const hours = Math.floor(diff / 3600);
            const minutes = Math.floor((diff % 3600) / 60);
            const seconds = diff % 60;
            
            timer.textContent = 
                String(hours).padStart(2, '0') + ':' +
                String(minutes).padStart(2, '0') + ':' +
                String(seconds).padStart(2, '0');
        }
        
        updateTimer();
        setInterval(updateTimer, 1000);
    });

    // Collapse chevron toggle
    document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(function(header) {
        header.addEventListener('click', function() {
            const chevron = this.querySelector('i.bi-chevron-down, i.bi-chevron-up');
            if (chevron) {
                chevron.classList.toggle('bi-chevron-down');
                chevron.classList.toggle('bi-chevron-up');
            }
        });
    });

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
