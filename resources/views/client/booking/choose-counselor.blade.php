@extends('layouts.app')

@section('title', 'Choose a Counselor')

@push('styles')
<style>
    /* Color Variables */
    :root {
        --color-primary-bg: #69d297;
        --color-primary-light: #a7f0ba;
        --color-secondary: #3d9f9b;
        --color-secondary-dark: #235675;
        --color-btn-primary: #3d9f9b;
        --color-btn-primary-hover: #358a87;
    }

    .booking-page {
        min-height: 100vh;
        background-color: white;
        position: relative;
    }

    /* Top Navigation - Overlay */
    .nav-custom {
        background-color: transparent;
        padding: 15px 0;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        z-index: 100;
    }

    .nav-custom .nav-link-custom {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: var(--color-secondary);
        color: white;
        margin: 0 5px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .nav-custom .nav-link-custom:hover {
        background-color: #358a87;
        transform: scale(1.1);
    }

    .nav-custom .nav-link-about {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 20px;
        border-radius: 20px;
        border: 2px solid #333;
        background-color: transparent;
        color: #333;
        font-weight: 500;
        margin: 0 5px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .nav-custom .nav-link-about:hover {
        background-color: #333;
        color: white;
    }

    /* Left Panel - Light green background */
    .left-panel {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        padding-top: 80px; /* Account for navbar */
        background-color: var(--color-primary-light);
        min-height: 100vh;
    }

    /* Step indicators */
    .step-indicators {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        margin-bottom: 40px;
    }

    .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1rem;
    }

    .step-circle.active {
        background-color: var(--color-secondary-dark);
        color: white;
    }

    .step-circle.inactive {
        background-color: white;
        color: #333;
        border: 2px solid #ccc;
    }

    /* Counselor icon */
    .counselor-icon {
        width: 200px;
        height: auto;
        margin-bottom: 30px;
    }

    /* Instruction text */
    .instruction-text {
        text-align: center;
        font-size: 1.2rem;
        color: #333;
        margin-bottom: 30px;
        font-weight: 500;
    }

    /* Next button */
    .btn-next {
        background-color: var(--color-secondary);
        border: none;
        color: white;
        font-weight: 600;
        padding: 12px 60px;
        border-radius: 25px;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
    }

    .btn-next:hover:not(:disabled) {
        background-color: #358a87;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(61, 159, 155, 0.4);
    }

    .btn-next:disabled {
        background-color: #9dc7c5;
        cursor: not-allowed;
    }

    /* Right Panel - Counselor List */
    .right-panel {
        padding: 20px;
        max-height: calc(100vh - 100px);
        overflow-y: auto;
    }

    /* Counselor card styling */
    .counselor-card-wrapper {
        position: relative;
        margin-top: 70px;
        margin-bottom: 20px;
        padding-left: 50px;
    }

    .counselor-card {
        position: relative;
        border-radius: 15px;
        padding: 25px 30px;
        padding-right: 140px;
        min-height: 120px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 3px solid transparent;
    }

    /* Card colors based on index: 0=primary-bg, 1=secondary, 2=secondary-dark */
    .counselor-card.color-primary {
        background-color: var(--color-primary-bg);
    }

    .counselor-card.color-secondary {
        background-color: var(--color-secondary);
    }

    .counselor-card.color-secondary-dark {
        background-color: var(--color-secondary-dark);
    }

    .counselor-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .counselor-card.selected {
        border-color: var(--color-secondary-dark);
        box-shadow: 0 8px 25px rgba(35, 86, 117, 0.3);
    }

    .counselor-name {
        color: white;
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .counselor-position {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1rem;
    }

    /* Counselor photo */
    .counselor-photo-wrapper {
        position: absolute;
        right: 20px;
        top: -60px;
        bottom: -3px;
        width: 180px;
        display: flex;
        align-items: flex-end;
        justify-content: center;
    }

    .counselor-photo {
        max-width: 100%;
        height: auto;
        object-fit: contain;
        object-position: bottom;
    }

    /* Radio button styling */
    .radio-wrapper {
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
    }

    /* Custom radio circle - colors match card */
    .custom-radio {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 3px solid currentColor;
        background-color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .custom-radio.color-primary {
        color: var(--color-primary-bg);
    }

    .custom-radio.color-secondary {
        color: var(--color-secondary);
    }

    .custom-radio.color-secondary-dark {
        color: var(--color-secondary-dark);
    }

    .custom-radio.checked::after {
        content: '';
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background-color: currentColor;
    }

    /* Scrollbar styling */
    .right-panel::-webkit-scrollbar {
        width: 8px;
    }

    .right-panel::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 4px;
    }

    .right-panel::-webkit-scrollbar-thumb {
        background: var(--color-secondary);
        border-radius: 4px;
    }

    .right-panel::-webkit-scrollbar-thumb:hover {
        background: #358a87;
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #666;
    }

    .empty-state i {
        font-size: 4rem;
        color: #999;
        margin-bottom: 20px;
    }

    /* Alert styling */
    .alert-custom {
        border-radius: 10px;
        margin-bottom: 20px;
    }

    /* Responsive adjustments */
    @media (max-width: 991.98px) {
        .left-panel {
            padding: 30px 15px;
        }
        
        .counselor-icon {
            width: 150px;
        }

        .step-indicators {
            margin-bottom: 25px;
        }
    }

    @media (max-width: 767.98px) {
        .right-panel {
            max-height: none;
            overflow-y: visible;
        }

        .counselor-card-wrapper {
            margin-top: 50px;
        }

        .counselor-photo-wrapper {
            width: 100px;
            height: 120px;
            top: -40px;
        }

        .counselor-card {
            padding-right: 120px;
        }

        .counselor-name {
            font-size: 1.2rem;
        }

        .radio-wrapper {
            left: 0;
        }
    }
</style>
@endpush

@section('content')
<div class="booking-page">
    <!-- Top Navigation -->
    <nav class="nav-custom">
        <div class="container">
            <div class="d-flex justify-content-center align-items-center">
                <a href="{{ route('client.welcome') }}" class="nav-link-custom" title="Home">
                    <i class="bi bi-house-door-fill"></i>
                </a>
                <a href="#" class="nav-link-about">About us</a>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="nav-link-custom" title="Log Out">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-0">
        <div class="row g-0 min-vh-100">
            <!-- Left Panel - Steps and Instructions -->
            <div class="col-lg-5 col-md-5 left-panel">
                <!-- Step Indicators -->
                <div class="step-indicators">
                    <div class="step-circle active">1</div>
                    <div class="step-circle inactive">2</div>
                    <div class="step-circle inactive">3</div>
                </div>

                <!-- Counselor Icon -->
                <img src="{{ asset('images/counselors_icon.png') }}" alt="Counselors" class="counselor-icon">

                <!-- Instruction Text -->
                <p class="instruction-text">
                    Select your preferred counselor<br>to continue
                </p>

                <!-- Next Button -->
                <form action="{{ route('booking.select-counselor') }}" method="POST" id="counselorForm">
                    @csrf
                    <input type="hidden" name="counselor_id" id="selectedCounselorId" value="">
                    <button type="submit" class="btn btn-next" id="nextBtn" disabled>
                        NEXT
                    </button>
                </form>
            </div>

            <!-- Right Panel - Counselor List -->
            <div class="col-lg-7 col-md-7 right-panel">
                @if(session('error'))
                    <div class="alert alert-danger alert-custom alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($counselors->isEmpty())
                    <div class="empty-state">
                        <i class="bi bi-person-x"></i>
                        <h4>No Counselors Available</h4>
                        <p>No counselors are available at the moment.<br>Please try again later.</p>
                    </div>
                @else
                    @php
                        $cardColors = ['color-primary', 'color-secondary', 'color-secondary-dark'];
                    @endphp

                    @foreach($counselors as $index => $counselor)
                        <div class="counselor-card-wrapper">
                            <div class="radio-wrapper">
                                <div class="custom-radio {{ $cardColors[$index % count($cardColors)] }}" data-counselor-id="{{ $counselor->id }}" id="radio-{{ $counselor->id }}"></div>
                            </div>
                            
                            <div class="counselor-card {{ $cardColors[$index % count($cardColors)] }}" 
                                 data-counselor-id="{{ $counselor->id }}">
                                <div class="counselor-name">{{ $counselor->name }}</div>
                                @if($counselor->counselorProfile && $counselor->counselorProfile->position)
                                    <div class="counselor-position">{{ $counselor->counselorProfile->position }}</div>
                                @endif

                                <div class="counselor-photo-wrapper">
                                    @if($counselor->counselorProfile && $counselor->counselorProfile->picture_url)
                                        <img src="{{ Storage::url($counselor->counselorProfile->picture_url) }}" 
                                             alt="{{ $counselor->name }}"
                                             class="counselor-photo">
                                    @else
                                        <img src="{{ asset('images/placeholder_counselor.png') }}" 
                                             alt="{{ $counselor->name }}"
                                             class="counselor-photo">
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.counselor-card');
    const radios = document.querySelectorAll('.custom-radio');
    const hiddenInput = document.getElementById('selectedCounselorId');
    const nextBtn = document.getElementById('nextBtn');

    function selectCounselor(counselorId) {
        // Remove selection from all cards and radios
        cards.forEach(c => c.classList.remove('selected'));
        radios.forEach(r => r.classList.remove('checked'));

        // Add selection to the clicked card and its radio
        const selectedCard = document.querySelector(`.counselor-card[data-counselor-id="${counselorId}"]`);
        const selectedRadio = document.querySelector(`.custom-radio[data-counselor-id="${counselorId}"]`);
        
        if (selectedCard) selectedCard.classList.add('selected');
        if (selectedRadio) selectedRadio.classList.add('checked');

        // Update hidden input
        hiddenInput.value = counselorId;

        // Enable next button
        nextBtn.disabled = false;
    }

    // Card click handler
    cards.forEach(card => {
        card.addEventListener('click', function() {
            selectCounselor(this.dataset.counselorId);
        });
    });

    // Radio click handler
    radios.forEach(radio => {
        radio.addEventListener('click', function(e) {
            e.stopPropagation();
            selectCounselor(this.dataset.counselorId);
        });
    });
});
</script>
@endpush
