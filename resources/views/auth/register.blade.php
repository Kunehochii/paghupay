@extends('layouts.app')

@section('title', 'Student Registration')

@section('content')
<div class="container">
    <div class="row justify-content-center py-5">
        <div class="col-md-6">
            <div class="text-center mb-4">
                <h2 class="fw-bold text-primary">
                    <i class="bi bi-heart-pulse me-2"></i>Paghupay
                </h2>
                <p class="text-muted">TUP-V Guidance & Counseling System</p>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-person-plus me-2"></i>Student Registration
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       placeholder="Juan Dela Cruz"
                                       required 
                                       autofocus>
                            </div>
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       placeholder="student@tup.edu.ph"
                                       required>
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Use your official TUP email address.</div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Minimum 8 characters"
                                       required>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       placeholder="Re-enter your password"
                                       required>
                            </div>
                        </div>

                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="bi bi-shield-check text-primary me-2"></i>Data Privacy Agreement
                                </h6>
                                <p class="card-text small text-muted mb-3">
                                    By registering, you agree to the collection and processing of your personal data 
                                    in accordance with the <strong>Data Privacy Act of 2012 (RA 10173)</strong>. 
                                    Your information will only be used for guidance and counseling purposes within TUP-V.
                                </p>
                                <div class="form-check">
                                    <input type="checkbox" 
                                           class="form-check-input @error('agree_terms') is-invalid @enderror" 
                                           id="agree_terms" 
                                           name="agree_terms"
                                           required>
                                    <label class="form-check-label" for="agree_terms">
                                        I agree to the Data Privacy Policy <span class="text-danger">*</span>
                                    </label>
                                </div>
                                @error('agree_terms')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-person-plus me-2"></i>Create Account
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-light text-center py-3">
                    <p class="mb-0">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">Login here</a>
                    </p>
                </div>
            </div>

            <div class="text-center mt-4">
                <small class="text-muted">
                    <a href="{{ route('counselor.login') }}" class="text-decoration-none">Counselor Login</a>
                    <span class="mx-2">|</span>
                    <a href="{{ route('admin.login') }}" class="text-decoration-none">Admin Login</a>
                </small>
            </div>
        </div>
    </div>
</div>
@endsection
