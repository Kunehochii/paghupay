@extends('layouts.app')

@section('title', 'About Us')

@push('styles')
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
            background-color: #333;
            color: white;
            font-weight: 500;
            margin: 0 5px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .nav-custom .nav-link-about:hover {
            background-color: #555;
            border-color: #555;
            color: white;
        }

        .about-container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 20px 60px;
        }

        .about-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .about-logo {
            width: 80px;
            height: 80px;
            background-color: rgba(61, 159, 155, 0.1);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .about-logo i {
            font-size: 2.5rem;
            color: #3d9f9b;
        }

        .about-title {
            color: #3d9f9b;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .about-subtitle {
            color: #6c757d;
            font-size: 1rem;
        }

        .card-custom {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            margin-bottom: 25px;
        }

        .card-custom .card-header {
            border-radius: 12px 12px 0 0;
            padding: 16px 20px;
        }

        .card-custom .card-body {
            padding: 24px;
        }

        .vision-box {
            padding: 24px;
            background: rgba(61, 159, 155, 0.08);
            border-radius: 12px;
            height: 100%;
        }

        .vision-box h5 {
            color: #3d9f9b;
            margin-bottom: 12px;
        }

        .mission-box {
            padding: 24px;
            background: rgba(13, 110, 253, 0.06);
            border-radius: 12px;
            height: 100%;
        }

        .mission-box h5 {
            color: #0d6efd;
            margin-bottom: 12px;
        }

        .service-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .service-icon {
            background: rgba(61, 159, 155, 0.1);
            border-radius: 8px;
            padding: 8px;
            margin-right: 12px;
            flex-shrink: 0;
        }

        .service-icon i {
            color: #3d9f9b;
        }

        .contact-card .card-header {
            background: #3d9f9b;
            color: white;
        }

        .system-info-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .system-info-item:last-child {
            border-bottom: none;
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

        <div class="about-container">
            <!-- Header -->
            <div class="about-header">
                <div class="about-logo">
                    <i class="bi bi-heart-pulse"></i>
                </div>
                <h2 class="about-title">Paghupay</h2>
                <p class="about-subtitle">TUP-V Guidance & Counseling System</p>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    {{-- Mission & Vision --}}
                    <div class="card-custom">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-4 mb-md-0">
                                    <div class="vision-box">
                                        <h5>
                                            <i class="bi bi-eye me-2"></i>Vision
                                        </h5>
                                        <p class="mb-0">
                                            To be the leading guidance and counseling office that provides holistic and
                                            accessible mental health support to all TUP-V students, fostering their
                                            personal growth, academic success, and overall well-being.
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mission-box">
                                        <h5>
                                            <i class="bi bi-bullseye me-2"></i>Mission
                                        </h5>
                                        <p class="mb-0">
                                            To deliver quality guidance and counseling services that empower students
                                            to navigate personal challenges, make informed decisions, and achieve their
                                            full potential through a supportive and confidential environment.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Services --}}
                    <div class="card-custom">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0">
                                <i class="bi bi-list-check text-success me-2"></i>Our Services
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="service-item">
                                        <div class="service-icon">
                                            <i class="bi bi-chat-heart"></i>
                                        </div>
                                        <div>
                                            <strong>Individual Counseling</strong>
                                            <p class="text-muted small mb-0">One-on-one sessions for personal concerns</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="service-item">
                                        <div class="service-icon">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <div>
                                            <strong>Group Counseling</strong>
                                            <p class="text-muted small mb-0">Peer support and group therapy sessions</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="service-item">
                                        <div class="service-icon">
                                            <i class="bi bi-mortarboard"></i>
                                        </div>
                                        <div>
                                            <strong>Academic Counseling</strong>
                                            <p class="text-muted small mb-0">Study skills and academic planning</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="service-item">
                                        <div class="service-icon">
                                            <i class="bi bi-briefcase"></i>
                                        </div>
                                        <div>
                                            <strong>Career Guidance</strong>
                                            <p class="text-muted small mb-0">Career planning and job readiness</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="service-item">
                                        <div class="service-icon">
                                            <i class="bi bi-exclamation-triangle"></i>
                                        </div>
                                        <div>
                                            <strong>Crisis Intervention</strong>
                                            <p class="text-muted small mb-0">Immediate support for urgent situations</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="service-item">
                                        <div class="service-icon">
                                            <i class="bi bi-clipboard-check"></i>
                                        </div>
                                        <div>
                                            <strong>Psychological Testing</strong>
                                            <p class="text-muted small mb-0">Assessment and evaluation services</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Confidentiality --}}
                    <div class="card-custom">
                        <div class="card-header bg-warning bg-opacity-10 py-3">
                            <h5 class="mb-0 text-dark">
                                <i class="bi bi-shield-lock text-warning me-2"></i>Confidentiality Notice
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-3">
                                All information shared during counseling sessions is treated with the utmost confidentiality
                                in accordance with the <strong>Data Privacy Act of 2012 (RA 10173)</strong>.
                            </p>
                            <ul class="mb-0">
                                <li>Session records are encrypted and securely stored</li>
                                <li>Access to case logs is restricted to authorized counselors only</li>
                                <li>Information is only disclosed with written consent, except in cases where there is
                                    imminent danger to the student or others</li>
                                <li>All counselors adhere to the Code of Ethics for Guidance Counselors</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    {{-- Contact Info --}}
                    <div class="card-custom contact-card">
                        <div class="card-header py-3">
                            <h5 class="mb-0">
                                <i class="bi bi-geo-alt me-2"></i>Contact Us
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong class="d-block mb-1">Location</strong>
                                <span class="text-muted">
                                    Guidance Office, 2nd Floor<br>
                                    TUP-V Administration Building<br>
                                    Talisay City, Negros Occidental
                                </span>
                            </div>
                            <div class="mb-3">
                                <strong class="d-block mb-1">Office Hours</strong>
                                <span class="text-muted">
                                    Monday - Friday<br>
                                    8:00 AM - 5:00 PM
                                </span>
                            </div>
                            <div class="mb-3">
                                <strong class="d-block mb-1">Email</strong>
                                <a href="mailto:guidance@tupv.edu.ph" class="text-success">
                                    guidance@tupv.edu.ph
                                </a>
                            </div>
                            <div>
                                <strong class="d-block mb-1">Emergency Hotline</strong>
                                <span class="text-danger">
                                    <i class="bi bi-telephone-fill me-1"></i>
                                    (034) 123-4567
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- System Info --}}
                    <div class="card-custom">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0">
                                <i class="bi bi-info-circle text-primary me-2"></i>System Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="system-info-item">
                                <span class="text-muted">Version</span>
                                <strong>1.0.0</strong>
                            </div>
                            <div class="system-info-item">
                                <span class="text-muted">Framework</span>
                                <strong>Laravel 12.x</strong>
                            </div>
                            <div class="system-info-item">
                                <span class="text-muted">Database</span>
                                <strong>PostgreSQL</strong>
                            </div>
                            <div class="system-info-item">
                                <span class="text-muted">Encryption</span>
                                <strong>AES-256-CBC</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
