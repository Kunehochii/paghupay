@extends('layouts.app')

@section('title', 'Counselor Management')

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
                    <i class="bi bi-person-badge me-2 text-success"></i>Counselor Management
                </h1>
                <a href="{{ route('admin.counselors.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-lg me-1"></i>Add New Counselor
                </a>
            </div>

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-x-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Stats Card -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm bg-success text-white">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-person-badge fs-1 mb-2 d-block"></i>
                            <h1 class="display-3 fw-bold mb-1">{{ $counselors->total() }}</h1>
                            <p class="mb-0 opacity-75">Total Counselors</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Counselors Table -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-list-ul me-2 text-muted"></i>All Counselors
                    </h5>
                </div>
                <div class="card-body">
                    @if($counselors->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 60px;"></th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Position</th>
                                        <th>Device Status</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($counselors as $counselor)
                                        <tr>
                                            <td>
                                                @if($counselor->counselorProfile?->picture_url)
                                                    <img src="{{ Storage::url($counselor->counselorProfile->picture_url) }}" 
                                                         alt="{{ $counselor->name }}"
                                                         class="rounded-circle"
                                                         style="width: 45px; height: 45px; object-fit: cover;">
                                                @else
                                                    <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center"
                                                         style="width: 45px; height: 45px;">
                                                        <i class="bi bi-person-fill text-success"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $counselor->name }}</strong>
                                            </td>
                                            <td>{{ $counselor->email }}</td>
                                            <td>{{ $counselor->counselorProfile?->position ?? 'N/A' }}</td>
                                            <td>
                                                @if($counselor->counselorProfile?->device_token)
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-lock-fill me-1"></i>Device Bound
                                                    </span>
                                                    <small class="d-block text-muted">
                                                        Since: {{ $counselor->counselorProfile->device_bound_at?->format('M d, Y') }}
                                                    </small>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        <i class="bi bi-unlock me-1"></i>No Device Bound
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group" role="group">
                                                    @if($counselor->counselorProfile?->device_token)
                                                        <button type="button" 
                                                                class="btn btn-sm btn-warning"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#resetModal{{ $counselor->id }}">
                                                            <i class="bi bi-arrow-counterclockwise"></i>
                                                        </button>
                                                    @endif
                                                    <a href="{{ route('admin.counselors.edit', $counselor->id) }}" 
                                                       class="btn btn-sm btn-outline-secondary">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-danger"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#deleteModal{{ $counselor->id }}">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Reset Device Modal -->
                                        @if($counselor->counselorProfile?->device_token)
                                        <div class="modal fade" id="resetModal{{ $counselor->id }}" tabindex="-1">
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

                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal{{ $counselor->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger text-white">
                                                        <h5 class="modal-title">
                                                            <i class="bi bi-exclamation-triangle me-2"></i>Delete Counselor
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to delete <strong>{{ $counselor->name }}</strong>?</p>
                                                        <p class="text-danger mb-0">
                                                            <i class="bi bi-exclamation-circle me-1"></i>
                                                            This action cannot be undone. All associated data will be permanently removed.
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <form action="{{ route('admin.counselors.destroy', $counselor->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">
                                                                <i class="bi bi-trash me-1"></i>Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($counselors->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $counselors->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-person-badge fs-1 mb-3 d-block"></i>
                            <h5>No Counselors Yet</h5>
                            <p class="mb-3">Get started by adding your first counselor.</p>
                            <a href="{{ route('admin.counselors.create') }}" class="btn btn-success">
                                <i class="bi bi-plus-lg me-1"></i>Add New Counselor
                            </a>
                        </div>
                    @endif
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
@endsection
