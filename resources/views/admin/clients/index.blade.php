@extends('layouts.admin')

@section('title', 'Student Management')

@section('content')
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-secondary-dark">
            <i class="bi bi-people me-2"></i>Student Management
        </h1>
        <div>
            <button type="button" class="btn btn-outline-warning me-2" data-bs-toggle="modal" data-bs-target="#deactivateStudentModal">
                <i class="bi bi-person-slash me-1"></i>Manage Status
            </button>
            <button type="button" class="btn btn-paghupay" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                <i class="bi bi-plus-lg me-1"></i>Add New Student
            </button>
        </div>
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
                        <label for="tupv_id" class="form-label">TUPV ID <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                            <input type="text" 
                                   class="form-control" 
                                   id="tupv_id" 
                                   name="tupv_id" 
                                   placeholder="TUPV-XX-XXXX"
                                   pattern="TUPV-\d{2}-\d{4}"
                                   required>
                        </div>
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>Format: TUPV-XX-XXXX (e.g., TUPV-24-0001)
                        </div>
                        <div id="tupvIdError" class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Student Email Address <small class="text-muted">(Optional)</small></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   placeholder="student@tupv.edu.ph">
                        </div>
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>If provided, an invitation email will be sent automatically.
                        </div>
                        <div id="emailError" class="invalid-feedback"></div>
                    </div>

                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>What happens next:</strong>
                        <ul class="mb-0 mt-2">
                            <li>The student's default password will be their TUPV ID</li>
                            <li>If email provided: invitation email will be sent</li>
                            <li>The student can login using their TUPV ID as both username and password</li>
                            <li>They will be required to change their password on first login</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-paghupay" id="submitBtn">
                        <i class="bi bi-person-plus me-1"></i>Create Student
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
                <h4 class="mb-3">Student Created!</h4>
                <p class="text-muted mb-2" id="successMessage">
                    Student account has been created successfully.
                </p>
                <div id="tempPasswordDisplay" class="d-none alert alert-warning text-start mb-4">
                    <strong><i class="bi bi-key me-1"></i>Default Password (TUPV ID):</strong>
                    <code id="tempPasswordValue" class="fs-5 d-block mt-2"></code>
                    <small class="text-muted d-block mt-2">The student's default password is their TUPV ID.</small>
                </div>
                <button type="button" class="btn btn-paghupay" data-bs-dismiss="modal" onclick="location.reload()">
                    Done
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Deactivate/Reactivate Student Modal (Search) -->
<div class="modal fade" id="deactivateStudentModal" tabindex="-1" aria-labelledby="deactivateStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="deactivateStudentModalLabel">
                    <i class="bi bi-person-slash me-2"></i>Manage Student Status
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Search Section -->
                <div class="mb-4">
                    <label for="studentSearch" class="form-label">Search by TUPV ID</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text"
                               class="form-control"
                               id="studentSearch"
                               placeholder="Enter TUPV ID (e.g., TUPV-24-0001)"
                               autocomplete="off">
                        <button type="button" class="btn btn-outline-secondary" id="searchBtn">
                            Search
                        </button>
                    </div>
                    <div class="form-text">
                        <i class="bi bi-info-circle me-1"></i>Search by TUPV ID only (minimum 4 characters, e.g., "TUPV" or "24-00")
                    </div>
                </div>

                <!-- Search Results -->
                <div id="searchResults" class="d-none">
                    <h6 class="mb-3">Search Results</h6>
                    <div id="resultsContainer">
                        <!-- Results will be populated here -->
                    </div>
                </div>

                <!-- No Results Message -->
                <div id="noResults" class="d-none text-center py-4">
                    <i class="bi bi-search fs-1 text-muted"></i>
                    <p class="text-muted mt-2 mb-0">No students found matching your search.</p>
                </div>

                <!-- Loading State -->
                <div id="searchLoading" class="d-none text-center py-4">
                    <div class="spinner-border text-secondary" role="status">
                        <span class="visually-hidden">Searching...</span>
                    </div>
                    <p class="text-muted mt-2 mb-0">Searching...</p>
                </div>

                <!-- Info -->
                <div class="alert alert-info mt-4 mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Note:</strong> Deactivating a student will prevent them from logging in. Their records (appointments, case logs) will be preserved. You can reactivate the account at any time.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Confirm Deactivate Modal -->
