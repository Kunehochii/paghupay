@extends('layouts.counselor')

@section('title', 'Dashboard')

@section('content')
{{-- Welcome Banner --}}
<div class="welcome-banner">
    <h5>Welcome back, {{ auth()->user()->role === 'admin' ? 'Admin' : auth()->user()->name }}!</h5>
</div>

{{-- Active Session Alert --}}
@if(isset($activeSession) && $activeSession)
<div class="alert alert-warning d-flex align-items-center justify-content-between mb-4" role="alert">
    <div>
        <i class="bi bi-clock-history me-2"></i>
        <strong>Active Session:</strong> You have an ongoing session with <strong>{{ $activeSession->appointment->client->name }}</strong>
        <span class="ms-3 badge bg-dark session-timer" id="activeTimer"
              data-start="{{ $activeSession->start_time->toISOString() }}"
              data-paused-at="{{ $activeSession->paused_at?->toISOString() }}"
              data-paused-total="{{ $activeSession->total_paused_seconds }}">
            00:00:00
        </span>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-sm btn-pause-session {{ $activeSession->isPaused() ? 'btn-success' : 'btn-secondary' }}"
                data-case-log-id="{{ $activeSession->id }}"
                data-pause-url="{{ route('counselor.case-logs.pause', $activeSession->id) }}"
                data-resume-url="{{ route('counselor.case-logs.resume', $activeSession->id) }}">
            <i class="bi {{ $activeSession->isPaused() ? 'bi-play-circle' : 'bi-pause-circle' }}"></i>
            {{ $activeSession->isPaused() ? 'Resume' : 'Pause' }}
        </button>
        <form action="{{ route('counselor.appointments.end-session', $activeSession->appointment_id) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm">
                <i class="bi bi-stop-circle"></i> End Session
            </button>
        </form>
    </div>
</div>
@endif

{{-- Main Stats Cards --}}
<div class="row g-4">
    {{-- Pending Appointment Requests --}}
    <div class="col-md-6">
        <a href="{{ route('counselor.appointments.index') }}" class="card-link">
            <div class="dashboard-card">
                <div class="dashboard-card-title">Pending Appointment Requests</div>
                <div class="dashboard-card-value">{{ $stats['pending_appointments'] }}</div>
                <div class="dashboard-card-subtitle">for {{ now()->format('F') }}</div>
                <div class="dashboard-card-arrow">
                    <i class="bi bi-arrow-right"></i>
                </div>
            </div>
        </a>
    </div>

    {{-- Today's Appointments --}}
    <div class="col-md-6">
        <a href="{{ route('counselor.appointments.day') }}" class="card-link">
            <div class="dashboard-card">
                <div class="dashboard-card-title">Today Appointments</div>
                <div class="dashboard-card-value">{{ $stats['today_appointments'] }}</div>
                <div class="dashboard-card-subtitle">{{ now()->format('j M Y') }}</div>
                <div class="dashboard-card-arrow">
                    <i class="bi bi-arrow-right"></i>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection

@push('scripts')
@if(isset($activeSession) && $activeSession)
<script>
    // Active session timer with pause/resume support
    const timerElement = document.getElementById('activeTimer');
    if (timerElement) {
        let startTime = new Date(timerElement.dataset.start);
        let pausedAt = timerElement.dataset.pausedAt ? new Date(timerElement.dataset.pausedAt) : null;
        let pausedTotal = parseInt(timerElement.dataset.pausedTotal) || 0;
        let timerInterval = null;

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
            timerElement.textContent = formatTime(Math.max(0, elapsed));
        }

        function startTicking() {
            if (timerInterval) clearInterval(timerInterval);
            if (!pausedAt) {
                timerInterval = setInterval(updateTimer, 1000);
            }
        }

        updateTimer();
        startTicking();

        // Pause/Resume button handler
        document.querySelectorAll('.btn-pause-session').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const isPaused = pausedAt !== null;
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
                            // Resumed
                            pausedAt = null;
                            pausedTotal = data.total_paused_seconds;
                            timerElement.dataset.pausedAt = '';
                            timerElement.dataset.pausedTotal = pausedTotal;
                            btn.innerHTML = '<i class="bi bi-pause-circle"></i> Pause';
                            btn.classList.remove('btn-success');
                            btn.classList.add('btn-secondary');
                        } else {
                            // Paused
                            pausedAt = new Date(data.paused_at);
                            pausedTotal = data.total_paused_seconds;
                            timerElement.dataset.pausedAt = data.paused_at;
                            timerElement.dataset.pausedTotal = pausedTotal;
                            btn.innerHTML = '<i class="bi bi-play-circle"></i> Resume';
                            btn.classList.remove('btn-secondary');
                            btn.classList.add('btn-success');
                        }
                        updateTimer();
                        startTicking();
                    }
                })
                .catch(console.error)
                .finally(() => { btn.disabled = false; });
            });
        });
    }
</script>
@endif
@endpush
