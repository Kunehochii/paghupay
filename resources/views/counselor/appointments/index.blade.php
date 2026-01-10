@extends('layouts.counselor')

@section('title', 'Appointments')

@push('styles')
<style>
    /* Calendar Styles */
    .calendar-wrapper {
        background: white;
        border-radius: 0.5rem;
    }

    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid #e9ecef;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
    }

    .calendar-day-header {
        padding: 0.75rem 0.5rem;
        text-align: center;
        font-weight: 600;
        font-size: 0.75rem;
        color: #6c757d;
        border-bottom: 1px solid #e9ecef;
    }

    .calendar-day {
        min-height: 80px;
        padding: 0.5rem;
        border: 1px solid #f0f0f0;
        cursor: pointer;
        transition: background 0.2s;
        position: relative;
    }

    .calendar-day:hover {
        background: #f8f9fa;
    }

    .calendar-day.today {
        background: #e8f5e9;
    }

    .calendar-day.selected {
        background: #e3f2fd;
        border-color: #2196F3;
    }

    .calendar-day.other-month {
        color: #ccc;
        background: #fafafa;
    }

    .calendar-day.weekend {
        background: #fafafa;
    }

    .calendar-date {
        font-weight: 600;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }

    .calendar-dot-container {
        display: flex;
        gap: 2px;
        flex-wrap: wrap;
        margin-top: 0.25rem;
    }

    .calendar-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .calendar-dot.pending {
        background: #ffc107;
    }

    .calendar-dot.accepted {
        background: #17a2b8;
    }

    .calendar-dot.completed {
        background: #28a745;
    }

    .appointment-count {
        position: absolute;
        top: 4px;
        right: 4px;
        font-size: 0.65rem;
        background: #198754;
        color: white;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Calendar Full View */
    .calendar-full .calendar-day {
        min-height: 100px;
    }

    .calendar-full .appointment-item {
        font-size: 0.7rem;
        padding: 2px 4px;
        background: #e3f2fd;
        border-radius: 3px;
        margin-bottom: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .calendar-full .appointment-item.pending {
        background: #fff3cd;
    }

    .calendar-full .appointment-item.accepted {
        background: #cff4fc;
    }

    .calendar-full .appointment-item.completed {
        background: #d1e7dd;
    }

    /* Accept Button Animation */
    .btn-accept:hover {
        transform: scale(1.05);
    }

    /* Card Hover */
    .appointment-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .appointment-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
    }

    /* Tab Pills */
    .nav-pills .nav-link.active {
        background-color: #198754;
    }
</style>
@endpush

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">Appointments</h4>
                <p class="text-muted mb-0">Manage your counseling appointments</p>
            </div>
        </div>
    </div>
</div>

{{-- Stats Row --}}
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">Pending This Month</p>
                        <h3 class="mb-0 text-warning">{{ $pendingCount }}</h3>
                    </div>
                    <div class="bg-warning bg-opacity-10 rounded p-2">
                        <i class="bi bi-hourglass-split text-warning fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">Today's Appointments</p>
                        <h3 class="mb-0 text-primary">{{ $todayAppointments->count() }}</h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 rounded p-2">
                        <i class="bi bi-calendar-check text-primary fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">This Month Total</p>
                        <h3 class="mb-0 text-success">{{ $monthlyCount }}</h3>
                    </div>
                    <div class="bg-success bg-opacity-10 rounded p-2">
                        <i class="bi bi-calendar-month text-success fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Navigation Tabs --}}
<ul class="nav nav-pills mb-4" id="appointmentTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ $selectedDate ? '' : 'active' }}" id="pending-tab" data-bs-toggle="pill" data-bs-target="#pending" type="button">
            <i class="bi bi-hourglass-split me-1"></i> Pending Requests
            @if($pendingAppointments->count() > 0)
                <span class="badge bg-warning text-dark ms-1">{{ $pendingAppointments->count() }}</span>
            @endif
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="calendar-tab" data-bs-toggle="pill" data-bs-target="#calendar" type="button">
            <i class="bi bi-calendar3 me-1"></i> Calendar View
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ $selectedDate ? 'active' : '' }}" id="day-tab" data-bs-toggle="pill" data-bs-target="#day" type="button">
            <i class="bi bi-calendar-day me-1"></i> Day View
        </button>
    </li>
