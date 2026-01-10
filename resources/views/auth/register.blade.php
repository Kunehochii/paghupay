@extends('layouts.app')

@section('title', 'Registration Form')
@section('hideNavbar', true)
@section('bodyClass', '')

@push('styles')
<style>
    body {
        background-color: #3d9f9b !important;
        min-height: 100vh;
    }

    .registration-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
    }

    .registration-card {
        background: white;
        border-radius: 8px;
        max-width: 900px;
        width: 100%;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        padding: 40px;
        position: relative;
    }

    .registration-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 30px;
    }

    .registration-title {
        color: #235675;
        font-size: 1.5rem;
        font-weight: 700;
        text-decoration: underline;
        text-underline-offset: 5px;
        margin-bottom: 0;
    }

    .user-icon {
        width: 50px;
        height: 50px;
        background-color: #3d9f9b;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
    }

    .section-title {
        color: #4a5568;
        font-size: 1rem;
        font-weight: 500;
        margin-bottom: 20px;
    }

    .form-label-custom {
        font-size: 0.85rem;
        color: #4a5568;
        margin-bottom: 5px;
        font-weight: 500;
    }

    .form-control-custom {
        border: 1px solid #ccc;
        border-radius: 6px;
        padding: 10px 12px;
        font-size: 0.9rem;
        width: 100%;
        transition: border-color 0.2s ease;
    }

    .form-control-custom:focus {
        outline: none;
        border-color: #3d9f9b;
        box-shadow: 0 0 0 2px rgba(61, 159, 155, 0.1);
    }

    .form-control-custom.is-invalid {
        border-color: #dc3545;
    }

    .invalid-feedback {
        font-size: 0.8rem;
        color: #dc3545;
        margin-top: 4px;
    }

    .checkbox-group {
        display: flex;
        gap: 20px;
        padding: 10px 0;
    }

    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .checkbox-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #3d9f9b;
    }

    .checkbox-item label {
        font-size: 0.9rem;
        color: #4a5568;
        cursor: pointer;
    }

    .section-divider {
        margin: 30px 0 20px;
        border: 0;
        border-top: 1px solid #e2e8f0;
    }

    .btn-proceed {
        background-color: #3d9f9b;
        border: none;
        color: white;
        font-weight: 600;
        padding: 12px 50px;
        border-radius: 25px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1rem;
    }

    .btn-proceed:hover {
        background-color: #358a87;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(61, 159, 155, 0.4);
        color: white;
    }

    .btn-proceed:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 15px;
    }

    .form-row.two-col {
        grid-template-columns: repeat(2, 1fr);
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .form-row.two-col {
            grid-template-columns: 1fr;
        }
        
        .registration-card {
            padding: 25px;
        }
    }

    .form-group {
        margin-bottom: 0;
    }

    .alert-custom {
        background-color: #fff3cd;
        border: 1px solid #ffc107;
        border-radius: 6px;
        padding: 12px 15px;
        margin-bottom: 20px;
        font-size: 0.9rem;
        color: #856404;
    }

    .alert-error {
        background-color: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
    }

    .email-display {
        background-color: #f7fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 10px 12px;
        font-size: 0.9rem;
        color: #4a5568;
    }

    .logout-link {
        display: block;
        text-align: center;
        margin-top: 20px;
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.9rem;
        text-decoration: none;
    }

    .logout-link:hover {
        color: white;
        text-decoration: underline;
    }
</style>
@endpush

