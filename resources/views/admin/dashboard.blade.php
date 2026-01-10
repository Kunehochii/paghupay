@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
    <!-- Search Bar -->
    <div class="search-wrapper mb-4">
        <i class="bi bi-search"></i>
        <input type="text" class="admin-search" placeholder="Search">
    </div>

    <!-- Stats Cards -->
    <div class="row g-4">
        <!-- Counselors Card -->
        <div class="col-md-6">
            <a href="{{ route('admin.counselors.index') }}" class="stat-card d-block">
                <div>
                    <div class="card-label">Counselors</div>
                    <div class="card-value">{{ $stats['total_counselors'] }}</div>
                </div>
                <div class="card-arrow">
                    <i class="bi bi-arrow-right"></i>
                </div>
            </a>
        </div>

        <!-- Users Card -->
        <div class="col-md-6">
            <a href="{{ route('admin.clients.index') }}" class="stat-card d-block">
                <div>
                    <div class="card-label">Users</div>
                    <div class="card-value">
                        @if($stats['total_clients'] >= 1000)
                            {{ number_format($stats['total_clients'] / 1000, 1) }}k
                        @else
                            {{ $stats['total_clients'] }}
                        @endif
                    </div>
                </div>
                <div class="card-arrow">
                    <i class="bi bi-arrow-right"></i>
                </div>
            </a>
        </div>
    </div>
@endsection
