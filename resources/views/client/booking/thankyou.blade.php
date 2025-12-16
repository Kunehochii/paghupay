@extends('layouts.app')

@section('title', 'Booking Confirmed')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <!-- Progress Steps (All Complete) -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="text-center flex-fill">
                    <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-check"></i>
                    </div>
                    <div class="small mt-1 text-success">Counselor</div>
                </div>
                <div class="flex-fill border-top border-2 border-success" style="margin-top: -15px;"></div>
                <div class="text-center flex-fill">
                    <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-check"></i>
                    </div>
                    <div class="small mt-1 text-success">Schedule</div>
                </div>
                <div class="flex-fill border-top border-2 border-success" style="margin-top: -15px;"></div>
                <div class="text-center flex-fill">
                    <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-check"></i>
                    </div>
                    <div class="small mt-1 text-success">Reason</div>
                </div>
                <div class="flex-fill border-top border-2 border-success" style="margin-top: -15px;"></div>
                <div class="text-center flex-fill">
                    <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-check"></i>
                    </div>
                    <div class="small mt-1 text-success">Confirm</div>
                </div>
            </div>

            <div class="card shadow-lg border-0">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                            <i class="bi bi-check-lg" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                    
                    <h1 class="h2 mb-3 text-success">Thank You!</h1>
                    <p class="lead text-muted mb-4">
                        Your appointment has been successfully submitted.
                    </p>

                    <!-- Appointment Details -->
                    <div class="card bg-light border-0 mb-4 text-start">
                        <div class="card-body">
                            <h6 class="card-title text-center mb-3">
                                <i class="bi bi-calendar-check me-2"></i>Appointment Details
                            </h6>
                            <div class="row g-3">
                                <div class="col-6">
                                    <small class="text-muted d-block">Counselor</small>
                                    <strong>{{ $appointment->counselor->name }}</strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Status</small>
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-hourglass-split me-1"></i>Pending
                                    </span>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Date</small>
                                    <strong>{{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('F d, Y') }}</strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Time</small>
                                    <strong>{{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('g:i A') }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Email Notification -->
                    <div class="alert alert-info text-start">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-envelope-check me-3" style="font-size: 1.5rem;"></i>
                            <div>
                                <h6 class="alert-heading mb-1">Check Your Email</h6>
                                <p class="mb-0 small">
                                    A confirmation email has been sent to <strong>{{ $appointment->client->email }}</strong>. 
                                    You will receive updates and notifications about your appointment through this email.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-secondary text-start">
                        <h6 class="alert-heading">
                            <i class="bi bi-info-circle me-2"></i>What's Next?
                        </h6>
                        <ul class="mb-0 small ps-3">
                            <li>Your appointment is now pending approval</li>
                            <li>The counselor will review and confirm your appointment</li>
                            <li>You will receive an email once your appointment is confirmed</li>
                            <li>Please arrive 10 minutes before your scheduled time</li>
                        </ul>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <a href="{{ route('client.welcome') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-house me-2"></i>Back to Home
                        </a>
                        <a href="{{ route('client.appointments') }}" class="btn btn-outline-primary">
                            <i class="bi bi-calendar3 me-2"></i>View My Appointments
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
