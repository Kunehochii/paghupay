@extends('layouts.app')

@section('title', 'Book an Appointment')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-calendar-heart text-primary" style="font-size: 4rem;"></i>
                    </div>
                    <h1 class="h2 mb-3">Book Your Appointment Today</h1>
                    <p class="text-muted mb-4">
                        Take the first step towards your well-being. Our guidance counselors are here to help you navigate through life's challenges.
                    </p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('booking.choose-counselor') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-arrow-right-circle me-2"></i>Start Booking
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-info-circle text-info me-2"></i>How it works
                        </h5>
                        <ol class="mb-0 ps-3">
                            <li class="mb-2">Choose your preferred counselor</li>
                            <li class="mb-2">Select a convenient date and time</li>
                            <li class="mb-2">Tell us your reason for counseling</li>
                            <li>Receive confirmation via email</li>
                        </ol>
                    </div>
                </div>
            </div>

            @if(isset($upcomingAppointments) && $upcomingAppointments->count() > 0)
                <div class="mt-4">
                    <div class="card border-0">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="bi bi-calendar-check text-success me-2"></i>Your Upcoming Appointments
                            </h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                @foreach($upcomingAppointments as $appointment)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $appointment->counselor->name }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('M d, Y - g:i A') }}
                                            </small>
                                        </div>
                                        <span class="badge bg-{{ $appointment->status === 'accepted' ? 'success' : 'warning' }}">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
