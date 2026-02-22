<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Paghupay') }} - @yield('title', 'Admin')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --sidebar-width: 280px;
            --color-secondary-dark: #235675;
            --color-secondary: #3d9f9b;
        }

        body {
            background-color: #ffffff;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background-color: var(--color-secondary-dark);
            display: flex;
            flex-direction: column;
            z-index: 1000;
        }

        .admin-sidebar .sidebar-header {
            padding: 24px 20px;
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 12px;
        }

        .admin-sidebar .admin-avatar {
            width: 48px;
            height: 48px;
            background-color: #d1d5db;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .admin-sidebar .admin-avatar i {
            font-size: 28px;
            color: #6b7280;
        }

        .admin-sidebar .admin-name {
            color: #ffffff;
            font-size: 18px;
            font-weight: 500;
        }

        /* Navigation */
        .admin-sidebar .sidebar-nav {
            flex: 1;
            padding: 0;
            padding-left: 12px;
        }

        .admin-sidebar .nav-item {
            margin-left: 8px;
            margin-bottom: 4px;
        }

        .admin-sidebar .nav-link {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 30px;
            padding: 12px 16px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            border-radius: 12px 0 0 12px;
            transition: all 0.2s ease;
            font-size: 15px;
            font-weight: 600;
        }

        .admin-sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }

        .admin-sidebar .nav-link.active {
            background-color: #ffffff;
            color: var(--color-secondary-dark);
        }

        .admin-sidebar .nav-link i {
            font-size: 20px;
            width: 24px;
            text-align: center;
        }

        /* Logout */
        .admin-sidebar .sidebar-footer {
            padding: 20px 12px;
            display: flex;
            justify-content: flex-start;
            padding-left: 28px;
        }

        .admin-sidebar .logout-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 10px 16px;
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
            width: auto;
        }

        .admin-sidebar .logout-btn:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .admin-sidebar .logout-btn i {
            font-size: 18px;
        }

        /* Main Content */
        .admin-main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            padding: 32px 40px;
        }

        /* Card Styles for Dashboard */
        .stat-card {
            border: 2px solid var(--color-secondary);
            border-radius: 16px;
            background: #ffffff;
            padding: 24px 28px;
            text-decoration: none;
            color: inherit;
            transition: all 0.2s ease;
            display: block;
            position: relative;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            color: inherit;
        }

        .stat-card .card-label {
            font-size: 18px;
            color: var(--color-secondary-dark);
            margin-bottom: 8px;
        }

        .stat-card .card-value {
            font-size: 64px;
            font-weight: 600;
            color: var(--color-secondary-dark);
            line-height: 1;
        }

        .stat-card .card-arrow {
            width: 40px;
            height: 40px;
            border: 2px solid var(--color-secondary-dark);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-secondary-dark);
            position: absolute;
            right: 28px;
            top: 50%;
            transform: translateY(-50%);
        }

        .stat-card .card-arrow i {
            font-size: 20px;
        }

        /* Search Bar */
        .admin-search {
            background-color: #e9ecef;
            border: none;
            border-radius: 8px;
            padding: 12px 20px 12px 48px;
            font-size: 15px;
            width: 100%;
            max-width: 100%;
        }

        .admin-search:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(61, 159, 155, 0.3);
        }

        .search-wrapper {
            position: relative;
        }

        .search-wrapper i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 18px;
        }

        /* Responsive */
        @media (max-width: 991.98px) {
            .admin-sidebar {
                width: 240px;
            }
            .admin-main {
                margin-left: 240px;
            }
        }

        @media (max-width: 767.98px) {
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            .admin-sidebar.show {
                transform: translateX(0);
            }
            .admin-main {
                margin-left: 0;
                padding: 20px;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="admin-sidebar">
        <!-- Header with Admin Icon & Name -->
        <div class="sidebar-header">
            <div class="admin-avatar">
                <i class="bi bi-person"></i>
            </div>
            <span class="admin-name">Admin</span>
        </div>

        <!-- Navigation -->
        <nav class="sidebar-nav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-bar-chart-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.counselors.*') ? 'active' : '' }}" href="{{ route('admin.counselors.index') }}">
                        <i class="bi bi-person-badge"></i>
                        <span>Counselors</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}" href="{{ route('admin.clients.index') }}">
                        <i class="bi bi-people"></i>
                        <span>Users</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Footer with Logout -->
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="bi bi-box-arrow-left"></i>
                    <span>Log Out</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="admin-main">
        <!-- Top Header Bar with University Branding -->
        <div class="d-flex justify-content-end align-items-center mb-4">
            <span style="font-weight: 600; color: var(--color-secondary-dark); font-size: 14px; margin-right: 10px;">Technological University of the Philippines Visayas</span>
            <img src="{{ asset('images/tupv_logo.png') }}" alt="TUP-V Logo" style="height: 40px; width: 40px; border-radius: 50%; object-fit: cover;" onerror="this.src='https://via.placeholder.com/40?text=TUPV'">
        </div>

        @yield('content')
    </main>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>
