@extends('layouts.app')

@section('title', 'Welcome')

@push('styles')
    <style>
        .welcome-hero {
            position: relative;
            min-height: 400px;
            background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)),
                url('{{ asset('images/welcome_image.png') }}');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .welcome-title {
            font-family: 'Georgia', serif;
            font-style: italic;
            font-weight: 700;
            color: #1a1a1a;
        }

        .welcome-subtitle {
            color: #5f9ea0;
            font-weight: 500;
        }

        .btn-start {
            background-color: #a7f0ba;
            border: none;
            color: #1a1a1a;
            font-weight: 600;
            padding: 12px 80px;
            border-radius: 25px;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .btn-start:hover {
            background-color: #8fd4a0;
            color: #1a1a1a;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(167, 240, 186, 0.4);
        }

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

        <!-- Welcome Section -->
        <div class="container text-center py-4">
            <h1 class="welcome-title display-4 mb-2">Welcome, {{ Auth::user()->nickname ?? 'Student' }}</h1>
            <p class="welcome-subtitle fs-5">Book your appointment today!</p>
        </div>

        <!-- Hero Image -->
        <div class="welcome-hero mb-5">
        </div>

        <!-- Start Button -->
        <div class="container text-center pb-5">
            <a href="{{ route('booking.index') }}" class="btn btn-start">
                START
            </a>
        </div>
    </div>
@endsection
