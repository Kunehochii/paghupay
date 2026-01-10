@extends('layouts.admin')

@section('title', 'Edit Counselor')

@section('content')
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-secondary-dark">
            <i class="bi bi-pencil-square me-2"></i>Edit Counselor
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

    <!-- Edit Form -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-secondary-teal text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-person-badge me-2"></i>Counselor Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.counselors.update', $counselor->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Photo Upload -->
                        <div class="text-center mb-4">
                            <div class="position-relative d-inline-block">
                                <div id="photoPreview" 
                                     class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3"
                                     style="width: 150px; height: 150px; border: 3px dashed #dee2e6; overflow: hidden;">
                                    @if($counselor->counselorProfile?->picture_url)
                                        <div id="placeholderIcon" class="d-none">
                                            <i class="bi bi-camera fs-1 text-muted"></i>
                                        </div>
                                        <img id="previewImg" 
                                             src="{{ Storage::url($counselor->counselorProfile->picture_url) }}" 
                                             alt="{{ $counselor->name }}" 
                                             style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <div id="placeholderIcon">
                                            <i class="bi bi-camera fs-1 text-muted"></i>
                                        </div>
                                        <img id="previewImg" src="" alt="" class="d-none" style="width: 100%; height: 100%; object-fit: cover;">
                                    @endif
                                </div>
                                <label for="picture" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-upload me-1"></i>Change Photo
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
                                               value="{{ old('name', $counselor->name) }}"
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
                                               value="{{ old('email', $counselor->email) }}"
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
                                               value="{{ old('position', $counselor->counselorProfile?->position) }}"
                                               placeholder="e.g., Head Psychologist">
                                    </div>
                                    @error('position')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Device Status Info -->
                                <div class="alert {{ $counselor->counselorProfile?->device_token ? 'alert-success' : 'alert-secondary' }} mb-4">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            @if($counselor->counselorProfile?->device_token)
                                                <i class="bi bi-lock-fill me-2"></i>
                                                <strong>Device Bound</strong>
                                                <span class="text-muted ms-2">
                                                    Since {{ $counselor->counselorProfile->device_bound_at?->format('M d, Y h:i A') }}
                                                </span>
                                            @else
                                                <i class="bi bi-unlock me-2"></i>
                                                <strong>No Device Bound</strong>
                                                <span class="text-muted ms-2">Device will be bound on first login</span>
                                            @endif
                                        </div>
                                        @if($counselor->counselorProfile?->device_token)
                                            <button type="button" 
                                                    class="btn btn-warning btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#resetDeviceModal">
                                                <i class="bi bi-arrow-counterclockwise me-1"></i>Reset Device
                                            </button>
                                        @endif
                                    </div>
                                </div>

                        <!-- Submit -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('admin.counselors.index') }}" class="btn btn-outline-secondary me-md-2">
                                <i class="bi bi-x-lg me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-paghupay">
                                <i class="bi bi-check-lg me-1"></i>Update Counselor
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<!-- Reset Device Modal -->
@if($counselor->counselorProfile?->device_token)
<div class="modal fade" id="resetDeviceModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle me-2"></i>Reset Device Lock
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to reset the device lock for <strong>{{ $counselor->name }}</strong>?</p>
                <p class="text-muted mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    They will need to log in again to bind a new device.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.counselors.reset-device', $counselor->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset Device
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

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
                const placeholder = document.getElementById('placeholderIcon');
                if (placeholder) {
                    placeholder.classList.add('d-none');
                }
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
@endsection
