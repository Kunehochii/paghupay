@extends('layouts.counselor')

@section('title', 'Create Case Log')

@push('styles')
<style>
    :root {
        --color-primary-bg: #69d297;
        --color-primary-light: #a7f0ba;
        --color-secondary: #3d9f9b;
        --color-secondary-dark: #235675;
    }

    /* Main Scrollable Card Container */
    .case-log-container {
        background-color: white;
        border: 2px solid var(--color-secondary-dark);
        border-radius: 12px;
        max-height: calc(100vh - 180px);
        overflow-y: auto;
        padding: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    /* Custom Scrollbar */
    .case-log-container::-webkit-scrollbar {
        width: 8px;
    }

    .case-log-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .case-log-container::-webkit-scrollbar-thumb {
        background: var(--color-secondary);
        border-radius: 4px;
    }

    .case-log-container::-webkit-scrollbar-thumb:hover {
        background: var(--color-secondary-dark);
    }

    /* Student ID Header */
    .student-id-header {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 12px 20px;
        margin-bottom: 20px;
        font-weight: 600;
        color: #333;
    }

    /* Note Card Styling */
    .note-card {
        border: 2px solid var(--color-secondary-dark);
        border-radius: 8px;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .note-card-header {
        background-color: var(--color-secondary);
        color: white;
        padding: 12px 16px;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .note-card-body {
        flex: 1;
        padding: 0;
        display: flex;
        flex-direction: column;
    }

    .note-textarea {
        width: 100%;
        min-height: 300px;
        border: none;
        border-left: 4px solid var(--color-secondary);
        padding: 15px 20px;
        font-size: 1rem;
        color: #333;
        resize: vertical;
        outline: none;
        background-color: transparent;
        flex: 1;
    }

    .note-textarea::placeholder {
        color: #999;
    }

    .note-textarea:focus {
        outline: none;
        border-left-color: var(--color-primary-bg);
        background-color: #fafffe;
    }

    /* Goal Card Styles */
    .goal-card {
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
    }

    .goal-header {
        background: #f8f9fa;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #dee2e6;
        border-radius: 0.5rem 0.5rem 0 0;
    }

    .goal-body {
        padding: 1rem;
    }

    .activity-item {
        background: #f8f9fa;
        padding: 0.75rem;
        border-radius: 0.375rem;
        margin-bottom: 0.5rem;
    }

    .remove-btn {
        opacity: 0.5;
        transition: opacity 0.2s;
    }

    .remove-btn:hover {
        opacity: 1;
    }

    /* Error styling for note textareas */
    .note-card.has-error {
        border-color: #dc3545;
    }

    .note-card.has-error .note-card-header {
        background-color: #dc3545;
    }

    .error-message {
        color: #dc3545;
        font-size: 0.875rem;
        padding: 8px 16px;
        background-color: #fff5f5;
    }

    /* Treatment Plan Table Styling */
    .treatment-plan-container {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 20px;
    }

    .treatment-plan-header {
        background-color: var(--color-secondary);
        color: white;
        padding: 12px 16px;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .treatment-plan-table {
        width: 100%;
        border-collapse: collapse;
    }

    .treatment-plan-table th,
    .treatment-plan-table td {
        border: 1px solid #dee2e6;
        padding: 0;
        vertical-align: top;
    }

    .goal-row-header {
        background-color: #d4eded;
        padding: 8px 12px;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        color: #333;
        width: 25%;
        min-width: 150px;
        vertical-align: top;
    }

    .goal-description-cell {
        background-color: white;
        padding: 0;
        vertical-align: top;
    }

    .goal-description-input {
        width: 100%;
        min-height: 60px;
        border: none;
        padding: 10px 12px;
        font-size: 0.9rem;
        resize: none;
        outline: none;
        background-color: white;
    }

    .goal-description-input:focus {
        background-color: #fafffe;
    }

    .activity-header {
        background-color: #d4eded;
        padding: 8px 12px;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        color: #333;
        text-align: center;
    }

    .goal-input-cell {
        padding: 0;
    }

    .goal-textarea {
        width: 100%;
        min-height: 80px;
        border: none;
        padding: 10px 12px;
        font-size: 0.9rem;
        resize: none;
        outline: none;
    }

    .goal-textarea:focus {
        background-color: #fafffe;
    }

    .activity-cell {
        padding: 0;
        position: relative;
    }

    .activity-input {
        width: 100%;
        min-height: 60px;
        border: none;
        border-bottom: 1px solid #dee2e6;
        padding: 10px 12px;
        font-size: 0.9rem;
        resize: none;
        outline: none;
    }

    .activity-input:focus {
        background-color: #fafffe;
    }

    .date-input-wrapper {
        padding: 8px 12px;
        background-color: #fafafa;
    }

    .date-label {
        font-size: 0.75rem;
        color: #666;
        margin-bottom: 4px;
    }

    .date-input {
        width: 100%;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        padding: 4px 8px;
        font-size: 0.85rem;
        outline: none;
    }

    .date-input:focus {
        border-color: var(--color-secondary);
    }

    .add-goal-btn {
        background-color: transparent;
        border: 2px dashed #dee2e6;
        color: #666;
        padding: 12px;
        width: 100%;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.9rem;
    }

    .add-goal-btn:hover {
        border-color: var(--color-secondary);
        color: var(--color-secondary);
        background-color: #f8fffc;
    }

    .remove-goal-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 12px;
        line-height: 1;
        cursor: pointer;
        opacity: 0;
        transition: opacity 0.2s;
    }

    .goal-row:hover .remove-goal-btn {
        opacity: 1;
    }

    /* Save Button */
    .btn-save {
        background-color: var(--color-secondary);
        border: none;
        color: white;
        font-weight: 600;
        padding: 12px 50px;
        border-radius: 25px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .btn-save:hover {
        background-color: #358a87;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(61, 159, 155, 0.4);
    }

    .save-button-container {
        display: flex;
        justify-content: flex-end;
        padding-top: 20px;
    }
</style>
@endpush

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('counselor.case-logs.index') }}">Case Logs</a></li>
                <li class="breadcrumb-item active">Create New</li>
            </ol>
        </nav>
    </div>
</div>

<form action="{{ route('counselor.case-logs.store') }}" method="POST" id="caseLogForm">
    @csrf

    <div class="row">
        {{-- Full Width: Main Scrollable Card --}}
        <div class="col-12 col-lg-10 col-xl-9 mx-auto">
            {{-- Student Selection (Above scrollable card) --}}
            <div class="mb-3">
                <label for="client_id" class="form-label">Select Student <span class="text-danger">*</span></label>
                <select class="form-select @error('client_id') is-invalid @enderror" id="client_id" name="client_id" required>
                    <option value="">-- Select a student --</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" 
                                data-tupv-id="{{ $client->course_year_section ?? 'N/A' }}"
                                {{ old('client_id') == $client->id ? 'selected' : '' }}>
                            {{ $client->name }} 
                            @if($client->course_year_section) - {{ $client->course_year_section }} @endif
                        </option>
                    @endforeach
                </select>
                @error('client_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Main Scrollable Case Log Card --}}
            <div class="case-log-container">
                {{-- Student ID Header --}}
                <div class="student-id-header">
                    TUPV ID: <span id="displayTupvId">Select a student</span>
                </div>

                {{-- Progress Report and Additional Info Row --}}
                <div class="row g-3 mb-4">
                    {{-- Progress Report --}}
                    <div class="col-md-6">
                        <div class="note-card @error('progress_report') has-error @enderror">
                            <div class="note-card-header">
                                Progress Report
                            </div>
                            <div class="note-card-body">
                                <textarea name="progress_report" 
                                          id="progress_report" 
                                          class="note-textarea" 
                                          placeholder="Enter text here">{{ old('progress_report') }}</textarea>
                                @error('progress_report')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Additional Information/Recommendations --}}
                    <div class="col-md-6">
                        <div class="note-card @error('additional_notes') has-error @enderror">
                            <div class="note-card-header">
                                Additional Information/Recommendations
                            </div>
                            <div class="note-card-body">
                                <textarea name="additional_notes" 
                                          id="additional_notes" 
                                          class="note-textarea" 
                                          placeholder="Enter text here">{{ old('additional_notes') }}</textarea>
                                @error('additional_notes')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Hidden Session Details --}}
                <input type="hidden" id="start_time" name="start_time" value="{{ old('start_time', now()->format('Y-m-d\TH:i')) }}">
                <input type="hidden" id="end_time" name="end_time" value="{{ old('end_time', now()->addHour()->format('Y-m-d\TH:i')) }}">

                {{-- Treatment Plan Section --}}
                <div class="treatment-plan-container">
                    <div class="treatment-plan-header">
                        Treatment Plan
                    </div>
                    <table class="treatment-plan-table" id="treatmentPlanTable">
                        <tbody id="goalsTableBody">
                            {{-- Goal rows will be added here dynamically --}}
                        </tbody>
                    </table>
                    <button type="button" class="add-goal-btn" onclick="addGoal()">
                        <i class="bi bi-plus-circle me-2"></i>Add Goal
                    </button>
                </div>

                {{-- Save Button --}}
                <div class="save-button-container">
                    <button type="submit" class="btn-save">
                        Save
                    </button>
                </div>

            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    let goalIndex = 0;

    // Student selection - Update TUPV ID display
    document.getElementById('client_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const tupvId = selectedOption.dataset.tupvId || 'N/A';
        document.getElementById('displayTupvId').textContent = this.value ? tupvId : 'Select a student';
    });

    // Add a new goal row
    function addGoal() {
        goalIndex++;
        const tbody = document.getElementById('goalsTableBody');
        
        const goalHtml = `
            <tr class="goal-row" data-goal-index="${goalIndex}">
                <td class="goal-row-header" rowspan="3" style="position: relative;">
                    GOAL ${goalIndex}
                    <button type="button" class="remove-goal-btn" onclick="removeGoal(this)" title="Remove Goal">
                        <i class="bi bi-x"></i>
                    </button>
                </td>
                <td class="activity-header">ACTIVITY 1</td>
                <td class="activity-header">ACTIVITY 2</td>
                <td class="activity-header">ACTIVITY 3</td>
            </tr>
            <tr class="goal-row" data-goal-index="${goalIndex}">
                <td class="goal-description-cell" colspan="3">
                    <textarea class="goal-description-input" 
                              name="goals[${goalIndex}][description]" 
                              placeholder="Enter goal description..."></textarea>
                </td>
            </tr>
            <tr class="goal-row" data-goal-index="${goalIndex}">
                <td class="activity-cell">
                    <textarea class="activity-input" 
                              name="goals[${goalIndex}][activities][1][description]" 
                              placeholder=""></textarea>
                    <div class="date-input-wrapper">
                        <div class="date-label">Date:</div>
                        <input type="date" class="date-input" 
                               name="goals[${goalIndex}][activities][1][activity_date]">
                    </div>
                </td>
                <td class="activity-cell">
                    <textarea class="activity-input" 
                              name="goals[${goalIndex}][activities][2][description]" 
                              placeholder=""></textarea>
                    <div class="date-input-wrapper">
                        <div class="date-label">Date:</div>
                        <input type="date" class="date-input" 
                               name="goals[${goalIndex}][activities][2][activity_date]">
                    </div>
                </td>
                <td class="activity-cell">
                    <textarea class="activity-input" 
                              name="goals[${goalIndex}][activities][3][description]" 
                              placeholder=""></textarea>
                    <div class="date-input-wrapper">
                        <div class="date-label">Date:</div>
                        <input type="date" class="date-input" 
                               name="goals[${goalIndex}][activities][3][activity_date]">
                    </div>
                </td>
            </tr>
        `;
        
        tbody.insertAdjacentHTML('beforeend', goalHtml);
    }

    // Remove a goal
    function removeGoal(btn) {
        const row = btn.closest('tr');
        const goalIdx = row.dataset.goalIndex;
        
        // Remove both rows for this goal (header row and content row)
        const rows = document.querySelectorAll(`tr[data-goal-index="${goalIdx}"]`);
        rows.forEach(r => r.remove());
        
        // Renumber remaining goals
        renumberGoals();
    }

    // Renumber goals after removal
    function renumberGoals() {
        const headerCells = document.querySelectorAll('.goal-row-header');
        headerCells.forEach((cell, index) => {
            const goalNum = index + 1;
            cell.childNodes[0].textContent = `GOAL ${goalNum}`;
        });
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        const clientSelect = document.getElementById('client_id');
        if (clientSelect.value) {
            clientSelect.dispatchEvent(new Event('change'));
        }
        
        // Add initial goals if none exist
        if (document.querySelectorAll('.goal-row').length === 0) {
            addGoal();
            addGoal();
        }
    });
</script>
@endpush
