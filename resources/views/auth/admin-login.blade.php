<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Paghupay') }} - Admin Login</title>

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
            background-color: var(--color-secondary-dark);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Login Card */
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 50px 40px;
            width: 100%;
            max-width: 400px;
            margin: 20px;
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
    </style>
</head>
<body>
    <div class="login-card">
        <!-- Logo -->
        <div class="logo-wrapper">
            <img src="{{ asset('images/logo-guidance.png') }}" alt="Guidance Services Office Logo" onerror="this.src='https://via.placeholder.com/100?text=GSO'">
        </div>

        <!-- Title -->
        <h2 class="login-title">Admin Login</h2>
        <p class="login-subtitle">Enter your Admin ID and password to access the admin portal.</p>

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
        <form method="POST" action="{{ route('admin.login') }}">
            @csrf

            <div class="form-floating-custom">
                <i class="bi bi-shield-lock input-icon"></i>
                <input type="text" 
                       class="form-control @error('admin_id') is-invalid @enderror" 
                       id="admin_id" 
                       name="admin_id" 
                       value="{{ old('admin_id') }}" 
                       placeholder="Admin ID"
                       required 
                       autofocus>
                @error('admin_id')
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
            <p class="mb-0 text-muted small">
                <a href="{{ route('login') }}">Student Login</a>
                <span class="mx-2">|</span>
                <a href="{{ route('counselor.login') }}">Counselor Login</a>
            </p>
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
