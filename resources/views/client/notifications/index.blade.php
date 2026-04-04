@extends('layouts.app')

@section('title', 'Notifications')

@push('styles')
    @include('layouts.partials.notification-styles')
    <style>
        .nav-custom {
            background-color: transparent;
            padding: 15px 0;
        }

        .nav-custom .nav-link-custom {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #3d9f9b;
            color: white;
            margin: 0 5px;
            transition: all 0.3s ease;
        }

        .nav-custom .nav-link-custom:hover {
            background-color: #358f8b;
            transform: scale(1.1);
        }

        .nav-custom .nav-link-about {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 20px;
            border-radius: 20px;
            border: 2px solid #333;
            background-color: transparent;
            color: #333;
            font-weight: 500;
            margin: 0 5px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .nav-custom .nav-link-about:hover {
            background-color: #333;
            color: white;
        }

        .notification-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: background 0.2s;
        }

        .notification-card.unread {
            background-color: #e7f3ff;
            border-left: 4px solid #3d9f9b;
        }

        .notification-card.read {
            background-color: #fff;
            border-left: 4px solid #dee2e6;
        }

        .status-badge {
            font-size: 0.75rem;
            padding: 4px 10px;
            border-radius: 12px;
        }

        .status-accepted { background-color: #d4edda; color: #155724; }
        .status-cancelled { background-color: #f8d7da; color: #721c24; }
        .status-completed { background-color: #cce5ff; color: #004085; }
        .status-rescheduled { background-color: #fff3cd; color: #856404; }
    </style>
@endpush

@section('content')
    <div class="bg-white min-vh-100">
        <!-- Custom Navigation -->
        <nav class="nav-custom">
            <div class="container">
                <div class="d-flex justify-content-center align-items-center">
                    <a href="{{ route('client.welcome') }}" class="nav-link-custom" title="Home">
                        <i class="bi bi-house-door-fill"></i>
                    </a>
                    <a href="{{ route('client.about') }}" class="nav-link-about">About us</a>
                    @include('layouts.partials.notification-bell')
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="nav-link-custom" title="Log Out"
                            style="border: none; cursor: pointer;">
                            <i class="bi bi-box-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <div class="container py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h4 mb-0">
                    <i class="bi bi-bell me-2"></i>Notifications
                </h2>
                @if($notifications->where('read_at', null)->count() > 0)
                    <form action="{{ route('notifications.read-all') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-check-all me-1"></i>Mark all as read
                        </button>
                    </form>
                @endif
            </div>

            @forelse($notifications as $notification)
                <div class="card notification-card {{ $notification->read_at ? 'read' : 'unread' }} mb-3">
                    <div class="card-body d-flex justify-content-between align-items-start">
                        <div>
                            <p class="mb-1">{{ $notification->data['message'] ?? 'Notification' }}</p>
                            @if(!empty($notification->data['reason']))
                                <p class="text-muted mb-1" style="font-size: 0.9rem;">
                                    <i class="bi bi-chat-left-text me-1"></i>{{ $notification->data['reason'] }}
                                </p>
                            @endif
                            <small class="text-muted">
                                <i class="bi bi-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}
                            </small>
                            @if(!empty($notification->data['status']))
                                <span class="status-badge status-{{ $notification->data['status'] }} ms-2">
                                    {{ ucfirst($notification->data['status']) }}
                                </span>
                            @endif
                        </div>
                        @if(!$notification->read_at)
                            <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-primary" title="Mark as read">
                                    <i class="bi bi-check"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="bi bi-bell-slash fs-1 text-muted"></i>
                    <p class="text-muted mt-3">No notifications yet.</p>
                    <a href="{{ route('client.welcome') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Back to Home
                    </a>
                </div>
            @endforelse

            <div class="d-flex justify-content-center mt-4">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>

@push('scripts')
    @include('layouts.partials.notification-scripts')
@endpush
@endsection
