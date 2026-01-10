@extends('layouts.counselor')

@section('title', 'Pending Appointments')

@push('styles')
<style>
    /* Color Variables */
    :root {
        --color-primary-bg: #69d297;
        --color-primary-light: #a7f0ba;
        --color-secondary: #3d9f9b;
        --color-secondary-dark: #235675;
    }

    /* Search Bar */
    .search-wrapper {
        position: relative;
        max-width: 100%;
        margin-bottom: 1.5rem;
    }

    .search-wrapper input {
        width: 100%;
        padding: 12px 20px 12px 45px;
        border: 1px solid #e0e0e0;
        border-radius: 25px;
        font-size: 0.95rem;
        background: white;
        transition: all 0.3s ease;
    }

    .search-wrapper input:focus {
        outline: none;
        border-color: var(--color-secondary);
        box-shadow: 0 0 0 3px rgba(61, 159, 155, 0.1);
    }

    .search-wrapper input::placeholder {
        color: #999;
    }

    .search-wrapper .search-icon {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
        font-size: 1rem;
    }

    /* Your Appointments Title */
    .appointments-title {
        display: inline-block;
        padding: 12px 30px;
        border: 2px solid #e0e0e0;
        border-radius: 30px;
        font-size: 1rem;
        font-weight: 500;
        color: #333;
        margin-bottom: 1.5rem;
    }

    /* Main Card Container */
    .appointments-main-card {
        background: white;
        border-radius: 12px;
        border: 2px solid var(--color-secondary);
        overflow: hidden;
        display: flex;
    }

    /* Left Section - Stats & Upcoming (narrower) */
    .appointments-left {
        flex: 0 0 340px;
        border-right: 1px solid #eee;
        display: flex;
        flex-direction: column;
    }

    /* Right Section - Calendar (larger) */
    .appointments-right {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    /* Pending Stats Section */
    .pending-stats {
        padding: 25px 25px 20px;
    }

    .pending-stats-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .pending-stats-value {
        font-size: 4rem;
        font-weight: 700;
        color: var(--color-secondary);
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .pending-stats-subtitle {
        font-size: 0.9rem;
        color: #666;
    }

    /* Upcoming Appointments List */
    .upcoming-section {
        flex: 1;
        display: flex;
        flex-direction: column;
        border-top: 1px solid #eee;
    }

    .upcoming-header {
        background: #f8f9fa;
        padding: 12px 20px;
        border-bottom: 1px solid #eee;
    }

    .upcoming-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #333;
        margin: 0;
    }

    .upcoming-list {
        flex: 1;
        overflow-y: auto;
        max-height: 300px;
    }

    .upcoming-item {
        padding: 15px 20px;
        border-bottom: 1px solid #f0f0f0;
    }

    .upcoming-item:last-child {
        border-bottom: none;
    }

    .upcoming-client-name {
        font-weight: 600;
        color: #333;
        font-size: 0.95rem;
        margin-bottom: 2px;
    }

    .upcoming-date {
        font-size: 0.8rem;
        color: #888;
        margin-bottom: 10px;
    }

    .upcoming-actions {
        display: flex;
        gap: 8px;
    }

    .btn-accept {
        flex: 1;
        padding: 8px 16px;
        background-color: var(--color-secondary);
        color: white;
        border: none;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-accept:hover {
        background-color: #358a87;
    }

    .btn-reject {
        padding: 8px 16px;
        background-color: #dc3545;
        color: white;
        border: none;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-reject:hover {
        background-color: #c82333;
    }

    /* Calendar Section */
    .calendar-section {
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    /* Calendar Header - Teal */
    .calendar-header {
        background-color: var(--color-secondary-dark);
        padding: 18px 25px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .calendar-nav-btn {
        background: none;
        border: none;
        color: white;
        font-size: 1.3rem;
        cursor: pointer;
        padding: 5px 10px;
        transition: all 0.2s;
        opacity: 0.9;
        text-decoration: none;
    }

    .calendar-nav-btn:hover {
        opacity: 1;
        transform: scale(1.1);
        color: white;
    }

    .calendar-month-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: white;
        text-transform: uppercase;
        letter-spacing: 3px;
    }

    /* Calendar Grid */
    .calendar-grid {
        padding: 20px 25px;
        flex: 1;
    }

    .calendar-weekdays {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 8px;
        margin-bottom: 15px;
    }

    .weekday {
        text-align: center;
        font-size: 0.75rem;
        font-weight: 600;
        color: #666;
        padding: 8px 0;
        text-transform: uppercase;
    }

    .weekday.weekend {
        color: #bbb;
    }

    .calendar-days {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 8px;
    }

    .calendar-day {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-end;
        padding: 8px 4px;
        min-height: 55px;
        cursor: pointer;
        border-radius: 8px;
        transition: background 0.2s;
        position: relative;
    }

    .calendar-day:hover:not(.empty) {
        background: rgba(61, 159, 155, 0.1);
    }

    .calendar-day.today .day-number {
        background: var(--color-secondary-dark);
        color: white;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .calendar-day.weekend .day-number {
        color: #bbb;
    }

    .calendar-day.other-month .day-number {
        color: #ccc;
    }

    .day-number {
        font-size: 0.95rem;
        font-weight: 500;
        color: #333;
    }

    /* Appointment Dots above the date */
    .appointment-dots {
        display: flex;
        gap: 3px;
        margin-bottom: 4px;
        min-height: 10px;
    }

    .appointment-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: var(--color-secondary);
    }

    .calendar-day.empty {
        visibility: hidden;
    }

    /* View Calendar Button */
    .view-calendar-wrapper {
        padding: 15px 25px 20px;
    }

    .view-calendar-btn {
        display: block;
        width: 100%;
        padding: 12px 20px;
        background-color: var(--color-secondary);
        color: white;
        border: none;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        text-align: center;
        text-decoration: none;
    }

    .view-calendar-btn:hover {
        background-color: #358a87;
        color: white;
    }

    /* Calendar Full View Mode */
    .calendar-full-view .calendar-day {
        min-height: 70px;
        align-items: flex-start;
        padding: 5px;
    }

    .calendar-full-view .day-number {
        align-self: flex-start;
        margin-bottom: 4px;
    }

    .calendar-full-view .appointment-dots {
        display: none;
    }

    .calendar-full-view .appointment-names {
        display: block;
        width: 100%;
    }

    .appointment-names {
        display: none;
        width: 100%;
    }

    .appointment-name {
        font-size: 0.65rem;
        background: rgba(61, 159, 155, 0.15);
        color: var(--color-secondary-dark);
        padding: 2px 4px;
        border-radius: 3px;
        margin-bottom: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .appointment-more {
        font-size: 0.6rem;
        color: #888;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
    }

    .empty-state i {
        font-size: 3rem;
        color: #ddd;
        margin-bottom: 15px;
    }

    .empty-state p {
        color: #888;
        margin: 0;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .appointments-main-card {
            flex-direction: column;
        }

        .appointments-left {
            flex: none;
            border-right: none;
            border-bottom: 1px solid #eee;
        }

        .upcoming-list {
            max-height: 200px;
        }
    }
</style>
@endpush

@section('content')
{{-- Search Bar --}}
<div class="search-wrapper">
    <i class="bi bi-search search-icon"></i>
    <input type="text" placeholder="Search" id="searchInput">
</div>

{{-- Title --}}
<div class="appointments-title">Your Appointments</div>

{{-- Main Card --}}
<div class="appointments-main-card">
    {{-- Left Section - Pending Stats & Upcoming List --}}
    <div class="appointments-left">
        {{-- Pending Stats --}}
        <div class="pending-stats">
            <div class="pending-stats-title">Pending Appointments</div>
            <div class="pending-stats-value">{{ $pendingCount }}</div>
            <div class="pending-stats-subtitle">for this Month</div>
        </div>

        {{-- Upcoming Appointments List --}}
        <div class="upcoming-section">
            <div class="upcoming-header">
                <h5 class="upcoming-title">Upcoming Appointments</h5>
            </div>
            <div class="upcoming-list">
                @forelse($pendingAppointments as $appointment)
                    <div class="upcoming-item" data-name="{{ strtolower($appointment->client->name) }}">
                        <div class="upcoming-client-name">{{ $appointment->client->name }}</div>
                        <div class="upcoming-date">{{ $appointment->scheduled_at->format('F j, Y') }}</div>
                        <div class="upcoming-actions">
                            <form action="{{ route('counselor.appointments.accept', $appointment->id) }}" method="POST" style="flex: 1;">
                                @csrf
                                <button type="submit" class="btn-accept" style="width: 100%;">Accept</button>
                            </form>
                            <button type="button" class="btn-reject"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#rejectModal"
                                    data-appointment-id="{{ $appointment->id }}"
                                    data-client-name="{{ $appointment->client->name }}">
                                Reject
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="bi bi-calendar-check"></i>
                        <p>No pending appointments</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Right Section - Calendar --}}
    <div class="appointments-right">
        <div class="calendar-section" id="calendarCard">
            {{-- Calendar Header --}}
            <div class="calendar-header">
                <a href="{{ route('counselor.appointments.index', ['month' => $prevMonth->format('Y-m')]) }}" class="calendar-nav-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <span class="calendar-month-title">{{ $currentMonth->format('F') }}</span>
                <a href="{{ route('counselor.appointments.index', ['month' => $nextMonth->format('Y-m')]) }}" class="calendar-nav-btn">
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            {{-- Calendar Grid --}}
            <div class="calendar-grid">
                {{-- Weekday Headers --}}
                <div class="calendar-weekdays">
                    <div class="weekday weekend">S</div>
                    <div class="weekday">M</div>
                    <div class="weekday">T</div>
                    <div class="weekday">W</div>
                    <div class="weekday">T</div>
                    <div class="weekday">F</div>
                    <div class="weekday weekend">S</div>
                </div>

                {{-- Calendar Days --}}
                <div class="calendar-days">
                    @foreach($calendarDays as $day)
                        <div class="calendar-day {{ $day['isToday'] ? 'today' : '' }} {{ $day['isWeekend'] ? 'weekend' : '' }} {{ !$day['isCurrentMonth'] ? 'other-month' : '' }} {{ $day['dayNumber'] === null ? 'empty' : '' }}"
                             @if($day['date']) data-date="{{ $day['date'] }}" onclick="selectDate('{{ $day['date'] }}')" @endif>
                            @if($day['dayNumber'])
                                {{-- Appointment Dots (max 2) --}}
                                <div class="appointment-dots">
                                    @foreach($day['appointments']->take(2) as $apt)
                                        <div class="appointment-dot {{ $apt->status }}"></div>
                                    @endforeach
                                </div>
                                {{-- Appointment Names for Full View --}}
                                <div class="appointment-names">
                                    @foreach($day['appointments']->take(2) as $apt)
                                        <div class="appointment-name {{ $apt->status }}">
                                            {{ Str::limit($apt->client->name, 12) }}
                                        </div>
                                    @endforeach
                                    @if($day['appointments']->count() > 2)
                                        <div class="appointment-more">+{{ $day['appointments']->count() - 2 }} more</div>
                                    @endif
                                </div>
                                <span class="day-number">{{ $day['dayNumber'] }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- View Calendar Button --}}
            <div class="view-calendar-wrapper">
                <button type="button" class="view-calendar-btn" id="viewCalendarBtn">View Calendar</button>
            </div>
        </div>
    </div>
</div>

{{-- Reject Modal --}}
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-x-circle me-2"></i>Reject Appointment</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>You are about to reject the appointment request from <strong id="rejectClientName"></strong>.</p>
                    <div class="mb-3">
                        <label for="rejectReason" class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejectReason" name="reason" rows="3" required 
                                  placeholder="e.g., Schedule conflict, please book another time slot..."></textarea>
                    </div>
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        The student will be notified via email and can book a new appointment.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Appointment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        document.querySelectorAll('.upcoming-item').forEach(function(item) {
            const name = item.getAttribute('data-name') || '';
            if (name.includes(searchTerm)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // View Calendar toggle
    let isFullView = false;
    document.getElementById('viewCalendarBtn').addEventListener('click', function() {
        const calendarCard = document.getElementById('calendarCard');
        isFullView = !isFullView;
        
        if (isFullView) {
            calendarCard.classList.add('calendar-full-view');
            this.textContent = 'Compact View';
        } else {
            calendarCard.classList.remove('calendar-full-view');
            this.textContent = 'View Calendar';
        }
    });

    // Select date from calendar - navigate to day view
    function selectDate(date) {
        window.location.href = `{{ route('counselor.appointments.day') }}?date=${date}`;
    }

    // Reject modal
    const rejectModal = document.getElementById('rejectModal');
    if (rejectModal) {
        rejectModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const appointmentId = button.getAttribute('data-appointment-id');
            const clientName = button.getAttribute('data-client-name');
            
            document.getElementById('rejectClientName').textContent = clientName;
            document.getElementById('rejectForm').action = `/counselor/appointments/${appointmentId}/reject`;
        });
    }
</script>
@endpush
