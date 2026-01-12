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
    // Active session timer
    const timerElement = document.getElementById('activeTimer');
    if (timerElement) {
        const startTime = new Date(timerElement.dataset.start);
        
        function updateTimer() {
            const now = new Date();
            const diff = Math.floor((now - startTime) / 1000);
            
            const hours = Math.floor(diff / 3600).toString().padStart(2, '0');
            const minutes = Math.floor((diff % 3600) / 60).toString().padStart(2, '0');
            const seconds = (diff % 60).toString().padStart(2, '0');
            
            timerElement.textContent = `${hours}:${minutes}:${seconds}`;
        }
        
        updateTimer();
        setInterval(updateTimer, 1000);
    }
</script>
@endif
@endpush