</ul>

<div class="tab-content">
    {{-- Pending Requests Tab --}}
    <div class="tab-pane fade {{ $selectedDate ? '' : 'show active' }}" id="pending" role="tabpanel">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-warning bg-opacity-10 py-3">
                <h5 class="mb-0 text-dark">
                    <i class="bi bi-hourglass-split text-warning me-2"></i>
                    Upcoming Pending Appointments
                </h5>
            </div>
            <div class="card-body">
                @if($pendingAppointments->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3 mb-0">No pending appointment requests.</p>
                    </div>
                @else
                    <div class="row">
                        @foreach($pendingAppointments as $appointment)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card h-100 appointment-card border-warning">
                                <div class="card-header bg-warning bg-opacity-10 py-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong>{{ $appointment->scheduled_at->format('M j, Y') }}</strong>
                                        <span class="badge bg-warning text-dark">{{ $appointment->scheduled_at->format('g:i A') }}</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2" 
                                             style="width: 40px; height: 40px; font-size: 16px;">
                                            {{ strtoupper(substr($appointment->client->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong>{{ $appointment->client->name }}</strong>
                                            @if($appointment->client->course_year_section)
                                            <br><small class="text-muted">{{ $appointment->client->course_year_section }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-muted small mb-3">
                                        <i class="bi bi-chat-quote me-1"></i>
                                        {{ Str::limit($appointment->reason, 80) }}
                                    </p>
                                </div>
                                <div class="card-footer bg-white d-flex gap-2">
                                    <form action="{{ route('counselor.appointments.accept', $appointment->id) }}" method="POST" class="flex-grow-1">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm w-100 btn-accept">
                                            <i class="bi bi-check-circle"></i> Accept
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-outline-danger btn-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#rejectModal"
                                            data-appointment-id="{{ $appointment->id }}"
                                            data-client-name="{{ $appointment->client->name }}">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Calendar View Tab --}}
    <div class="tab-pane fade" id="calendar" role="tabpanel">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('counselor.appointments.index', ['month' => $prevMonth->format('Y-m')]) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                    <h5 class="mb-0">{{ $currentMonth->format('F Y') }}</h5>
                    <a href="{{ route('counselor.appointments.index', ['month' => $nextMonth->format('Y-m')]) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('counselor.appointments.index', ['month' => now()->format('Y-m')]) }}" class="btn btn-sm btn-outline-success">
                        Today
                    </a>
                    <button class="btn btn-sm btn-outline-primary" id="toggleFullCalendar">
                        <i class="bi bi-arrows-fullscreen"></i> Full View
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="calendar-wrapper" id="calendarWrapper">
                    <div class="calendar-grid">
                        {{-- Day Headers --}}
                        @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                        <div class="calendar-day-header">{{ $day }}</div>
                        @endforeach

                        {{-- Calendar Days --}}
                        @foreach($calendarDays as $day)
                        <div class="calendar-day {{ $day['isToday'] ? 'today' : '' }} {{ $day['isCurrentMonth'] ? '' : 'other-month' }} {{ $day['isWeekend'] ? 'weekend' : '' }}"
                             data-date="{{ $day['date'] }}"
                             onclick="selectDate('{{ $day['date'] }}')">
                            <div class="calendar-date">{{ $day['dayNumber'] }}</div>
                            
                            @if($day['appointments']->count() > 0)
                                <span class="appointment-count">{{ $day['appointments']->count() }}</span>
                                
                                {{-- Dots for mini view --}}
                                <div class="calendar-dot-container mini-view">
                                    @foreach($day['appointments']->take(5) as $apt)
                                        <div class="calendar-dot {{ $apt->status }}"></div>
                                    @endforeach
                                </div>
                                
                                {{-- Names for full view --}}
                                <div class="full-view" style="display: none;">
                                    @foreach($day['appointments']->take(3) as $apt)
                                        <div class="appointment-item {{ $apt->status }}">
                                            {{ $apt->scheduled_at->format('g:i') }} {{ Str::limit($apt->client->name, 10) }}
                                        </div>
                                    @endforeach
                                    @if($day['appointments']->count() > 3)
                                        <div class="text-muted small">+{{ $day['appointments']->count() - 3 }} more</div>
                                    @endif
                                </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Legend --}}
        <div class="mt-3 d-flex gap-4 justify-content-center">
            <div class="d-flex align-items-center">
                <div class="calendar-dot pending me-2"></div>
                <small class="text-muted">Pending</small>
            </div>
            <div class="d-flex align-items-center">
                <div class="calendar-dot accepted me-2"></div>
                <small class="text-muted">Accepted</small>
            </div>
            <div class="d-flex align-items-center">
                <div class="calendar-dot completed me-2"></div>
                <small class="text-muted">Completed</small>
            </div>
        </div>
    </div>

    {{-- Day View Tab --}}
    <div class="tab-pane fade {{ $selectedDate ? 'show active' : '' }}" id="day" role="tabpanel">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary bg-opacity-10 d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 text-dark">
                    <i class="bi bi-calendar-day text-primary me-2"></i>
                    {{ $selectedDate ? \Carbon\Carbon::parse($selectedDate)->format('l, F j, Y') : 'Today - ' . now()->format('l, F j, Y') }}
                </h5>
                @if($selectedDate)
                    <a href="{{ route('counselor.appointments.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-x"></i> Clear Selection
                    </a>
                @endif
            </div>
            <div class="card-body">
                @php
                    $dayAppointments = $selectedDate ? $selectedDateAppointments : $todayAppointments;
                @endphp
                
                @if($dayAppointments->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3 mb-0">No appointments scheduled for this day.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 100px;">Time</th>
                                    <th>Student</th>
                                    <th>Reason</th>
                                    <th style="width: 100px;">Status</th>
                                    <th style="width: 180px;" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dayAppointments as $appointment)
                                <tr>
                                    <td>
                                        <strong class="text-primary">{{ $appointment->scheduled_at->format('g:i A') }}</strong>
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
                                            <span class="badge bg-success">
                                                <i class="bi bi-record-circle"></i> In Session
                                            </span>
                                        @elseif($appointment->status === 'completed')
                                            <span class="badge bg-secondary">Completed</span>
                                        @elseif($appointment->status === 'cancelled')
                                            <span class="badge bg-danger">Cancelled</span>
                                        @elseif($appointment->status === 'accepted')
                                            <span class="badge bg-info">Accepted</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($appointment->status === 'completed')
                                            @if($appointment->caseLog)
                                                <a href="{{ route('counselor.case-logs.show', $appointment->caseLog->id) }}" class="btn btn-sm btn-outline-info">
                                                    <i class="bi bi-journal-text"></i> View Log
                                                </a>
                                            @endif
                                        @elseif($appointment->status === 'cancelled')
                                            <span class="text-muted small">-</span>
                                        @elseif($appointment->caseLog && $appointment->caseLog->start_time && !$appointment->caseLog->end_time)
                                            {{-- Session in progress --}}
                                            <div class="d-inline-flex align-items-center">
                                                <span class="badge bg-dark me-2 session-timer" data-start="{{ $appointment->caseLog->start_time->toISOString() }}">00:00:00</span>
                                                <form action="{{ route('counselor.appointments.end-session', $appointment->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="bi bi-stop-circle"></i> End
                                                    </button>
                                                </form>
                                            </div>
                                        @elseif($appointment->status === 'pending')
                                            {{-- Pending appointment: Show Accept/Decline buttons --}}
                                            <form action="{{ route('counselor.appointments.accept', $appointment->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="bi bi-check-circle"></i> Accept
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-outline-danger ms-1" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#rejectModal"
                                                    data-appointment-id="{{ $appointment->id }}"
                                                    data-client-name="{{ $appointment->client->name }}">
                                                <i class="bi bi-x-circle"></i> Decline
                                            </button>
                                        @elseif($appointment->status === 'accepted')
                                            {{-- Accepted appointment: Show Start/Cancel buttons --}}
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

{{-- Cancel Modal --}}
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-x-circle"></i> Cancel Appointment</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="cancelForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>You are about to cancel the appointment with <strong id="cancelClientName"></strong>.</p>
                    <div class="mb-3">
                        <label for="cancelReason" class="form-label">Reason for Cancellation <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="cancelReason" name="reason" rows="3" required></textarea>
                    </div>
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        The student will be notified via email.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keep Appointment</button>
                    <button type="submit" class="btn btn-danger">Cancel Appointment</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Reject Modal (for declining pending requests) --}}
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title"><i class="bi bi-x-circle"></i> Decline Appointment Request</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>You are about to decline the appointment request from <strong id="rejectClientName"></strong>.</p>
                    <div class="mb-3">
                        <label for="rejectReason" class="form-label">Reason for Declining <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejectReason" name="reason" rows="3" required 
                                  placeholder="e.g., Schedule conflict, please book another time slot..."></textarea>
                    </div>
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        The student will be notified via email and can book a new appointment.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-secondary">Decline Request</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Session timers
    document.querySelectorAll('.session-timer').forEach(function(timer) {
        const startTime = new Date(timer.dataset.start);
        
        function updateTimer() {
            const now = new Date();
            const diff = Math.floor((now - startTime) / 1000);
            
            const hours = Math.floor(diff / 3600).toString().padStart(2, '0');
            const minutes = Math.floor((diff % 3600) / 60).toString().padStart(2, '0');
            const seconds = (diff % 60).toString().padStart(2, '0');
            
            timer.textContent = `${hours}:${minutes}:${seconds}`;
        }
        
        updateTimer();
        setInterval(updateTimer, 1000);
    });

    // Select date from calendar
    function selectDate(date) {
        window.location.href = `{{ route('counselor.appointments.index') }}?date=${date}`;
    }

    // Toggle full calendar view
    document.getElementById('toggleFullCalendar').addEventListener('click', function() {
        const wrapper = document.getElementById('calendarWrapper');
        wrapper.classList.toggle('calendar-full');
        
        const miniViews = document.querySelectorAll('.mini-view');
        const fullViews = document.querySelectorAll('.full-view');
        
        if (wrapper.classList.contains('calendar-full')) {
            miniViews.forEach(el => el.style.display = 'none');
            fullViews.forEach(el => el.style.display = 'block');
            this.innerHTML = '<i class="bi bi-arrows-angle-contract"></i> Compact';
        } else {
            miniViews.forEach(el => el.style.display = 'flex');
            fullViews.forEach(el => el.style.display = 'none');
            this.innerHTML = '<i class="bi bi-arrows-fullscreen"></i> Full View';
        }
    });

    // Cancel modal
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

    // Reject modal (for declining pending requests)
    const rejectModal = document.getElementById('rejectModal');
    if (rejectModal) {
        rejectModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const appointmentId = button.getAttribute('data-appointment-id');
            const clientName = button.getAttribute('data-client-name');
            
            document.getElementById('rejectClientName').textContent = clientName;
            document.getElementById('rejectForm').action = `/counselor/appointments/${appointmentId}/reject`;
        });
    }
</script>
@endpush
