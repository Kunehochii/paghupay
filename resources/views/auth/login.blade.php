<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Paghupay') }} - Student Login</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            /* Paghupay Color System */
            --color-primary-bg: #69d297;
            --color-primary-light: #a7f0ba;
            --color-secondary: #3d9f9b;
            --color-secondary-dark: #235675;
            --color-btn-primary: #3d9f9b;
        }

        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        /* Navbar Styles */
        .login-navbar {
            background-color: var(--color-primary-bg);
            padding: 15px 40px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.5);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
            color: var(--color-secondary-dark) !important;
            font-size: 1.1rem;
        }

        .navbar-brand img {
            height: 45px;
            width: 45px;
            border-radius: 50%;
            object-fit: cover;
        }

        .nav-link-custom {
            color: var(--color-secondary-dark) !important;
            font-weight: 500;
            margin-right: 15px;
        }

        .btn-login-nav {
            background-color: white;
            color: var(--color-secondary-dark) !important;
            border: none;
            border-radius: 20px;
            padding: 8px 25px;
            font-weight: 500;
        }

        /* Main Container - Horizontal Split */
        .login-wrapper {
            min-height: calc(100vh - 77px);
            display: flex;
            flex-direction: column;
        }

        /* Top Section - Green (70%) */
        .top-section {
            flex: 7;
            background-color: var(--color-primary-bg);
            position: relative;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            padding-bottom: 0;
        }

        /* Bottom Section - White (30%) */
        .bottom-section {
            flex: 3;
            background-color: white;
        }

        /* Login Container - Spans both sections */
        .login-container {
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            transform: translateY(-50%);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px 40px;
            z-index: 10;
        }

        /* Form Side */
        .form-side {
            flex: 0 0 40%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Illustration Side */
        .illustration-side {
            flex: 0 0 60%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .illustration-side img {
            max-width: 100%;
            max-height: 80vh;
            object-fit: contain;
        }

        /* Login Card */
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            padding: 40px;
            width: 100%;
            max-width: 380px;
        }

        .logo-wrapper {
            text-align: center;
            margin-bottom: 25px;
        }

        .logo-wrapper img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 4px solid var(--color-secondary);
            padding: 5px;
            background: white;
        }

        .login-title {
            color: var(--color-secondary);
            font-weight: 600;
            font-size: 1.8rem;
            text-align: center;
            margin-bottom: 5px;
        }

        .login-subtitle {
            color: #6c757d;
            text-align: center;
            font-size: 0.9rem;
            margin-bottom: 30px;
        }

        /* Form Inputs */
        .form-floating-custom {
            position: relative;
            margin-bottom: 20px;
        }

        .form-floating-custom .form-control {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 15px 15px 15px 50px;
            height: auto;
            font-size: 0.95rem;
        }

        .form-floating-custom .form-control:focus {
            border-color: var(--color-secondary);
            box-shadow: 0 0 0 3px rgba(61, 159, 155, 0.15);
        }

        .form-floating-custom .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #9e9e9e;
            font-size: 1.1rem;
        }

        .form-floating-custom .toggle-password {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #9e9e9e;
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
        }

        .form-floating-custom .toggle-password:hover {
            color: var(--color-secondary);
        }

        /* Submit Button */
        .btn-login {
            background-color: var(--color-btn-primary);
            border: none;
            border-radius: 10px;
            padding: 14px;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background-color: #358a87;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(61, 159, 155, 0.4);
        }

        /* Links */
        .login-links {
            text-align: center;
            margin-top: 25px;
        }

        .login-links a {
            color: var(--color-secondary);
            text-decoration: none;
            font-size: 0.85rem;
        }

        .login-links a:hover {
            text-decoration: underline;
        }

        /* Alert Styles */
        .alert-custom {
            border-radius: 10px;
            font-size: 0.85rem;
            margin-bottom: 20px;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .login-container {
                flex-direction: column;
                position: relative;
                transform: none;
                top: auto;
            }

            .form-side {
                flex: 1;
                width: 100%;
                padding: 20px;
            }

            .illustration-side {
                display: none;
            }

            .top-section {
                flex: 1;
            }

            .bottom-section {
                display: none;
            }

            .login-wrapper {
                background-color: var(--color-primary-bg);
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="login-navbar d-flex justify-content-between align-items-center">
        <a href="/" class="navbar-brand text-decoration-none">
            <img src="{{ asset('images/logo-landscape.png') }}" alt="TUPV Logo" onerror="this.style.display='none'">
            <span>TUPV Guidance Services Office</span>
        </a>
        <div class="d-flex align-items-center">
            <a href="/" class="nav-link-custom text-decoration-none">Home</a>
            <a href="{{ route('login') }}" class="btn btn-login-nav">Log in</a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="login-wrapper">
        <div class="top-section"></div>
        <div class="bottom-section"></div>
        
        <!-- Login Container - Positioned over both sections -->
        <div class="login-container">
            <!-- Form Side -->
            <div class="form-side">
                <div class="login-card">
                    <!-- Logo -->
                    <div class="logo-wrapper">
                        <img src="{{ asset('images/logo-guidance.png') }}" alt="Guidance Services Office Logo" onerror="this.src='https://via.placeholder.com/100?text=GSO'">
                    </div>

                    <!-- Title -->
                    <h2 class="login-title">Student Login</h2>
                    <p class="login-subtitle">Enter your credentials to access your account.</p>

                    <!-- Alerts -->
                    @if (session('error'))
                        <div class="alert alert-danger alert-custom">
                            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success alert-custom">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-floating-custom">
                            <i class="bi bi-envelope input-icon"></i>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   placeholder="TUPV ID"
                                   required 
                                   autofocus>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating-custom">
                            <i class="bi bi-lock input-icon"></i>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Password"
                                   required>
                            <button type="button" class="toggle-password" onclick="togglePassword()">
                                <i class="bi bi-eye" id="toggleIcon"></i>
                            </button>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-login">
                            Sign In
                        </button>
                    </form>

                    <!-- Links -->
                    <div class="login-links">
                        <p class="mb-2">
                            Don't have an account? 
                            <a href="{{ route('register') }}">Register here</a>
                        </p>
                        <p class="mb-0 text-muted small">
                            <a href="{{ route('counselor.login') }}">Counselor Login</a>
                            <span class="mx-2">|</span>
                            <a href="{{ route('admin.login') }}">Admin Login</a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Illustration Side -->
            <div class="illustration-side">
                <img src="{{ asset('images/illustration-login.png') }}" alt="Counseling Illustration">
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        }
    </script>
</body>
</html>
