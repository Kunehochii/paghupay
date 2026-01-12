<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Paghupay') }} - @yield('title', 'Counselor Portal')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 70px;
            --counselor-primary: #198754;
            --counselor-primary-dark: #146c43;
        }

        body {
            min-height: 100vh;
            background-color: #f8f9fa;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, var(--counselor-primary) 0%, var(--counselor-primary-dark) 100%);
            z-index: 1000;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-brand {
            color: white;
            font-size: 1.25rem;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sidebar-brand:hover {
            color: rgba(255,255,255,0.9);
        }

        /* Profile Section */
        .sidebar-profile {
            padding: 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .profile-dropdown {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            border-radius: 0.5rem;
            background: rgba(255,255,255,0.1);
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
            color: white;
        }

        .profile-dropdown:hover {
            background: rgba(255,255,255,0.2);
            color: white;
        }

        .profile-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
            overflow: hidden;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-info {
            flex: 1;
            min-width: 0;
        }

        .profile-name {
            font-weight: 600;
            font-size: 0.95rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .profile-role {
            font-size: 0.75rem;
            opacity: 0.8;
        }

        /* Navigation */
        .sidebar-nav {
            flex: 1;
            padding: 1rem 0;
            overflow-y: auto;
        }

        .nav-section {
            padding: 0.5rem 1.25rem;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: rgba(255,255,255,0.5);
            margin-top: 0.5rem;
        }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1.25rem;
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .sidebar-nav .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        .sidebar-nav .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: white;
            border-left-color: white;
        }

        .sidebar-nav .nav-link i {
            font-size: 1.25rem;
            width: 24px;
            text-align: center;
        }

        .nav-badge {
            margin-left: auto;
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        .content-header {
            background: white;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .content-body {
            padding: 1.5rem;
        }

        /* Dropdown Menu */
        .sidebar .dropdown-menu {
            background: #2c3e50;
            border: none;
            min-width: 200px;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
        }

        .sidebar .dropdown-menu .dropdown-item {
            color: rgba(255,255,255,0.85);
            padding: 0.5rem 1rem;
        }

        .sidebar .dropdown-menu .dropdown-item:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        .sidebar .dropdown-menu .dropdown-divider {
            border-color: rgba(255,255,255,0.1);
        }

        /* Responsive */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-header {
                display: flex !important;
            }
        }

        /* Mobile Header */
        .mobile-header {
            display: none;
            background: var(--counselor-primary);
            color: white;
            padding: 1rem;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        /* Sidebar Overlay for Mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }

        .sidebar-overlay.show {
            display: block;
        }

        /* Card Hover Effect */
        .card-hover {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
        }

        /* Custom Scrollbar */
        .sidebar-nav::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar-nav::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.1);
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 3px;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Mobile Header -->
    <div class="mobile-header justify-content-between align-items-center">
        <button class="btn btn-link text-white p-0" id="sidebarToggle">
            <i class="bi bi-list fs-4"></i>
        </button>
        <span class="fw-semibold">{{ config('app.name', 'Paghupay') }}</span>
        <div style="width: 24px;"></div>
    </div>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <!-- Brand -->
        <div class="sidebar-header">
            <a href="{{ route('counselor.dashboard') }}" class="sidebar-brand">
                <i class="bi bi-heart-pulse"></i>
                <span>Paghupay</span>
            </a>
        </div>

        <!-- Profile Section -->
        <div class="sidebar-profile">
            <div class="dropdown">
                <a href="#" class="profile-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="profile-avatar">
                        @if(auth()->user()->counselorProfile && auth()->user()->counselorProfile->picture_url)
                            <img src="{{ Storage::url(auth()->user()->counselorProfile->picture_url) }}" alt="Profile">
                        @else
                            <i class="bi bi-person"></i>
                        @endif
                    </div>
                    <div class="profile-info">
                        <div class="profile-name">{{ auth()->user()->name }}</div>
                        <div class="profile-role">
                            {{ auth()->user()->counselorProfile->position ?? 'Counselor' }}
                        </div>
                    </div>
                    <i class="bi bi-chevron-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-person-circle me-2"></i> My Profile
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="sidebar-nav">
            <div class="nav-section">Main Menu</div>
            
            <a href="{{ route('counselor.dashboard') }}" 
               class="nav-link {{ request()->routeIs('counselor.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('counselor.appointments.index') }}" 
               class="nav-link {{ request()->routeIs('counselor.appointments.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-event"></i>
                <span>Appointments</span>
                @php
                    $pendingCount = \App\Models\Appointment::where('counselor_id', auth()->id())->pending()->count();
                @endphp
                @if($pendingCount > 0)
                    <span class="nav-badge badge bg-warning text-dark">{{ $pendingCount }}</span>
                @endif
            </a>

            <a href="{{ route('counselor.case-logs.index') }}" 
               class="nav-link {{ request()->routeIs('counselor.case-logs.*') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i>
                <span>Case Logs</span>
            </a>

            <div class="nav-section mt-4">Information</div>

            <a href="{{ route('counselor.about') }}" 
               class="nav-link {{ request()->routeIs('counselor.about') ? 'active' : '' }}">
                <i class="bi bi-info-circle"></i>
                <span>About Us</span>
            </a>
        </nav>

        <!-- Footer -->
        <div class="p-3 border-top border-light border-opacity-25">
            <small class="text-white-50 d-block text-center">
                © {{ date('Y') }} TUP-V Guidance
            </small>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-3 mb-0" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-3 mb-0" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="content-body">
            @yield('content')
        </div>
    </main>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Mobile sidebar toggle
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
            });
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', () => {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html>
