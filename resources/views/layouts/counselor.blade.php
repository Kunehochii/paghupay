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
            --sidebar-width: 300px;
            --sidebar-collapsed-width: 70px;
            --color-primary-bg: #69d297;
            --color-primary-light: #a7f0ba;
            --color-secondary: #3d9f9b;
            --color-secondary-dark: #235675;
            --color-btn-primary: #3d9f9b;
        }

        body {
            min-height: 100vh;
            background-color: #ffffff;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: white;
            z-index: 1000;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
        }

        /* Profile Section at Top */
        .sidebar-profile {
            padding: 1.25rem;
            background: white;
            border-bottom: 1px solid #e9ecef;
        }

        .profile-dropdown {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
            color: var(--color-secondary-dark);
        }

        .profile-dropdown:hover {
            background: #f8f9fa;
            color: var(--color-secondary-dark);
        }

        .profile-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: var(--color-secondary-dark);
            overflow: hidden;
            border: 2px solid var(--color-secondary);
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
            font-size: 1rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: var(--color-secondary-dark);
        }

        /* Navigation Section */
        .sidebar-nav-wrapper {
            background: white;
            padding: 1rem 0;
        }

        .nav-section-label {
            padding: 0.75rem 1.25rem 0.5rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: #6c757d;
        }

        .sidebar-nav {
            padding: 0;
        }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1.25rem;
            color: var(--color-secondary-dark);
            text-decoration: none;
            transition: all 0.2s;
            border-left: 4px solid transparent;
            font-weight: 500;
        }

        .sidebar-nav .nav-link:hover {
            background: #f8f9fa;
            color: var(--color-secondary-dark);
        }

        .sidebar-nav .nav-link.active {
            background: #f0f0f0;
            color: var(--color-secondary-dark);
            border-left-color: var(--color-secondary);
        }

        .sidebar-nav .nav-link i {
            font-size: 1.25rem;
            width: 24px;
            text-align: center;
            color: var(--color-secondary-dark);
        }

        .nav-badge {
            margin-left: auto;
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
        }

        /* Sidebar Footer - Secondary Teal Background */
        .sidebar-footer {
            flex: 1;
            background: var(--color-secondary-dark);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }

        .sidebar-footer-content {
            padding: 1rem;
            text-align: center;
        }

        .sidebar-footer-content small {
            color: rgba(255, 255, 255, 0.8);
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s ease;
            background-color: #f5f5f5;
        }

        .content-body {
            padding: 1.5rem 2rem;
        }

        /* Dropdown Menu */
        .sidebar .dropdown-menu {
            background: white;
            border: 1px solid #e9ecef;
            min-width: 200px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            border-radius: 0.5rem;
        }

        .sidebar .dropdown-menu .dropdown-item {
            color: var(--color-secondary-dark);
            padding: 0.625rem 1rem;
        }

        .sidebar .dropdown-menu .dropdown-item:hover {
            background: #f8f9fa;
            color: var(--color-secondary-dark);
        }

        .sidebar .dropdown-menu .dropdown-item i {
            color: var(--color-secondary-dark);
        }

        .sidebar .dropdown-menu .dropdown-divider {
            border-color: #e9ecef;
        }

        /* Search Bar Styles */
        .search-bar-wrapper {
            background: white;
            padding: 1rem 2rem;
            border-bottom: 1px solid #e9ecef;
        }

        .search-input-wrapper {
            position: relative;
            max-width: 600px;
        }

        .search-input-wrapper i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .search-input {
            padding-left: 2.75rem;
            border-radius: 0.5rem;
            border: 1px solid #e9ecef;
            background: #f8f9fa;
        }

        .search-input:focus {
            background: white;
            border-color: var(--color-secondary);
            box-shadow: 0 0 0 0.2rem rgba(61, 159, 155, 0.15);
        }

        /* Welcome Banner */
        .welcome-banner {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 0.5rem;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
        }

        .welcome-banner h5 {
            color: var(--color-secondary-dark);
            margin: 0;
            font-weight: 500;
        }

        /* Dashboard Cards */
        .dashboard-card {
            background: white;
            border: 2px solid var(--color-secondary-dark);
            border-radius: 0.75rem;
            padding: 1.5rem;
            height: 100%;
            transition: all 0.2s;
            position: relative;
        }

        .dashboard-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .dashboard-card-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--color-secondary-dark);
            margin-bottom: 1rem;
        }

        .dashboard-card-value {
            font-size: 3.5rem;
            font-weight: 700;
            color: var(--color-secondary-dark);
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .dashboard-card-subtitle {
            font-size: 0.875rem;
            color: var(--color-secondary-dark);
        }

        .dashboard-card-arrow {
            position: absolute;
            right: 1.5rem;
            top: 50%;
            transform: translateY(-50%);
        }

        .dashboard-card-arrow i {
            font-size: 1.5rem;
            color: var(--color-secondary-dark);
            border: 2px solid var(--color-secondary-dark);
            border-radius: 50%;
            padding: 0.5rem;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .card-link:hover {
            color: inherit;
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
            background: var(--color-secondary);
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
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .sidebar-overlay.show {
            display: block;
        }

        /* Custom Scrollbar */
        .sidebar-nav::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar-nav::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: #ccc;
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
        <!-- Profile Section at Top -->
        <div class="sidebar-profile">
            <div class="dropdown">
                <a href="#" class="profile-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="profile-avatar">
                        @if (auth()->user()->counselorProfile && auth()->user()->counselorProfile->picture_url)
                            <img src="{{ Storage::url(auth()->user()->counselorProfile->picture_url) }}" alt="Profile">
                        @else
                            <i class="bi bi-person"></i>
                        @endif
                    </div>
                    <div class="profile-info">
                        <div class="profile-name">{{ Str::limit(auth()->user()->name, 20) }}</div>
                    </div>
                    <i class="bi bi-chevron-down" style="color: var(--color-secondary-dark);"></i>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-person-circle me-2"></i> My Profile
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
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

        <!-- Navigation Section (White Background) -->
        <div class="sidebar-nav-wrapper">
            <div class="nav-section-label">Primary Menu</div>
            <nav class="sidebar-nav">
                <a href="{{ route('counselor.dashboard') }}"
                    class="nav-link {{ request()->routeIs('counselor.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart-line"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('counselor.appointments.index') }}"
                    class="nav-link {{ request()->routeIs('counselor.appointments.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar3"></i>
                    <span>Appointment</span>
                    @php
                        $pendingCount = \App\Models\Appointment::where('counselor_id', auth()->id())
                            ->pending()
                            ->count();
                    @endphp
                    @if ($pendingCount > 0)
                        <span class="nav-badge badge bg-warning text-dark">{{ $pendingCount }}</span>
                    @endif
                </a>

                <a href="{{ route('counselor.case-logs.index') }}"
                    class="nav-link {{ request()->routeIs('counselor.case-logs.*') ? 'active' : '' }}">
                    <i class="bi bi-journal-text"></i>
                    <span>Case Logs</span>
                </a>

                <a href="{{ route('counselor.availability.index') }}"
                    class="nav-link {{ request()->routeIs('counselor.availability.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i>
                    <span>My Availability</span>
                </a>

                <a href="{{ route('counselor.about') }}"
                    class="nav-link {{ request()->routeIs('counselor.about') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span>About us</span>
                </a>
            </nav>
        </div>

        <!-- Footer Section (Teal Background) -->
        <div class="sidebar-footer">
            <div class="sidebar-footer-content">
                <small>© {{ date('Y') }}. All Rights Reserved</small>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        {{-- Top Header Bar with University Branding --}}
        <div class="search-bar-wrapper">
            <div class="d-flex justify-content-end align-items-center">
                <span style="font-weight: 600; color: var(--color-secondary-dark); font-size: 14px; margin-right: 10px;">Technological University of the Philippines Visayas</span>
                <img src="{{ asset('images/tupv_logo.png') }}" alt="TUP-V Logo" style="height: 40px; width: 40px; border-radius: 50%; object-fit: cover;" onerror="this.src='https://via.placeholder.com/40?text=TUPV'">
            </div>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mx-4 mt-3 mb-0" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mx-4 mt-3 mb-0" role="alert">
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