@section('content')
<div class="registration-container">
    <div>
        <div class="registration-card">
            <div class="registration-header">
                <h1 class="registration-title">Registration Form</h1>
                <div class="user-icon">
                    <i class="bi bi-person-fill"></i>
                </div>
            </div>

            @if (session('info'))
                <div class="alert-custom">
                    <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert-custom alert-error">
                    <i class="bi bi-x-circle me-2"></i>{{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Personal Background Section --}}
                <h2 class="section-title">Personal Background</h2>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label-custom">Full Name</label>
                        <input type="text" 
                               class="form-control-custom @error('name') is-invalid @enderror" 
                               name="name" 
                               value="{{ old('name') }}" 
                               placeholder=""
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label-custom">Nickname</label>
                        <input type="text" 
                               class="form-control-custom @error('nickname') is-invalid @enderror" 
                               name="nickname" 
                               value="{{ old('nickname') }}" 
                               placeholder=""
                               required>
                        @error('nickname')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label-custom">Course & Yr./Section</label>
                        <input type="text" 
                               class="form-control-custom @error('course_year_section') is-invalid @enderror" 
                               name="course_year_section" 
                               value="{{ old('course_year_section') }}" 
                               placeholder=""
                               required>
                        @error('course_year_section')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label-custom">Sex</label>
                        <div class="checkbox-group">
                            <div class="checkbox-item">
                                <input type="checkbox" 
                                       id="sex_male" 
                                       name="sex" 
                                       value="Male"
                                       {{ old('sex') == 'Male' ? 'checked' : '' }}
                                       onchange="handleSexSelection('Male')">
                                <label for="sex_male">Male</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" 
                                       id="sex_female" 
                                       name="sex" 
                                       value="Female"
                                       {{ old('sex') == 'Female' ? 'checked' : '' }}
                                       onchange="handleSexSelection('Female')">
                                <label for="sex_female">Female</label>
                            </div>
                        </div>
                        @error('sex')
                            <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label-custom">Birthdate</label>
                        <input type="date" 
                               class="form-control-custom @error('birthdate') is-invalid @enderror" 
                               name="birthdate" 
                               value="{{ old('birthdate') }}"
                               required>
                        @error('birthdate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label-custom">Birthplace</label>
                        <input type="text" 
                               class="form-control-custom @error('birthplace') is-invalid @enderror" 
                               name="birthplace" 
                               value="{{ old('birthplace') }}" 
                               placeholder=""
                               required>
                        @error('birthplace')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label-custom">Contact No.</label>
                        <input type="text" 
                               class="form-control-custom @error('contact_number') is-invalid @enderror" 
                               name="contact_number" 
                               value="{{ old('contact_number') }}" 
                               placeholder=""
                               required>
                        @error('contact_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label-custom">E-Mail</label>
                        <div class="email-display">{{ auth()->user()->email ?? '' }}</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label-custom">FB Account</label>
                        <input type="text" 
                               class="form-control-custom @error('fb_account') is-invalid @enderror" 
                               name="fb_account" 
                               value="{{ old('fb_account') }}" 
                               placeholder="">
                        @error('fb_account')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label-custom">Nationality</label>
                        <input type="text" 
                               class="form-control-custom @error('nationality') is-invalid @enderror" 
                               name="nationality" 
                               value="{{ old('nationality', 'Filipino') }}" 
                               required>
                        @error('nationality')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label-custom">Address (while enrolled at TUPV)</label>
                        <input type="text" 
                               class="form-control-custom @error('address') is-invalid @enderror" 
                               name="address" 
                               value="{{ old('address') }}" 
                               placeholder=""
                               required>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label-custom">Home Address</label>
                        <input type="text" 
                               class="form-control-custom @error('home_address') is-invalid @enderror" 
                               name="home_address" 
                               value="{{ old('home_address') }}" 
                               placeholder=""
                               required>
                        @error('home_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label-custom">Name of Guardian</label>
                        <input type="text" 
                               class="form-control-custom @error('guardian_name') is-invalid @enderror" 
                               name="guardian_name" 
                               value="{{ old('guardian_name') }}" 
                               placeholder=""
                               required>
                        @error('guardian_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label-custom">Relationship</label>
                        <input type="text" 
                               class="form-control-custom @error('guardian_relationship') is-invalid @enderror" 
                               name="guardian_relationship" 
                               value="{{ old('guardian_relationship') }}" 
                               placeholder=""
                               required>
                        @error('guardian_relationship')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label-custom">Contact No.</label>
                        <input type="text" 
                               class="form-control-custom @error('guardian_contact') is-invalid @enderror" 
                               name="guardian_contact" 
                               value="{{ old('guardian_contact') }}" 
                               placeholder=""
                               required>
                        @error('guardian_contact')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="section-divider">

                {{-- Account Details Section --}}
                <h2 class="section-title">Account Details</h2>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label-custom">Input Old Password</label>
                        <input type="password" 
                               class="form-control-custom @error('current_password') is-invalid @enderror" 
                               name="current_password" 
                               placeholder=""
                               required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label-custom">Input New Password</label>
                        <input type="password" 
                               class="form-control-custom @error('password') is-invalid @enderror" 
                               name="password" 
                               placeholder=""
                               required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label-custom">Confirm New Password</label>
                        <input type="password" 
                               class="form-control-custom" 
                               name="password_confirmation" 
                               placeholder=""
                               required>
                    </div>
                </div>

                {{-- Hidden agree_terms field - will be handled by the agreement page --}}
                <input type="hidden" name="agree_terms" value="1">

                <div class="text-center mt-4">
                    <button type="submit" class="btn-proceed">
                        Proceed
                    </button>
                </div>
            </form>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-link" style="background: none; border: none; width: 100%;">
                <i class="bi bi-box-arrow-left me-1"></i>Logout
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function handleSexSelection(selected) {
        const maleCheckbox = document.getElementById('sex_male');
        const femaleCheckbox = document.getElementById('sex_female');
        
        if (selected === 'Male') {
            femaleCheckbox.checked = false;
        } else {
            maleCheckbox.checked = false;
        }
    }
</script>
@endpush
@endsection
