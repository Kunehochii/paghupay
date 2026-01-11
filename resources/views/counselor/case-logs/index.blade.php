@extends('layouts.counselor')

@section('title', 'Case Logs')

@push('styles')
<style>
    .search-box {
        background-color: #f5f5f5;
        border: none;
        border-radius: 12px;
        padding: 12px 16px;
        padding-left: 40px;
        width: 100%;
        font-size: 14px;
    }
    .search-box:focus {
        outline: none;
        background-color: #eeeeee;
    }
    .search-wrapper {
        position: relative;
    }
    .search-wrapper .search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #888;
    }
    .all-cases-box {
        border: 1px solid #ccc;
        border-radius: 20px;
        padding: 12px 20px;
        background-color: #fff;
    }
    .case-logs-table {
        width: 100%;
        border-collapse: collapse;
    }
    .case-logs-table thead th {
        color: #666;
        font-weight: 500;
        font-size: 14px;
        padding: 16px 12px;
        border-bottom: 2px solid #ddd;
        border-right: 2px solid #ddd;
    }
    .case-logs-table thead th:first-child {
        border-left: none;
    }
    .case-logs-table thead th:last-child {
        border-right: none;
    }
    .case-logs-table tbody td {
        padding: 16px 12px;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
        border-right: 2px solid #ddd;
        font-size: 14px;
    }
    .case-logs-table tbody td:first-child {
        border-left: none;
    }
    .case-logs-table tbody td:last-child {
        border-right: none;
    }
    .case-logs-table tbody tr:nth-child(odd) {
        background-color: #f5f5f5;
    }
    .case-logs-table tbody tr:nth-child(even) {
        background-color: #fff;
    }
    .case-logs-table tbody tr:last-child td {
        border-bottom: none;
    }
    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        border: 1px solid #ddd;
        background: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #666;
        transition: all 0.2s;
        text-decoration: none;
    }
    .action-btn:hover {
        background-color: #f5f5f5;
        color: #333;
    }
    .btn-add-new {
        background-color: #3d9f9b;
        color: #fff;
        border: none;
        border-radius: 20px;
        padding: 10px 24px;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        display: inline-block;
    }
    .btn-add-new:hover {
        background-color: #358f8b;
        color: #fff;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    {{-- Search Bar --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="search-wrapper">
                <i class="bi bi-search search-icon"></i>
                <input type="text" class="search-box" placeholder="Search" id="searchInput">
            </div>
        </div>
    </div>

    {{-- All Cases Box --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="all-cases-box">
                <span style="font-size: 15px;">All Cases</span>
            </div>
        </div>
    </div>

    {{-- Case Logs Table --}}
    <div class="row">
        <div class="col-12">
            @if($caseLogs->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-journal-x text-muted" style="font-size: 4rem;"></i>
                    <h4 class="text-muted mt-3">No Case Logs Yet</h4>
                    <p class="text-muted">Case logs will appear here after you complete counseling sessions.</p>
                </div>
            @else
                <table class="case-logs-table">
                    <thead>
                        <tr>
                            <th>Created</th>
                            <th>TUPV ID</th>
                            <th>Log #</th>
                            <th>Time Elapsed</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($caseLogs as $index => $caseLog)
                        <tr>
                            <td>{{ $caseLog->created_at->format('m/d/Y') }}</td>
                            <td>{{ $caseLog->case_log_id }}</td>
                            <td>{{ str_pad($caseLogs->total() - (($caseLogs->currentPage() - 1) * $caseLogs->perPage()) - $index, 3, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                @if($caseLog->session_duration)
                                    {{ $caseLog->formatted_duration }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('counselor.case-logs.show', $caseLog->id) }}" 
                                       class="action-btn" 
                                       title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('counselor.case-logs.edit', $caseLog->id) }}" 
                                       class="action-btn" 
                                       title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="{{ route('counselor.case-logs.export-pdf', $caseLog->id) }}" 
                                       class="action-btn" 
                                       title="Export PDF"
                                       target="_blank">
                                        <i class="bi bi-file-earmark-text"></i>
                                    </a>
                                    <button type="button" class="action-btn" 
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

                {{-- Pagination --}}
                @if($caseLogs->hasPages())
                <div class="d-flex justify-content-center py-3">
                    {{ $caseLogs->links() }}
                </div>
                @endif
            @endif
        </div>
    </div>

    {{-- Add New Button --}}
    <div class="row mt-4">
        <div class="col-12 text-end">
            <a href="{{ route('counselor.case-logs.create') }}" class="btn-add-new">
                Add New
            </a>
        </div>
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
