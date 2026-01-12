@extends('layouts.app')

@section('title', 'Reason for Counseling')

@push('styles')
<style>
    /* Color Variables */
    :root {
        --color-primary-bg: #69d297;
        --color-primary-light: #a7f0ba;
        --color-secondary: #3d9f9b;
        --color-secondary-dark: #235675;
    }

    .reason-page {
        min-height: 100vh;
        background: linear-gradient(to bottom, var(--color-primary-bg) 40%, #ffffff 40%);
    }

    /* Top Navigation - Overlay */
    .nav-custom {
        background-color: transparent;
        padding: 15px 0;
        position: fixed;
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
        text-decoration: none;
    }

    .nav-custom .nav-link-custom:hover {
        background-color: #358a87;
        transform: scale(1.1);
        color: white;
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

    /* Step indicators */
    .step-indicators {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        margin-bottom: 20px;
        padding-top: 80px;
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
        background-color: var(--color-secondary);
        color: white;
    }

    .step-circle.inactive {
        background-color: white;
        color: #333;
        border: 2px solid #ccc;
    }

    /* Page Title */
    .page-title {
        text-align: center;
        font-size: 2rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 10px;
    }

    .page-subtitle {
        text-align: center;
        font-size: 1rem;
        color: #333;
        margin-bottom: 30px;
    }

    /* Reason Card */
    .reason-card {
        background-color: white;
        border-radius: 20px;
        border: 1px solid #e0e0e0;
        overflow: hidden;
        max-width: 900px;
        margin: 0 auto;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    /* Textarea styling */
    .reason-textarea {
        width: 100%;
        min-height: 350px;
        border: none;
        border-left: 3px solid var(--color-secondary);
        padding: 15px 20px;
        font-size: 1rem;
        color: #333;
        resize: vertical;
        outline: none;
        background-color: transparent;
    }

    .reason-textarea::placeholder {
        color: #999;
    }

    .reason-textarea:focus {
        outline: none;
        border-left-color: var(--color-primary-bg);
    }

    /* Submit Button */
    .btn-submit {
        background-color: var(--color-primary-light);
        border: none;
        color: #333;
        font-weight: 600;
        padding: 15px 60px;
        border-radius: 30px;
        font-size: 1rem;
        transition: all 0.3s ease;
        display: block;
        margin: 20px auto 0;
    }

    .btn-submit:hover:not(:disabled) {
        background-color: #8fe0a8;
        color: #333;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(167, 240, 186, 0.5);
    }

    .btn-submit:disabled {
        background-color: #cce8d4;
        color: #888;
        cursor: not-allowed;
    }

    /* Error styling */
    .error-message {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 10px;
        text-align: center;
    }

    /* Responsive adjustments */
    @media (max-width: 767.98px) {
        .reason-card {
            border-radius: 15px;
            margin: 0 15px;
            padding: 20px;
        }

        .reason-textarea {
            min-height: 250px;
        }

        .btn-submit {
            padding: 12px 40px;
        }

        .page-title {
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="reason-page">
    <!-- Top Navigation -->
    <nav class="nav-custom">
        <div class="container">
            <div class="d-flex justify-content-end align-items-center">
                <a href="{{ route('client.welcome') }}" class="nav-link-custom" title="Home">
                    <i class="bi bi-house-door-fill"></i>
                </a>
                <a href="#" class="nav-link-about">About us</a>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="nav-link-custom" title="Logout">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <!-- Step Indicators -->
        <div class="step-indicators">
            <div class="step-circle inactive">1</div>
            <div class="step-circle inactive">2</div>
            <div class="step-circle active">3</div>
        </div>

        <!-- Page Title -->
        <h1 class="page-title">Reason for Counseling</h1>
        <p class="page-subtitle">What would you like to discuss in this session?</p>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mx-auto" style="max-width: 900px;" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Reason Card -->
        <form action="{{ route('booking.store') }}" method="POST" id="reasonForm">
            @csrf

            <div class="reason-card">
                <textarea name="reason" 
                          id="reason" 
                          class="reason-textarea" 
                          placeholder="Enter text here"
                          minlength="10"
                          maxlength="1000"
                          required>{{ old('reason') }}</textarea>
                
                @error('reason')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <button type="submit" class="btn-submit" id="submitBtn">
                    Submit Response
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('reason');
    const submitBtn = document.getElementById('submitBtn');

    function validateForm() {
        const count = textarea.value.trim().length;
        submitBtn.disabled = count < 10;
    }

    textarea.addEventListener('input', validateForm);
    validateForm(); // Initial validation
});
</script>
@endpush
