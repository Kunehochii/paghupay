@extends('layouts.app')

@section('title', 'Complete Registration')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-4">
                <h2 class="fw-bold text-primary">
                    <i class="bi bi-heart-pulse me-2"></i>Paghupay
                </h2>
                <p class="text-muted">TUP-V Guidance & Counseling System</p>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 text-center">
                        <i class="bi bi-person-check me-2"></i>Complete Your Registration
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if (session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-x-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="alert alert-warning mb-4">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Welcome!</strong> Please change your password and complete your profile to continue.
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        {{-- Password Change Section --}}
                        <div class="card bg-light mb-4">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="bi bi-key me-2"></i>Change Password</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="current_password" class="form-label">Current Password <span class="text-danger">*</span></label>
                                        <input type="password" 
                                               class="form-control @error('current_password') is-invalid @enderror" 
                                               id="current_password" 
                                               name="current_password" 
                                               placeholder="Temporary password"
                                               required>
                                        @error('current_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Enter the password from your invitation email</div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="password" class="form-label">New Password <span class="text-danger">*</span></label>
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password" 
                                               placeholder="Minimum 8 characters"
                                               required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                        <input type="password" 
                                               class="form-control" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               placeholder="Re-enter new password"
                                               required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Personal Information Section --}}
                        <div class="card bg-light mb-4">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="bi bi-person me-2"></i>Personal Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name') }}" 
                                               placeholder="Juan Dela Cruz"
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="nickname" class="form-label">Nickname <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('nickname') is-invalid @enderror" 
                                               id="nickname" 
                                               name="nickname" 
                                               value="{{ old('nickname') }}" 
                                               placeholder="What should we call you?"
                                               required>
                                        @error('nickname')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="course_year_section" class="form-label">Course/Year/Section <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('course_year_section') is-invalid @enderror" 
                                               id="course_year_section" 
                                               name="course_year_section" 
                                               value="{{ old('course_year_section') }}" 
                                               placeholder="e.g., BSIT-4A"
                                               required>
                                        @error('course_year_section')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="birthdate" class="form-label">Birthdate <span class="text-danger">*</span></label>
                                        <input type="date" 
                                               class="form-control @error('birthdate') is-invalid @enderror" 
                                               id="birthdate" 
                                               name="birthdate" 
                                               value="{{ old('birthdate') }}"
                                               required>
                                        @error('birthdate')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="birthplace" class="form-label">Birthplace <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('birthplace') is-invalid @enderror" 
                                               id="birthplace" 
                                               name="birthplace" 
                                               value="{{ old('birthplace') }}" 
                                               placeholder="City/Municipality"
                                               required>
                                        @error('birthplace')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="sex" class="form-label">Sex <span class="text-danger">*</span></label>
                                        <select class="form-select @error('sex') is-invalid @enderror" 
                                                id="sex" 
                                                name="sex" 
                                                required>
                                            <option value="">Select...</option>
                                            <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                        @error('sex')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="contact_number" class="form-label">Contact Number <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('contact_number') is-invalid @enderror" 
                                               id="contact_number" 
                                               name="contact_number" 
                                               value="{{ old('contact_number') }}" 
                                               placeholder="09XX XXX XXXX"
                                               required>
                                        @error('contact_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="nationality" class="form-label">Nationality <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('nationality') is-invalid @enderror" 
                                               id="nationality" 
                                               name="nationality" 
                                               value="{{ old('nationality', 'Filipino') }}" 
                                               required>
                                        @error('nationality')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="fb_account" class="form-label">Facebook Account</label>
                                        <input type="text" 
                                               class="form-control @error('fb_account') is-invalid @enderror" 
                                               id="fb_account" 
                                               name="fb_account" 
                                               value="{{ old('fb_account') }}" 
                                               placeholder="Profile link or name (optional)">
                                        @error('fb_account')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="address" class="form-label">Current Address <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                                  id="address" 
                                                  name="address" 
                                                  rows="2" 
                                                  placeholder="Where are you currently staying?"
                                                  required>{{ old('address') }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="home_address" class="form-label">Home Address <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('home_address') is-invalid @enderror" 
                                                  id="home_address" 
                                                  name="home_address" 
                                                  rows="2" 
                                                  placeholder="Permanent home address"
                                                  required>{{ old('home_address') }}</textarea>
                                        @error('home_address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Guardian Information Section --}}
                        <div class="card bg-light mb-4">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="bi bi-people me-2"></i>Guardian Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="guardian_name" class="form-label">Guardian Name <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('guardian_name') is-invalid @enderror" 
                                               id="guardian_name" 
                                               name="guardian_name" 
                                               value="{{ old('guardian_name') }}" 
                                               placeholder="Full name"
                                               required>
                                        @error('guardian_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="guardian_relationship" class="form-label">Relationship <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('guardian_relationship') is-invalid @enderror" 
                                               id="guardian_relationship" 
                                               name="guardian_relationship" 
                                               value="{{ old('guardian_relationship') }}" 
                                               placeholder="e.g., Mother, Father"
                                               required>
                                        @error('guardian_relationship')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="guardian_contact" class="form-label">Guardian Contact <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('guardian_contact') is-invalid @enderror" 
                                               id="guardian_contact" 
                                               name="guardian_contact" 
                                               value="{{ old('guardian_contact') }}" 
                                               placeholder="09XX XXX XXXX"
                                               required>
                                        @error('guardian_contact')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Data Privacy Agreement --}}
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
                                           {{ old('agree_terms') ? 'checked' : '' }}
                                           required>
                                    <label class="form-check-label" for="agree_terms">
                                        I agree to the Data Privacy Policy <span class="text-danger">*</span>
                                    </label>
                                    @error('agree_terms')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle me-2"></i>Complete Registration
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="text-center mt-4">
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link text-muted text-decoration-none">
                        <i class="bi bi-box-arrow-left me-1"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
