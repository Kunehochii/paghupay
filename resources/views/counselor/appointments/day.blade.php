@extends('layouts.counselor')

@section('title', $dateTitle ?? 'Today\'s Appointments')

@push('styles')
<style>
    /* Color Variables */
    :root {
        --color-primary-bg: #69d297;
        --color-primary-light: #a7f0ba;
        --color-secondary: #3d9f9b;
        --color-secondary-dark: #235675;
    }

    /* Search Bar */
    .search-wrapper {
        position: relative;
        max-width: 100%;
        margin-bottom: 1.5rem;
    }

    .search-wrapper input {
        width: 100%;
        padding: 12px 20px 12px 45px;
        border: 1px solid #e0e0e0;
        border-radius: 25px;
        font-size: 0.95rem;
        background: white;
        transition: all 0.3s ease;
    }

    .search-wrapper input:focus {
        outline: none;
        border-color: var(--color-secondary);
        box-shadow: 0 0 0 3px rgba(61, 159, 155, 0.1);
    }

    .search-wrapper input::placeholder {
        color: #999;
    }

    .search-wrapper .search-icon {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
        font-size: 1rem;
    }

    /* Your Appointments Title */
    .appointments-title {
        display: inline-block;
        padding: 12px 30px;
        border: 2px solid #e0e0e0;
        border-radius: 30px;
        font-size: 1rem;
        font-weight: 500;
        color: #333;
        margin-bottom: 1.5rem;
    }

    /* Main Card Container */
    .appointments-main-card {
        background: white;
        border-radius: 12px;
        border: 2px solid var(--color-secondary);
        overflow: hidden;
    }

    /* Stats Section */
    .day-stats {
        display: flex;
        align-items: flex-start;
        padding: 25px 30px;
        gap: 30px;
    }

    .day-stats-left {
        flex: 0 0 auto;
    }

    .day-stats-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .day-stats-value {
        font-size: 4rem;
        font-weight: 700;
        color: var(--color-secondary);
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .day-stats-subtitle {
        font-size: 0.9rem;
        color: #666;
    }

    /* Selected Date Indicator */
    .selected-date-badge {
        display: inline-block;
        padding: 6px 16px;
        background: var(--color-secondary-dark);
        color: white;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        margin-left: 10px;
    }

    /* Today's Appointment Card */
    .appointment-card-wrapper {
        flex: 1;
        display: flex;
        justify-content: flex-end;
    }

    .today-appointment-card {
        background: #f5f5f5;
        border-radius: 15px;
        padding: 20px 25px;
        min-width: 350px;
        max-width: 400px;
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .appointment-info {
        flex: 1;
    }

    .appointment-name {
        font-size: 1.25rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 4px;
    }

    .appointment-id {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 4px;
    }

    .appointment-date {
        font-size: 0.85rem;
        color: #888;
    }

    .appointment-actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .btn-start-session {
        padding: 10px 25px;
        background-color: var(--color-secondary);
        color: white;
        border: none;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .btn-start-session:hover {
        background-color: #358a87;
        color: white;
    }

    .btn-cancel-appointment {
        padding: 10px 25px;
        background-color: #f0f0f0;
        color: #333;
        border: 1px solid #ddd;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .btn-cancel-appointment:hover {
        background-color: #e0e0e0;
        color: #333;
    }

    /* Pending Appointment Card - Yellow/Orange theme */
    .pending-appointment-card {
        background: #fff3cd;
        border-radius: 15px;
        padding: 20px 25px;
        min-width: 350px;
        max-width: 400px;
        display: flex;
        align-items: center;
        gap: 20px;
        border: 2px solid #ffc107;
    }

    .pending-appointment-card .appointment-name {
        font-size: 1.25rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 4px;
    }

    .pending-appointment-card .appointment-id {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 4px;
    }

    .pending-appointment-card .appointment-date {
        font-size: 0.85rem;
        color: #888;
    }

    .pending-badge {
        display: inline-block;
        padding: 4px 12px;
        background: #ffc107;
        color: #333;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-top: 6px;
    }

    .btn-accept-pending {
        padding: 10px 25px;
        background-color: var(--color-secondary);
        color: white;
        border: none;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .btn-accept-pending:hover {
        background-color: #358a87;
        color: white;
    }

    .btn-decline-pending {
        padding: 10px 25px;
        background-color: #dc3545;
        color: white;
        border: none;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .btn-decline-pending:hover {
        background-color: #c82333;
        color: white;
    }

    /* Active Session Card */
    .session-active-card {
        background: linear-gradient(135deg, var(--color-secondary-dark), #1a4057);
        border-radius: 15px;
        padding: 20px 25px;
        min-width: 350px;
        max-width: 400px;
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .session-active-card .appointment-name {
        color: white;
    }

    .session-active-card .appointment-id {
        color: rgba(255,255,255,0.8);
    }

    .session-active-card .appointment-date {
        color: rgba(255,255,255,0.7);
    }

    .session-timer-badge {
        background: rgba(255,255,255,0.2);
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        color: white;
        margin-top: 8px;
        display: inline-block;
    }

    .btn-end-session {
        padding: 10px 25px;
        background-color: #dc3545;
        color: white;
        border: none;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .btn-end-session:hover {
        background-color: #c82333;
        color: white;
    }

    /* Multiple Appointments List */
    .appointments-list {
        padding: 0 30px 25px;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .appointments-list .today-appointment-card {
        max-width: none;
        width: 100%;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state i {
        font-size: 4rem;
        color: #ddd;
        margin-bottom: 20px;
    }

    .empty-state h5 {
        color: #888;
        font-weight: 500;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #aaa;
        margin: 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .day-stats {
            flex-direction: column;
            align-items: stretch;
        }

        .appointment-card-wrapper {
            justify-content: flex-start;
        }

        .today-appointment-card,
        .session-active-card {
            min-width: auto;
            max-width: none;
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
{{-- Search Bar --}}
<div class="search-wrapper">
    <i class="bi bi-search search-icon"></i>
    <input type="text" placeholder="Search" id="searchInput">
</div>

{{-- Title --}}
<div class="appointments-title">Your Appointments</div>

{{-- Main Card --}}
<div class="appointments-main-card">
    {{-- Stats Section --}}
    <div class="day-stats">
        <div class="day-stats-left">
            <div class="day-stats-title">
                {{ $dateTitle ?? 'Today Appointments' }}
            </div>
            <div class="day-stats-value">{{ $appointments->count() }}</div>
            <div class="day-stats-subtitle">{{ $displayDate }}</div>
        </div>

        {{-- First Appointment Card (if only one or showing preview) --}}
        @if($appointments->isNotEmpty() && $appointments->count() === 1)
            @php $appointment = $appointments->first(); @endphp
            <div class="appointment-card-wrapper">
                @if($appointment->caseLog && $appointment->caseLog->start_time && !$appointment->caseLog->end_time)
                    {{-- Active Session Card --}}
                    <div class="session-active-card">
                        <div class="appointment-info">
                            <div class="appointment-name">{{ $appointment->client->name }}</div>
                            <div class="appointment-id">{{ $appointment->client->course_year_section ?? 'N/A' }}</div>
                            <div class="appointment-date">{{ $appointment->scheduled_at->format('j M Y') }}</div>
                            <div class="session-timer-badge session-timer"
                                 data-start="{{ $appointment->caseLog->start_time->toISOString() }}"
                                 data-paused-at="{{ $appointment->caseLog->paused_at?->toISOString() }}"
                                 data-paused-total="{{ $appointment->caseLog->total_paused_seconds }}">00:00:00</div>
                        </div>
                        <div class="appointment-actions">
                            <button type="button" class="btn-pause-session {{ $appointment->caseLog->isPaused() ? 'btn-start-session' : 'btn-cancel-appointment' }}"
                                    data-case-log-id="{{ $appointment->caseLog->id }}"
                                    data-pause-url="{{ route('counselor.case-logs.pause', $appointment->caseLog->id) }}"
                                    data-resume-url="{{ route('counselor.case-logs.resume', $appointment->caseLog->id) }}"
                                    style="text-align:center;">
                                <i class="bi {{ $appointment->caseLog->isPaused() ? 'bi-play-circle' : 'bi-pause-circle' }}"></i>
                                {{ $appointment->caseLog->isPaused() ? 'Resume' : 'Pause' }}
                            </button>
                            <form action="{{ route('counselor.appointments.end-session', $appointment->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-end-session">End Session</button>
                            </form>
                        </div>
                    </div>
                @elseif($appointment->status === 'pending')
                    {{-- Pending Appointment Card --}}
                    <div class="pending-appointment-card" data-name="{{ strtolower($appointment->client->name) }}">
                        <div class="appointment-info">
                            <div class="appointment-name">{{ $appointment->client->name }}</div>
                            <div class="appointment-id">{{ $appointment->client->course_year_section ?? 'N/A' }}</div>
                            <div class="appointment-date">{{ $appointment->scheduled_at->format('j M Y \a\t g:i A') }}</div>
                            <p class="appointment-reason text-muted small mt-1 mb-2">
                                <i class="bi bi-chat-quote me-1"></i>{{ Str::limit($appointment->reason, 200) }}
                            </p>
                            <div class="small text-muted mt-1">
                                @if($appointment->client->contact_number)<span class="me-3"><i class="bi bi-telephone"></i> {{ $appointment->client->contact_number }}</span>@endif
                                @if($appointment->client->email)<span class="me-3"><i class="bi bi-envelope"></i> {{ $appointment->client->email }}</span>@endif
                                <br>
                                @if($appointment->client->address)<span><i class="bi bi-geo-alt"></i> {{ $appointment->client->address }}</span>@endif
                            </div>
                            <span class="pending-badge">PENDING</span>
                        </div>
                        <div class="appointment-actions">
                            <form action="{{ route('counselor.appointments.accept', $appointment->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-accept-pending">Accept</button>
                            </form>
                            <button type="button" class="btn-decline-pending"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#declineModal"
                                    data-appointment-id="{{ $appointment->id }}"
                                    data-client-name="{{ $appointment->client->name }}">
                                Decline
                            </button>
                        </div>
                    </div>
                @else
                    {{-- Accepted Appointment Card --}}
                    <div class="today-appointment-card" data-name="{{ strtolower($appointment->client->name) }}">
                        <div class="appointment-info">
                            <div class="appointment-name">{{ $appointment->client->name }}</div>
                            <div class="appointment-id">{{ $appointment->client->course_year_section ?? 'N/A' }}</div>
                            <div class="appointment-date">{{ $appointment->scheduled_at->format('j M Y \a\t g:i A') }}</div>
                            <p class="appointment-reason text-muted small mt-1 mb-2">
                                <i class="bi bi-chat-quote me-1"></i>{{ Str::limit($appointment->reason, 200) }}
                            </p>
                        </div>
                        <div class="appointment-actions">
                            <form action="{{ route('counselor.appointments.start-session', $appointment->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-start-session">Start Session</button>
                            </form>
                            <button type="button" class="btn-cancel-appointment"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#cancelModal"
                                    data-appointment-id="{{ $appointment->id }}"
                                    data-client-name="{{ $appointment->client->name }}">
                                Cancel
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>

    {{-- Multiple Appointments List --}}
    @if($appointments->count() > 1)
        <div class="appointments-list">
            @foreach($appointments as $appointment)
                @if($appointment->caseLog && $appointment->caseLog->start_time && !$appointment->caseLog->end_time)
                    {{-- Active Session Card --}}
                    <div class="session-active-card" data-name="{{ strtolower($appointment->client->name) }}">
                        <div class="appointment-info">
                            <div class="appointment-name">{{ $appointment->client->name }}</div>
                            <div class="appointment-id">{{ $appointment->client->course_year_section ?? 'N/A' }}</div>
                            <div class="appointment-date">{{ $appointment->scheduled_at->format('j M Y') }}</div>
                            <div class="session-timer-badge session-timer"
                                 data-start="{{ $appointment->caseLog->start_time->toISOString() }}"
                                 data-paused-at="{{ $appointment->caseLog->paused_at?->toISOString() }}"
                                 data-paused-total="{{ $appointment->caseLog->total_paused_seconds }}">00:00:00</div>
                        </div>
                        <div class="appointment-actions">
                            <button type="button" class="btn-pause-session {{ $appointment->caseLog->isPaused() ? 'btn-start-session' : 'btn-cancel-appointment' }}"
                                    data-case-log-id="{{ $appointment->caseLog->id }}"
                                    data-pause-url="{{ route('counselor.case-logs.pause', $appointment->caseLog->id) }}"
                                    data-resume-url="{{ route('counselor.case-logs.resume', $appointment->caseLog->id) }}"
                                    style="text-align:center;">
                                <i class="bi {{ $appointment->caseLog->isPaused() ? 'bi-play-circle' : 'bi-pause-circle' }}"></i>
                                {{ $appointment->caseLog->isPaused() ? 'Resume' : 'Pause' }}
                            </button>
                            <form action="{{ route('counselor.appointments.end-session', $appointment->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-end-session">End Session</button>
                            </form>
                        </div>
                    </div>
                @elseif($appointment->status === 'pending')
                    {{-- Pending Appointment Card --}}
                    <div class="pending-appointment-card" data-name="{{ strtolower($appointment->client->name) }}">
                        <div class="appointment-info">
                            <div class="appointment-name">{{ $appointment->client->name }}</div>
                            <div class="appointment-id">{{ $appointment->client->course_year_section ?? 'N/A' }}</div>
                            <div class="appointment-date">{{ $appointment->scheduled_at->format('j M Y \a\t g:i A') }}</div>
                            <p class="appointment-reason text-muted small mt-1 mb-2">
                                <i class="bi bi-chat-quote me-1"></i>{{ Str::limit($appointment->reason, 200) }}
                            </p>
                            <div class="small text-muted mt-1">
                                @if($appointment->client->contact_number)<span class="me-3"><i class="bi bi-telephone"></i> {{ $appointment->client->contact_number }}</span>@endif
                                @if($appointment->client->email)<span class="me-3"><i class="bi bi-envelope"></i> {{ $appointment->client->email }}</span>@endif
                                <br>
                                @if($appointment->client->address)<span><i class="bi bi-geo-alt"></i> {{ $appointment->client->address }}</span>@endif
                            </div>
                            <span class="pending-badge">PENDING</span>
                        </div>
                        <div class="appointment-actions">
                            <form action="{{ route('counselor.appointments.accept', $appointment->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-accept-pending">Accept</button>
                            </form>
                            <button type="button" class="btn-decline-pending"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#declineModal"
                                    data-appointment-id="{{ $appointment->id }}"
                                    data-client-name="{{ $appointment->client->name }}">
                                Decline
                            </button>
                        </div>
                    </div>
                @else
                    {{-- Accepted Appointment Card --}}
                    <div class="today-appointment-card" data-name="{{ strtolower($appointment->client->name) }}">
                        <div class="appointment-info">
                            <div class="appointment-name">{{ $appointment->client->name }}</div>
                            <div class="appointment-id">{{ $appointment->client->course_year_section ?? 'N/A' }}</div>
                            <div class="appointment-date">{{ $appointment->scheduled_at->format('j M Y \a\t g:i A') }}</div>
                            <p class="appointment-reason text-muted small mt-1 mb-2">
                                <i class="bi bi-chat-quote me-1"></i>{{ Str::limit($appointment->reason, 200) }}
                            </p>
                        </div>
                        <div class="appointment-actions">
                            <form action="{{ route('counselor.appointments.start-session', $appointment->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-start-session">Start Session</button>
                            </form>
                            <button type="button" class="btn-cancel-appointment"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#cancelModal"
                                    data-appointment-id="{{ $appointment->id }}"
                                    data-client-name="{{ $appointment->client->name }}">
                                Cancel
                            </button>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @elseif($appointments->isEmpty())
        {{-- Empty State --}}
        <div class="empty-state">
            <i class="bi bi-calendar-x"></i>
            <h5>No appointments for this day</h5>
            <p>There are no pending or accepted appointments scheduled.</p>
        </div>
    @endif
</div>

{{-- Cancel Modal --}}
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-x-circle me-2"></i>Cancel Appointment</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="cancelForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>You are about to cancel the appointment with <strong id="cancelClientName"></strong>.</p>
                    <div class="mb-3">
                        <label for="cancelReason" class="form-label">Reason for Cancellation <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="cancelReason" name="reason" rows="3" required 
                                  placeholder="e.g., Emergency, rescheduling needed..."></textarea>
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

{{-- Decline Modal (for pending appointments) --}}
<div class="modal fade" id="declineModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-x-circle me-2"></i>Decline Appointment</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="declineForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>You are about to decline the appointment request from <strong id="declineClientName"></strong>.</p>
                    <div class="mb-3">
                        <label for="declineReason" class="form-label">Reason for Declining <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="declineReason" name="reason" rows="3" required 
                                  placeholder="e.g., Schedule conflict, please book another time slot..."></textarea>
                    </div>
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        The student will be notified via email and can book a new appointment.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Decline Appointment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        document.querySelectorAll('[data-name]').forEach(function(item) {
            const name = item.getAttribute('data-name') || '';
            if (name.includes(searchTerm)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Session timers with pause/resume support
    document.querySelectorAll('.session-timer').forEach(function(timer) {
        const startTime = new Date(timer.dataset.start);
        let pausedAt = timer.dataset.pausedAt ? new Date(timer.dataset.pausedAt) : null;
        let pausedTotal = parseInt(timer.dataset.pausedTotal) || 0;
        let interval = null;

        function formatTime(totalSeconds) {
            const hours = Math.floor(totalSeconds / 3600).toString().padStart(2, '0');
            const minutes = Math.floor((totalSeconds % 3600) / 60).toString().padStart(2, '0');
            const seconds = (totalSeconds % 60).toString().padStart(2, '0');
            return `${hours}:${minutes}:${seconds}`;
        }

        function updateTimer() {
            let elapsed;
            if (pausedAt) {
                elapsed = Math.floor((pausedAt - startTime) / 1000) - pausedTotal;
            } else {
                elapsed = Math.floor((Date.now() - startTime) / 1000) - pausedTotal;
            }
            timer.textContent = formatTime(Math.max(0, elapsed));
        }

        function startTicking() {
            if (interval) clearInterval(interval);
            if (!pausedAt) {
                interval = setInterval(updateTimer, 1000);
            }
        }

        updateTimer();
        startTicking();

        // Store refs on element for pause/resume handler
        timer._pauseState = { startTime, get pausedAt() { return pausedAt; }, set pausedAt(v) { pausedAt = v; }, get pausedTotal() { return pausedTotal; }, set pausedTotal(v) { pausedTotal = v; }, updateTimer, startTicking };
    });

    // Pause/Resume button handlers
    document.querySelectorAll('.btn-pause-session').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const caseLogId = btn.dataset.caseLogId;
            // Find the timer element in the same card
            const card = btn.closest('.session-active-card');
            const timer = card ? card.querySelector('.session-timer') : null;
            if (!timer || !timer._pauseState) return;

            const state = timer._pauseState;
            const isPaused = state.pausedAt !== null;
            const url = isPaused ? btn.dataset.resumeUrl : btn.dataset.pauseUrl;
            const token = document.querySelector('meta[name="csrf-token"]').content;

            btn.disabled = true;

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    if (isPaused) {
                        state.pausedAt = null;
                        state.pausedTotal = data.total_paused_seconds;
                        timer.dataset.pausedAt = '';
                        timer.dataset.pausedTotal = data.total_paused_seconds;
                        btn.innerHTML = '<i class="bi bi-pause-circle"></i> Pause';
                        btn.classList.remove('btn-start-session');
                        btn.classList.add('btn-cancel-appointment');
                    } else {
                        state.pausedAt = new Date(data.paused_at);
                        state.pausedTotal = data.total_paused_seconds;
                        timer.dataset.pausedAt = data.paused_at;
                        timer.dataset.pausedTotal = data.total_paused_seconds;
                        btn.innerHTML = '<i class="bi bi-play-circle"></i> Resume';
                        btn.classList.remove('btn-cancel-appointment');
                        btn.classList.add('btn-start-session');
                    }
                    state.updateTimer();
                    state.startTicking();
                }
            })
            .catch(console.error)
            .finally(() => { btn.disabled = false; });
        });
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

    // Decline modal (for pending appointments)
    const declineModal = document.getElementById('declineModal');
    if (declineModal) {
        declineModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const appointmentId = button.getAttribute('data-appointment-id');
            const clientName = button.getAttribute('data-client-name');
            
            document.getElementById('declineClientName').textContent = clientName;
            document.getElementById('declineForm').action = `{{ url('counselor/appointments') }}/${appointmentId}/decline`;
        });
    }
</script>
@endpush
