@extends('layouts.counselor')

@section('title', 'Case Logs')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">Case Logs</h4>
                <p class="text-muted mb-0">Manage your counseling session records</p>
            </div>
            <a href="{{ route('counselor.case-logs.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle me-1"></i> New Case Log
            </a>
        </div>
    </div>
</div>

{{-- Stats Row --}}
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm bg-success bg-opacity-10">
            <div class="card-body text-center py-4">
                <i class="bi bi-journal-text text-success" style="font-size: 2rem;"></i>
                <h3 class="mt-2 mb-0 text-success">{{ $caseLogs->total() }}</h3>
                <small class="text-muted">Total Case Logs</small>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm bg-info bg-opacity-10">
            <div class="card-body text-center py-4">
                <i class="bi bi-calendar-check text-info" style="font-size: 2rem;"></i>
                <h3 class="mt-2 mb-0 text-info">{{ $thisMonthCount }}</h3>
                <small class="text-muted">This Month</small>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm bg-primary bg-opacity-10">
            <div class="card-body text-center py-4">
                <i class="bi bi-clock-history text-primary" style="font-size: 2rem;"></i>
                <h3 class="mt-2 mb-0 text-primary">{{ $avgDuration }}</h3>
                <small class="text-muted">Avg. Session Duration</small>
            </div>
        </div>
    </div>
</div>

{{-- Case Logs Table --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-archive text-success me-2"></i>
                Session Records
            </h5>
            <div class="d-flex gap-2">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" class="form-control" placeholder="Search case logs..." id="searchInput">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        @if($caseLogs->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-journal-x text-muted" style="font-size: 4rem;"></i>
                <h4 class="text-muted mt-3">No Case Logs Yet</h4>
                <p class="text-muted">Case logs will appear here after you complete counseling sessions.</p>
                <a href="{{ route('counselor.case-logs.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Create New Case Log
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Created Date</th>
                            <th>Case Log ID</th>
                            <th>Log #</th>
                            <th>Student</th>
                            <th>Duration</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($caseLogs as $index => $caseLog)
                        <tr>
                            <td>
                                <div>
                                    <strong>{{ $caseLog->created_at->format('M j, Y') }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $caseLog->created_at->format('g:i A') }}</small>
                                </div>
                            </td>
                            <td>
                                <code class="text-primary">{{ $caseLog->case_log_id }}</code>
                            </td>
                            <td>
                                <span class="badge bg-secondary">
                                    #{{ $caseLogs->total() - (($caseLogs->currentPage() - 1) * $caseLogs->perPage()) - $index }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2" 
                                         style="width: 36px; height: 36px; font-size: 14px;">
                                        {{ strtoupper(substr($caseLog->client->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <strong>{{ $caseLog->client->name }}</strong>
                                        @if($caseLog->client->course_year_section)
                                        <br><small class="text-muted">{{ $caseLog->client->course_year_section }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($caseLog->session_duration)
                                    <span class="badge bg-info">{{ $caseLog->formatted_duration }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1 justify-content-end">
                                    <a href="{{ route('counselor.case-logs.show', $caseLog->id) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('counselor.case-logs.edit', $caseLog->id) }}" 
                                       class="btn btn-sm btn-outline-success" 
                                       title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="{{ route('counselor.case-logs.export-pdf', $caseLog->id) }}" 
                                       class="btn btn-sm btn-outline-secondary" 
                                       title="Export PDF"
                                       target="_blank">
                                        <i class="bi bi-file-pdf"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            title="Delete"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal"
                                            data-caselog-id="{{ $caseLog->id }}"
                                            data-caselog-name="{{ $caseLog->case_log_id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center py-3">
                {{ $caseLogs->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-trash"></i> Delete Case Log</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Are you sure you want to delete case log <strong id="deleteCaseLogId"></strong>?</p>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        This action cannot be undone. All associated treatment goals and activities will also be deleted.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Case Log</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Delete modal handler
    const deleteModal = document.getElementById('deleteModal');
    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const caselogId = button.getAttribute('data-caselog-id');
            const caselogName = button.getAttribute('data-caselog-name');
            
            document.getElementById('deleteCaseLogId').textContent = caselogName;
            document.getElementById('deleteForm').action = `/counselor/case-logs/${caselogId}`;
        });
    }

    // Simple search filter
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
</script>
@endpush
