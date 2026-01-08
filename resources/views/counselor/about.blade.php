@extends('layouts.counselor')

@section('title', 'About Us')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-1">About Us</h4>
        <p class="text-muted mb-0">TUP-V Guidance and Counseling Office</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        {{-- Mission & Vision --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px;">
                        <i class="bi bi-heart-pulse text-success" style="font-size: 2.5rem;"></i>
                    </div>
                    <h3 class="text-success">Paghupay</h3>
                    <p class="text-muted">TUP-V Guidance & Counseling System</p>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="p-4 bg-success bg-opacity-10 rounded h-100">
                            <h5 class="text-success mb-3">
                                <i class="bi bi-eye me-2"></i>Vision
                            </h5>
                            <p class="mb-0">
                                To be the leading guidance and counseling office that provides holistic and 
                                accessible mental health support to all TUP-V students, fostering their 
                                personal growth, academic success, and overall well-being.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="p-4 bg-primary bg-opacity-10 rounded h-100">
                            <h5 class="text-primary mb-3">
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
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="bi bi-list-check text-success me-2"></i>Our Services
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="bg-success bg-opacity-10 rounded p-2 me-3">
                                <i class="bi bi-chat-heart text-success"></i>
                            </div>
                            <div>
                                <strong>Individual Counseling</strong>
                                <p class="text-muted small mb-0">One-on-one sessions for personal concerns</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="bg-success bg-opacity-10 rounded p-2 me-3">
                                <i class="bi bi-people text-success"></i>
                            </div>
                            <div>
                                <strong>Group Counseling</strong>
                                <p class="text-muted small mb-0">Peer support and group therapy sessions</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="bg-success bg-opacity-10 rounded p-2 me-3">
                                <i class="bi bi-mortarboard text-success"></i>
                            </div>
                            <div>
                                <strong>Academic Counseling</strong>
                                <p class="text-muted small mb-0">Study skills and academic planning</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="bg-success bg-opacity-10 rounded p-2 me-3">
                                <i class="bi bi-briefcase text-success"></i>
                            </div>
                            <div>
                                <strong>Career Guidance</strong>
                                <p class="text-muted small mb-0">Career planning and job readiness</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="bg-success bg-opacity-10 rounded p-2 me-3">
                                <i class="bi bi-exclamation-triangle text-success"></i>
                            </div>
                            <div>
                                <strong>Crisis Intervention</strong>
                                <p class="text-muted small mb-0">Immediate support for urgent situations</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="bg-success bg-opacity-10 rounded p-2 me-3">
                                <i class="bi bi-clipboard-check text-success"></i>
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
        <div class="card border-0 shadow-sm">
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
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-success text-white py-3">
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
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle text-primary me-2"></i>System Information
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Version</span>
                        <strong>1.0.0</strong>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Framework</span>
                        <strong>Laravel 12.x</strong>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Database</span>
                        <strong>PostgreSQL</strong>
                    </li>
                    <li class="d-flex justify-content-between py-2">
                        <span class="text-muted">Encryption</span>
                        <strong>AES-256-CBC</strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