<div class="modal fade" id="confirmDeactivateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle me-2"></i>Confirm Deactivation
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to deactivate this student?</p>
                <div class="card bg-light mb-3">
                    <div class="card-body">
                        <p class="mb-1"><strong>TUPV ID:</strong> <span id="confirmTupvId"></span></p>
                        <p class="mb-1"><strong>Name:</strong> <span id="confirmName"></span></p>
                        <p class="mb-1"><strong>Email:</strong> <span id="confirmEmail"></span></p>
                    </div>
                </div>
                <div class="alert alert-warning mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    The student will not be able to log in while deactivated. You can reactivate the account later.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deactivateForm" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-warning" id="confirmDeactivateBtn">
                        <i class="bi bi-person-slash me-1"></i>Deactivate
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Status Change Success Modal -->
<div class="modal fade" id="statusSuccessModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
                </div>
                <h4 class="mb-3" id="statusSuccessTitle">Status Updated</h4>
                <p class="text-muted mb-2" id="statusSuccessMessage">
                    Student status has been updated successfully.
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
    const tupvIdInput = document.getElementById('tupv_id');
    const emailInput = document.getElementById('email');
    const tupvIdError = document.getElementById('tupvIdError');
    const emailError = document.getElementById('emailError');
    
    // Validate TUPV ID format
    const tupvId = tupvIdInput.value.toUpperCase();
    const tupvIdPattern = /^TUPV-\d{2}-\d{4}$/;
    if (!tupvIdPattern.test(tupvId)) {
        tupvIdInput.classList.add('is-invalid');
        tupvIdError.textContent = 'TUPV ID must be in format TUPV-XX-XXXX (e.g., TUPV-24-0001)';
        return;
    }
    
    // Validate email domain if provided
    const email = emailInput.value.toLowerCase().trim();
    if (email && !email.endsWith('@tupv.edu.ph') && !email.endsWith('@gmail.com')) {
        emailInput.classList.add('is-invalid');
        emailError.textContent = 'Only @tupv.edu.ph email addresses are allowed.';
        return;
    }
    
    // Clear validation state
    tupvIdInput.classList.remove('is-invalid');
    emailInput.classList.remove('is-invalid');
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Creating...';
    
    // Submit via AJAX
    fetch(form.action, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ 
            tupv_id: tupvId,
            email: email || null 
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Hide add modal
            bootstrap.Modal.getInstance(document.getElementById('addStudentModal')).hide();
            
            // Update success message
            let message = `Student account created for TUPV ID: ${data.tupv_id}`;
            if (data.email_sent) {
                message += `. Invitation email sent to ${data.email}.`;
            }
            document.getElementById('successMessage').textContent = message;
            
            // Show temp password if no email was provided
            const tempPasswordDisplay = document.getElementById('tempPasswordDisplay');
            if (data.temp_password) {
                document.getElementById('tempPasswordValue').textContent = data.temp_password;
                tempPasswordDisplay.classList.remove('d-none');
            } else {
                tempPasswordDisplay.classList.add('d-none');
            }
            
            new bootstrap.Modal(document.getElementById('successModal')).show();
        } else {
            // Show error
            if (data.errors && data.errors.tupv_id) {
                tupvIdInput.classList.add('is-invalid');
                tupvIdError.textContent = data.errors.tupv_id[0];
            }
            if (data.errors && data.errors.email) {
                emailInput.classList.add('is-invalid');
                emailError.textContent = data.errors.email[0];
            }
            if (!data.errors) {
                tupvIdInput.classList.add('is-invalid');
                tupvIdError.textContent = data.message || 'An error occurred. Please try again.';
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        tupvIdInput.classList.add('is-invalid');
        tupvIdError.textContent = 'An error occurred. Please try again.';
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="bi bi-person-plus me-1"></i>Create Student';
    });
});

// Clear validation on input
document.getElementById('tupv_id').addEventListener('input', function() {
    this.classList.remove('is-invalid');
});
document.getElementById('email').addEventListener('input', function() {
    this.classList.remove('is-invalid');
});

// =====================================================
// DEACTIVATE/REACTIVATE STUDENT FUNCTIONALITY
// =====================================================

const studentSearch = document.getElementById('studentSearch');
const searchBtn = document.getElementById('searchBtn');
const searchResults = document.getElementById('searchResults');
const resultsContainer = document.getElementById('resultsContainer');
const noResults = document.getElementById('noResults');
const searchLoading = document.getElementById('searchLoading');

// Search function
function searchStudents() {
    const query = studentSearch.value.trim().toUpperCase();

    if (query.length < 4) {
        searchResults.classList.add('d-none');
        noResults.classList.add('d-none');
        return;
    }

    // Show loading
    searchResults.classList.add('d-none');
    noResults.classList.add('d-none');
    searchLoading.classList.remove('d-none');

    fetch(`{{ route('admin.clients.search', [], false) }}?q=${encodeURIComponent(query)}`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        searchLoading.classList.add('d-none');

        if (data.success && data.results.length > 0) {
            displayResults(data.results);
            searchResults.classList.remove('d-none');
            noResults.classList.add('d-none');
        } else {
            searchResults.classList.add('d-none');
            noResults.classList.remove('d-none');
        }
    })
    .catch(error => {
        console.error('Search error:', error);
        searchLoading.classList.add('d-none');
        noResults.classList.remove('d-none');
    });
}

