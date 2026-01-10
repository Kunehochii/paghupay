@extends('layouts.admin')

@section('title', 'Student Management')

@section('content')
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-secondary-dark">
            <i class="bi bi-people me-2"></i>Student Management
        </h1>
        <button type="button" class="btn btn-paghupay" data-bs-toggle="modal" data-bs-target="#addStudentModal">
            <i class="bi bi-plus-lg me-1"></i>Add New Student
        </button>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-x-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <!-- Total Students -->
        <div class="col-md-4">
            <div class="stat-card">
                <div>
                    <div class="card-label">Total Students</div>
                    <div class="card-value">{{ $clientCount }}</div>
                </div>
                <div class="card-arrow">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>

        <!-- Active Students -->
        <div class="col-md-4">
            <div class="stat-card">
                <div>
                    <div class="card-label">Active Students</div>
                    <div class="card-value">{{ $activeCount }}</div>
                </div>
                <div class="card-arrow">
                    <i class="bi bi-person-check"></i>
                </div>
            </div>
        </div>

        <!-- Pending Registration -->
        <div class="col-md-4">
            <div class="stat-card">
                <div>
                    <div class="card-label">Pending Registration</div>
                    <div class="card-value">{{ $pendingCount }}</div>
                </div>
                <div class="card-arrow">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-shield-lock fs-1 text-muted mb-3 d-block"></i>
            <h5 class="text-muted">Student Privacy Protected</h5>
            <p class="text-muted mb-4">
                For privacy reasons, student details are not displayed here.<br>
                Use the "Add New Student" button to invite students to the system.
            </p>
            <button type="button" class="btn btn-paghupay btn-lg" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                <i class="bi bi-plus-lg me-2"></i>Add New Student
            </button>
        </div>
    </div>

<!-- Add Student Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-secondary-teal text-white">
                <h5 class="modal-title" id="addStudentModalLabel">
                    <i class="bi bi-person-plus me-2"></i>Add New Student
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addStudentForm" method="POST" action="{{ route('admin.clients.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="email" class="form-label">Student Email Address <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   placeholder="student@tupv.edu.ph"
                                   required>
                        </div>
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>Only @tupv.edu.ph email addresses are accepted.
                        </div>
                        <div id="emailError" class="invalid-feedback"></div>
                    </div>

                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>What happens next:</strong>
                        <ul class="mb-0 mt-2">
                            <li>A temporary password will be generated</li>
                            <li>An invitation email will be sent to the student</li>
                            <li>The student can register using the temporary password</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-paghupay" id="submitBtn">
                        <i class="bi bi-send me-1"></i>Send Invitation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
                </div>
                <h4 class="mb-3">Invitation Sent!</h4>
                <p class="text-muted mb-4" id="successMessage">
                    An email with login credentials has been sent.
                </p>
                <button type="button" class="btn btn-paghupay" data-bs-dismiss="modal" onclick="location.reload()">
                    Done
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('addStudentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const submitBtn = document.getElementById('submitBtn');
    const emailInput = document.getElementById('email');
    const emailError = document.getElementById('emailError');
    
    // Validate email domain
    const email = emailInput.value.toLowerCase();
    // Allow @tupv.edu.ph (production) and @gmail.com (testing)
    if (!email.endsWith('@tupv.edu.ph') && !email.endsWith('@gmail.com')) {
        emailInput.classList.add('is-invalid');
        emailError.textContent = 'Only @tupv.edu.ph email addresses are allowed.';
        return;
    }
    
    // Clear validation state
    emailInput.classList.remove('is-invalid');
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Sending...';
    
    // Submit via AJAX
    fetch(form.action, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ email: email })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Hide add modal
            bootstrap.Modal.getInstance(document.getElementById('addStudentModal')).hide();
            
            // Update success message and show success modal
            document.getElementById('successMessage').textContent = 
                `An email with login credentials has been sent to ${data.email}.`;
            new bootstrap.Modal(document.getElementById('successModal')).show();
        } else {
            // Show error
            emailInput.classList.add('is-invalid');
            emailError.textContent = data.message || 'An error occurred. Please try again.';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        emailInput.classList.add('is-invalid');
        emailError.textContent = 'An error occurred. Please try again.';
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="bi bi-send me-1"></i>Send Invitation';
    });
});

// Clear validation on input
document.getElementById('email').addEventListener('input', function() {
    this.classList.remove('is-invalid');
});
</script>
@endpush
@endsection
