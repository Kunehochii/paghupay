@extends('layouts.app')

@section('title', 'Add New Counselor')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse" style="min-height: calc(100vh - 56px);">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2 me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active text-white" href="{{ route('admin.counselors.index') }}">
                            <i class="bi bi-person-badge me-2"></i>Counselors
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="{{ route('admin.clients.index') }}">
                            <i class="bi bi-people me-2"></i>Students
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">
                    <i class="bi bi-person-plus me-2 text-success"></i>Add New Counselor
                </h1>
                <a href="{{ route('admin.counselors.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back to List
                </a>
            </div>

            <!-- Flash Messages -->
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Create Form -->
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-person-badge me-2"></i>Counselor Information
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('admin.counselors.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <!-- Photo Upload -->
                                <div class="text-center mb-4">
                                    <div class="position-relative d-inline-block">
                                        <div id="photoPreview" 
                                             class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3"
                                             style="width: 150px; height: 150px; border: 3px dashed #dee2e6; overflow: hidden;">
                                            <div id="placeholderIcon">
                                                <i class="bi bi-camera fs-1 text-muted"></i>
                                            </div>
                                            <img id="previewImg" src="" alt="" class="d-none" style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                        <label for="picture" class="btn btn-outline-success btn-sm">
                                            <i class="bi bi-upload me-1"></i>Upload Photo
                                        </label>
                                        <input type="file" 
                                               id="picture" 
                                               name="picture" 
                                               class="d-none" 
                                               accept="image/jpeg,image/png,image/jpg">
                                    </div>
                                    <p class="text-muted small mb-0">Optional. Max 2MB. JPG or PNG.</p>
                                </div>

                                <hr class="my-4">

                                <!-- Name -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                        <input type="text" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name') }}"
                                               placeholder="e.g., Dr. Maria Santos"
                                               required>
                                    </div>
                                    @error('name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                        <input type="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email') }}"
                                               placeholder="e.g., counselor@tupv.edu.ph"
                                               required>
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Position -->
                                <div class="mb-4">
                                    <label for="position" class="form-label">Position</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-briefcase"></i></span>
                                        <input type="text" 
                                               class="form-control @error('position') is-invalid @enderror" 
                                               id="position" 
                                               name="position" 
                                               value="{{ old('position') }}"
                                               placeholder="e.g., Head Psychologist">
                                    </div>
                                    @error('position')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Info Box -->
                                <div class="alert alert-info mb-4">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Note:</strong> A temporary password will be generated automatically.
                                    The counselor will need to use this password for their first login.
                                </div>

                                <!-- Submit -->
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="{{ route('admin.counselors.index') }}" class="btn btn-outline-secondary me-md-2">
                                        <i class="bi bi-x-lg me-1"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-check-lg me-1"></i>Create Counselor
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

@push('styles')
<style>
    .sidebar {
        position: fixed;
        top: 56px;
        bottom: 0;
        left: 0;
        z-index: 100;
        padding: 0;
        overflow-x: hidden;
        overflow-y: auto;
    }
    @media (max-width: 767.98px) {
        .sidebar {
            position: static;
            min-height: auto !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Photo preview
    document.getElementById('picture').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('previewImg').classList.remove('d-none');
                document.getElementById('placeholderIcon').classList.add('d-none');
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
@endsection
