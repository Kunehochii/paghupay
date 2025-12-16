@extends('layouts.app')

@section('title', 'Reason for Counseling')

@section('content')
<div class="container py-4">
    <!-- Progress Steps -->
    <div class="row justify-content-center mb-4">
        <div class="col-md-10 col-lg-8">
            <div class="d-flex justify-content-between align-items-center">
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
                    <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <span class="fw-bold">3</span>
                    </div>
                    <div class="small mt-1 fw-semibold text-primary">Reason</div>
                </div>
                <div class="flex-fill border-top border-2" style="margin-top: -15px;"></div>
                <div class="text-center flex-fill">
                    <div class="rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <span class="fw-bold">4</span>
                    </div>
                    <div class="small mt-1 text-muted">Confirm</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-chat-left-text me-2"></i>Reason for Counseling
                    </h4>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Booking Summary -->
                    <div class="card bg-light border-0 mb-4">
                        <div class="card-body">
                            <h6 class="card-title">
                                <i class="bi bi-clipboard-check me-2"></i>Booking Summary
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-person-circle text-primary me-2" style="font-size: 1.5rem;"></i>
                                        <div>
                                            <small class="text-muted d-block">Counselor</small>
                                            <strong>{{ $counselor->name }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-calendar-date text-primary me-2" style="font-size: 1.5rem;"></i>
                                        <div>
                                            <small class="text-muted d-block">Date</small>
                                            <strong>{{ \Carbon\Carbon::parse($scheduledDate)->format('F d, Y') }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-clock text-primary me-2" style="font-size: 1.5rem;"></i>
                                        <div>
                                            <small class="text-muted d-block">Time</small>
                                            <strong>{{ $timeSlot->formatted_time }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('booking.store') }}" method="POST" id="reasonForm">
                        @csrf

                        <div class="mb-4">
                            <label for="reason" class="form-label fw-semibold">
                                <i class="bi bi-pencil me-1"></i>Please tell us your reason for seeking counseling
                            </label>
                            <textarea name="reason" 
                                      id="reason" 
                                      class="form-control @error('reason') is-invalid @enderror" 
                                      rows="6" 
                                      placeholder="Please describe briefly why you would like to schedule a counseling session. This helps our counselors prepare for your appointment."
                                      minlength="10"
                                      maxlength="1000"
                                      required>{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text d-flex justify-content-between">
                                <span>
                                    <i class="bi bi-info-circle me-1"></i>
                                    Minimum 10 characters
                                </span>
                                <span id="charCount">0/1000</span>
                            </div>
                        </div>

                        <div class="alert alert-secondary">
                            <h6 class="alert-heading">
                                <i class="bi bi-shield-lock me-2"></i>Privacy Notice
                            </h6>
                            <p class="mb-0 small">
                                Your information is confidential and will only be shared with your assigned counselor. 
                                We follow the Data Privacy Act (RA 10173) in handling your personal data.
                            </p>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('booking.schedule', $counselor) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Back
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle me-2"></i>Submit Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('reason');
    const charCount = document.getElementById('charCount');

    function updateCharCount() {
        const count = textarea.value.length;
        charCount.textContent = `${count}/1000`;
        
        if (count < 10) {
            charCount.classList.add('text-danger');
            charCount.classList.remove('text-success');
        } else {
            charCount.classList.remove('text-danger');
            charCount.classList.add('text-success');
        }
    }

    textarea.addEventListener('input', updateCharCount);
    updateCharCount(); // Initial count
});
</script>
@endpush
