@extends('layouts.app')

@section('title', 'Choose a Counselor')

@section('content')
<div class="container py-4">
    <!-- Progress Steps -->
    <div class="row justify-content-center mb-4">
        <div class="col-md-10 col-lg-8">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-center flex-fill">
                    <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <span class="fw-bold">1</span>
                    </div>
                    <div class="small mt-1 fw-semibold text-primary">Counselor</div>
                </div>
                <div class="flex-fill border-top border-2" style="margin-top: -15px;"></div>
                <div class="text-center flex-fill">
                    <div class="rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <span class="fw-bold">2</span>
                    </div>
                    <div class="small mt-1 text-muted">Schedule</div>
                </div>
                <div class="flex-fill border-top border-2" style="margin-top: -15px;"></div>
                <div class="text-center flex-fill">
                    <div class="rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <span class="fw-bold">3</span>
                    </div>
                    <div class="small mt-1 text-muted">Reason</div>
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
                        <i class="bi bi-person-circle me-2"></i>Choose Your Counselor
                    </h4>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($counselors->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-person-x text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3">No counselors are available at the moment. Please try again later.</p>
                            <a href="{{ route('booking.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Go Back
                            </a>
                        </div>
                    @else
                        <form action="{{ route('booking.select-counselor') }}" method="POST" id="counselorForm">
                            @csrf
                            <input type="hidden" name="counselor_id" id="selectedCounselorId" value="">

                            <div class="row g-3">
                                @foreach($counselors as $counselor)
                                    <div class="col-md-6">
                                        <div class="card h-100 counselor-card border-2" 
                                             data-counselor-id="{{ $counselor->id }}"
                                             style="cursor: pointer; transition: all 0.2s;">
                                            <div class="card-body text-center">
                                                @if($counselor->counselorProfile && $counselor->counselorProfile->picture_url)
                                                    <img src="{{ Storage::url($counselor->counselorProfile->picture_url) }}" 
                                                         alt="{{ $counselor->name }}"
                                                         class="rounded-circle mb-3"
                                                         style="width: 100px; height: 100px; object-fit: cover;">
                                                @else
                                                    <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3"
                                                         style="width: 100px; height: 100px; font-size: 2.5rem;">
                                                        {{ strtoupper(substr($counselor->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                                <h5 class="card-title mb-1">{{ $counselor->name }}</h5>
                                                @if($counselor->counselorProfile && $counselor->counselorProfile->position)
                                                    <p class="text-muted small mb-0">
                                                        {{ $counselor->counselorProfile->position }}
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="card-footer bg-transparent text-center py-2">
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>Available
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('booking.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>Back
                                </a>
                                <button type="submit" class="btn btn-primary" id="nextBtn" disabled>
                                    Next<i class="bi bi-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.counselor-card');
    const hiddenInput = document.getElementById('selectedCounselorId');
    const nextBtn = document.getElementById('nextBtn');

    cards.forEach(card => {
        card.addEventListener('click', function() {
            // Remove selection from all cards
            cards.forEach(c => {
                c.classList.remove('border-primary', 'shadow');
                c.classList.add('border-light');
            });

            // Add selection to clicked card
            this.classList.remove('border-light');
            this.classList.add('border-primary', 'shadow');

            // Update hidden input
            hiddenInput.value = this.dataset.counselorId;

            // Enable next button
            nextBtn.disabled = false;
        });
    });
});
</script>
@endpush

@push('styles')
<style>
.counselor-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.counselor-card.border-primary {
    background-color: #f8f9ff;
}
</style>
@endpush
