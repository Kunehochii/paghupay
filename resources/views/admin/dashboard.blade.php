@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse" style="min-height: calc(100vh - 56px);">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active text-white" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2 me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="{{ route('admin.counselors.index') }}">
                            <i class="bi bi-person-badge me-2"></i>Counselors
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="{{ route('admin.clients.index') }}">
                            <i class="bi bi-people me-2"></i>Students
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">
                    <i class="bi bi-speedometer2 me-2 text-danger"></i>Admin Dashboard
                </h1>
                <div class="text-muted">
                    <i class="bi bi-calendar3 me-1"></i>{{ now()->format('F d, Y') }}
                </div>
            </div>

            <!-- Stats Cards Row -->
            <div class="row mb-4">
                <!-- Counselors Card -->
                <div class="col-md-6 col-lg-4 mb-3">
                    <a href="{{ route('admin.counselors.index') }}" class="text-decoration-none">
                        <div class="card border-0 shadow-sm h-100 card-hover">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted text-uppercase mb-2">Counselors</h6>
                                        <h2 class="mb-0 text-success">{{ $stats['total_counselors'] }}</h2>
                                        <small class="text-muted">Registered counselors</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-success bg-opacity-10 p-3 me-2">
                                            <i class="bi bi-person-badge fs-3 text-success"></i>
                                        </div>
                                        <i class="bi bi-chevron-right text-muted"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Students/Users Card -->
                <div class="col-md-6 col-lg-4 mb-3">
                    <a href="{{ route('admin.clients.index') }}" class="text-decoration-none">
                        <div class="card border-0 shadow-sm h-100 card-hover">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted text-uppercase mb-2">Students</h6>
                                        <h2 class="mb-0 text-primary">{{ $stats['total_clients'] }}</h2>
                                        <small class="text-muted">
                                            {{ $stats['active_clients'] }} active
                                        </small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-2">
                                            <i class="bi bi-people fs-3 text-primary"></i>
                                        </div>
                                        <i class="bi bi-chevron-right text-muted"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Appointments Card -->
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted text-uppercase mb-2">Today's Appointments</h6>
                                    <h2 class="mb-0 text-warning">{{ $stats['today_appointments'] }}</h2>
                                    <small class="text-muted">
                                        {{ $stats['pending_appointments'] }} pending
                                    </small>
                                </div>
                                <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                                    <i class="bi bi-calendar-check fs-3 text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="bi bi-clock-history me-2 text-muted"></i>Recent Appointments
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($recentAppointments->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Student</th>
                                                <th>Counselor</th>
                                                <th>Scheduled</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentAppointments as $appointment)
                                                <tr>
                                                    <td>{{ $appointment->client->name ?? 'N/A' }}</td>
                                                    <td>{{ $appointment->counselor->name ?? 'N/A' }}</td>
                                                    <td>{{ $appointment->scheduled_at ? $appointment->scheduled_at->format('M d, Y h:i A') : 'N/A' }}</td>
                                                    <td>
                                                        @php
                                                            $statusClass = match($appointment->status) {
                                                                'pending' => 'warning',
                                                                'accepted' => 'success',
                                                                'completed' => 'primary',
                                                                'cancelled' => 'danger',
                                                                'rescheduled' => 'info',
                                                                default => 'secondary'
                                                            };
                                                        @endphp
                                                        <span class="badge bg-{{ $statusClass }}">
                                                            {{ ucfirst($appointment->status) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-calendar-x fs-1 mb-2 d-block"></i>
                                    <p class="mb-0">No appointments yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

@push('styles')
<style>
    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        transition: all 0.2s ease-in-out;
    }
    .card-hover {
        transition: all 0.2s ease-in-out;
    }
    .sidebar {
        position: fixed;
        top: 56px;
        bottom: 0;
        left: 0;
        z-index: 100;
        padding: 0;
        overflow-x: hidden;
        overflow-y: auto;
    }
    @media (max-width: 767.98px) {
        .sidebar {
            position: static;
            min-height: auto !important;
        }
    }
</style>
@endpush
@endsection
