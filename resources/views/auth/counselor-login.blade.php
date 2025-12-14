@extends('layouts.app')

@section('title', 'Counselor Login')

@section('content')
<div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center">
        <div class="col-md-5">
            <div class="text-center mb-4">
                <h2 class="fw-bold text-success">
                    <i class="bi bi-heart-pulse me-2"></i>Paghupay
                </h2>
                <p class="text-muted">TUP-V Guidance & Counseling System</p>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white text-center py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-person-badge me-2"></i>Counselor Login
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Device Lock Notice -->
                    <div class="alert alert-info small mb-4">
                        <i class="bi bi-shield-lock me-2"></i>
                        <strong>Security Notice:</strong> Your account will be bound to this device on first login. 
                        Contact admin if you need to switch devices.
                    </div>

                    <form method="POST" action="{{ route('counselor.login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white"><i class="bi bi-envelope"></i></span>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       placeholder="counselor@tup.edu.ph"
                                       required 
                                       autofocus>
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white"><i class="bi bi-lock"></i></span>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Enter your password"
                                       required>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Login
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="text-center mt-4">
                <small class="text-muted">
                    <a href="{{ route('login') }}" class="text-decoration-none">Student Login</a>
                    <span class="mx-2">|</span>
                    <a href="{{ route('admin.login') }}" class="text-decoration-none">Admin Login</a>
                </small>
            </div>
        </div>
    </div>
</div>
@endsection