// Display search results
function displayResults(results) {
    resultsContainer.innerHTML = results.map(student => {
        const isDeactivated = student.deactivated_at !== null;
        const statusBadge = isDeactivated
            ? '<span class="badge bg-danger ms-2">Deactivated</span>'
            : (student.is_active ? '<span class="badge bg-success ms-2">Active</span>' : '<span class="badge bg-secondary ms-2">Pending</span>');
        const actionBtn = isDeactivated
            ? `<button type="button" class="btn btn-outline-success btn-sm"
                    onclick="toggleStatus(${student.id}, '${student.tupv_id}', '${student.name.replace(/'/g, "\\'")}', '${student.email || 'N/A'}', 'reactivate')">
                    <i class="bi bi-person-check"></i> Reactivate
                </button>`
            : `<button type="button" class="btn btn-outline-warning btn-sm"
                    onclick="toggleStatus(${student.id}, '${student.tupv_id}', '${student.name.replace(/'/g, "\\'")}', '${student.email || 'N/A'}', 'deactivate')">
                    <i class="bi bi-person-slash"></i> Deactivate
                </button>`;

        return `
        <div class="card mb-2">
            <div class="card-body d-flex justify-content-between align-items-center py-2">
                <div>
                    <strong>${student.tupv_id}</strong>
                    ${statusBadge}
                    <br>
                    <small class="text-muted">
                        ${student.name !== 'Pending Registration' ? student.name : '(No name yet)'}
                        ${student.email ? ' &bull; ' + student.email : ''}
                    </small>
                    <br>
                    <small class="text-muted">
                        <i class="bi bi-calendar me-1"></i>${student.appointments_count} appointments,
                        <i class="bi bi-journal-text me-1"></i>${student.case_logs_count} case logs
                    </small>
                </div>
                ${actionBtn}
            </div>
        </div>`;
    }).join('');
}

// Search on button click
searchBtn.addEventListener('click', searchStudents);

// Search on Enter key
studentSearch.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        searchStudents();
    }
});

// Clear results when modal closes
document.getElementById('deactivateStudentModal').addEventListener('hidden.bs.modal', function() {
    studentSearch.value = '';
    searchResults.classList.add('d-none');
    noResults.classList.add('d-none');
    searchLoading.classList.add('d-none');
});

let currentAction = 'deactivate';

// Toggle status (deactivate or reactivate)
function toggleStatus(id, tupvId, name, email, action) {
    currentAction = action;

    if (action === 'reactivate') {
        // Reactivate directly via AJAX (no confirmation needed)
        performStatusChange(id, action);
        return;
    }

    // Show confirm modal for deactivation
    bootstrap.Modal.getInstance(document.getElementById('deactivateStudentModal')).hide();

    document.getElementById('confirmTupvId').textContent = tupvId;
    document.getElementById('confirmName').textContent = name !== 'Pending Registration' ? name : '(No name yet)';
    document.getElementById('confirmEmail').textContent = email;

    document.getElementById('deactivateForm').action = `/admin/clients/${id}/deactivate`;

    new bootstrap.Modal(document.getElementById('confirmDeactivateModal')).show();
}

// Handle deactivate form submission via AJAX
document.getElementById('deactivateForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = this;
    const btn = document.getElementById('confirmDeactivateBtn');

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Deactivating...';

    fetch(form.action, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        bootstrap.Modal.getInstance(document.getElementById('confirmDeactivateModal')).hide();

        if (data.success) {
            document.getElementById('statusSuccessTitle').textContent = 'Student Deactivated';
            document.getElementById('statusSuccessMessage').textContent = data.message;
            new bootstrap.Modal(document.getElementById('statusSuccessModal')).show();
        } else {
            alert(data.message || 'An error occurred. Please try again.');
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
        location.reload();
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-person-slash me-1"></i>Deactivate';
    });
});

// Perform status change (used for reactivation)
function performStatusChange(id, action) {
    fetch(`/admin/clients/${id}/${action}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('statusSuccessTitle').textContent = 'Student Reactivated';
            document.getElementById('statusSuccessMessage').textContent = data.message;
            bootstrap.Modal.getInstance(document.getElementById('deactivateStudentModal'))?.hide();
            new bootstrap.Modal(document.getElementById('statusSuccessModal')).show();
        } else {
            alert(data.message || 'An error occurred.');
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
        location.reload();
    });
}
</script>
@endpush
@endsection
