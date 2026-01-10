@extends('layouts.app')

@section('title', 'Select Date & Time')

@push('styles')
<style>
    /* Color Variables */
    :root {
        --color-primary-bg: #69d297;
        --color-primary-light: #a7f0ba;
        --color-secondary: #3d9f9b;
        --color-secondary-dark: #235675;
    }

    .schedule-page {
        min-height: 100vh;
        background-color: #ffffff;
        padding-top: 80px;
    }

    /* Top Navigation - Overlay */
    .nav-custom {
        background-color: transparent;
        padding: 15px 0;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 100;
    }

    .nav-custom .nav-link-custom {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: var(--color-secondary);
        color: white;
        margin: 0 5px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .nav-custom .nav-link-custom:hover {
        background-color: #358a87;
        transform: scale(1.1);
    }

    .nav-custom .nav-link-about {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 20px;
        border-radius: 20px;
        border: 2px solid #333;
        background-color: transparent;
        color: #333;
        font-weight: 500;
        margin: 0 5px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .nav-custom .nav-link-about:hover {
        background-color: #333;
        color: white;
    }

    /* Step indicators */
    .step-indicators {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1rem;
    }

    .step-circle.active {
        background-color: var(--color-secondary);
        color: white;
    }

    .step-circle.inactive {
        background-color: white;
        color: #333;
        border: 2px solid #ccc;
    }

    /* Instruction text */
    .instruction-text {
        text-align: center;
        font-size: 1.3rem;
        color: #333;
        margin-bottom: 30px;
        font-weight: 600;
    }

    /* Main Schedule Card */
    .schedule-card {
        background-color: white;
        border-radius: 20px;
        border: 3px solid var(--color-secondary);
        overflow: hidden;
        max-width: 900px;
        margin: 0 auto;
    }

    .schedule-card .row {
        --bs-gutter-x: 0;
    }

    /* Calendar Section - 70% width */
    .calendar-section {
        flex: 0 0 70%;
        max-width: 70%;
        padding: 0;
    }

    /* Calendar Header - Green background */
    .calendar-header {
        background-color: var(--color-primary-bg);
        padding: 15px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .calendar-nav-btn {
        background: none;
        border: none;
        color: #333;
        font-size: 1.5rem;
        cursor: pointer;
        padding: 5px 10px;
        transition: all 0.2s;
    }

    .calendar-nav-btn:hover {
        color: var(--color-secondary-dark);
    }

    .calendar-month-year {
        font-size: 1.2rem;
        font-weight: 700;
        color: #333;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    /* Calendar Grid - White background */
    .calendar-grid {
        padding: 15px 20px 20px;
        background-color: white;
    }

    .calendar-weekdays {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 5px;
        margin-bottom: 10px;
    }

    .weekday {
        text-align: center;
        font-size: 0.75rem;
        font-weight: 600;
        color: #555;
        padding: 5px;
        text-transform: uppercase;
    }

    .calendar-days {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 5px;
    }

    .calendar-day {
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        font-weight: 500;
        color: #333;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.2s;
        background: transparent;
        border: none;
        min-width: 36px;
        min-height: 36px;
    }

    .calendar-day:hover:not(.disabled):not(.weekend) {
        background-color: rgba(105, 210, 151, 0.3);
    }

    .calendar-day.selected {
        background-color: #333;
        color: white;
    }

    .calendar-day.disabled,
    .calendar-day.weekend {
        color: #999;
        cursor: not-allowed;
    }

    .calendar-day.weekend {
        color: #bbb;
    }

    .calendar-day.today {
        border: 2px solid var(--color-secondary-dark);
    }

    .calendar-day.empty {
        visibility: hidden;
    }

    /* Time Section - 30% width */
    .time-section {
        flex: 0 0 30%;
        max-width: 30%;
        background-color: white;
        padding: 0;
    }

    .time-header {
        background-color: var(--color-primary-bg);
        color: #333;
        text-align: center;
        padding: 24.5px;
        font-size: 1.1rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .time-content {
        padding: 20px;
    }

    .time-category {
        margin-bottom: 20px;
    }

    .time-category-label {
        font-size: 1rem;
        font-weight: 700;
        color: var(--color-secondary);
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .time-slots-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .time-slot-btn {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        background-color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.95rem;
        color: #333;
    }

    .time-slot-btn:hover:not(.disabled) {
        background-color: #f0f0f0;
    }

    .time-slot-btn.selected {
        background-color: #ddd;
    }

    .time-slot-btn.disabled {
        color: #999;
        cursor: not-allowed;
        text-decoration: line-through;
    }

    .time-slot-radio {
        width: 20px;
        height: 20px;
        border: 2px solid #333;
        border-radius: 50%;
        margin-right: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .time-slot-radio.checked::after {
        content: '';
        width: 10px;
        height: 10px;
        background-color: #333;
        border-radius: 50%;
    }

    .time-slot-text {
        font-weight: 500;
    }

    /* Prompt/Loading states */
    .time-prompt {
        text-align: center;
        padding: 40px 20px;
        color: #666;
    }

    .time-prompt i {
        font-size: 2.5rem;
        margin-bottom: 10px;
        color: #999;
    }

    /* Next Button */
    .btn-next {
        background-color: var(--color-primary-light);
        border: none;
        color: #333;
        font-weight: 700;
        padding: 15px 80px;
        border-radius: 30px;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        transition: all 0.3s ease;
        margin-top: 30px;
    }

    .btn-next:hover:not(:disabled) {
        background-color: #8fe0a8;
        color: #333;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(167, 240, 186, 0.5);
    }

    .btn-next:disabled {
        background-color: #cce8d4;
        color: #888;
        cursor: not-allowed;
    }

    /* Alert styling */
    .alert-custom {
        border-radius: 10px;
        max-width: 900px;
        margin: 0 auto 20px;
    }

    /* Responsive adjustments */
    @media (max-width: 767.98px) {
        .schedule-card {
            border-radius: 15px;
        }

        .calendar-section {
            flex: 0 0 100%;
            max-width: 100%;
        }

        .time-section {
            flex: 0 0 100%;
            max-width: 100%;
        }

        .calendar-day {
            font-size: 0.85rem;
            min-width: 30px;
            min-height: 30px;
        }

        .btn-next {
            padding: 12px 50px;
        }
    }
</style>
@endpush

@section('content')
<div class="schedule-page">
    <!-- Top Navigation -->
    <nav class="nav-custom">
        <div class="container">
            <div class="d-flex justify-content-center align-items-center">
                <a href="{{ route('client.welcome') }}" class="nav-link-custom" title="Home">
                    <i class="bi bi-house-door-fill"></i>
                </a>
                <a href="#" class="nav-link-about">About us</a>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="nav-link-custom" title="Log Out">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <!-- Step Indicators -->
        <div class="step-indicators">
            <div class="step-circle inactive">1</div>
            <div class="step-circle active">2</div>
            <div class="step-circle inactive">3</div>
        </div>

        <!-- Instruction Text -->
        <p class="instruction-text">Select date and time to schedule your session.</p>

        @if(session('error'))
            <div class="alert alert-danger alert-custom alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Schedule Card -->
        <form action="{{ route('booking.select-schedule', $counselor) }}" method="POST" id="scheduleForm">
            @csrf
            <input type="hidden" name="scheduled_date" id="scheduledDate" value="">
            <input type="hidden" name="time_slot_id" id="selectedTimeSlotId" value="">

            <div class="schedule-card">
                <div class="d-flex flex-wrap">
                    <!-- Calendar Section -->
                    <div class="calendar-section">
                        <div class="calendar-header">
                            <button type="button" class="calendar-nav-btn" id="prevMonth">
                                <i class="bi bi-arrow-left"></i>
                            </button>
                            <span class="calendar-month-year" id="monthYearDisplay">JANUARY</span>
                            <button type="button" class="calendar-nav-btn" id="nextMonth">
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                        <div class="calendar-grid">
                            <div class="calendar-weekdays">
                                <div class="weekday">S</div>
                                <div class="weekday">M</div>
                                <div class="weekday">T</div>
                                <div class="weekday">W</div>
                                <div class="weekday">T</div>
                                <div class="weekday">F</div>
                                <div class="weekday">S</div>
                            </div>
                            <div class="calendar-days" id="calendarDays">
                                <!-- Generated by JS -->
                            </div>
                        </div>
                    </div>

                    <!-- Time Section -->
                    <div class="time-section">
                        <div class="time-header">TIME</div>
                        
                        <div class="time-content" id="timeSlotsContainer">
                            <div class="time-prompt" id="selectDatePrompt">
                                <i class="bi bi-calendar3 d-block"></i>
                                <p class="mb-0">Select a date to view available times</p>
                            </div>

                            <div id="timeSlotsContent" style="display: none;">
                                <!-- Morning Slots -->
                                <div class="time-category">
                                    <div class="time-category-label">MORNING</div>
                                    <div class="time-slots-list" id="morningSlots">
                                        <!-- Populated via JS -->
                                    </div>
                                </div>

                                <!-- Afternoon Slots -->
                                <div class="time-category">
                                    <div class="time-category-label">AFTERNOON</div>
                                    <div class="time-slots-list" id="afternoonSlots">
                                        <!-- Populated via JS -->
                                    </div>
                                </div>
                            </div>

                            <div id="noSlotsMessage" class="time-prompt" style="display: none;">
                                <i class="bi bi-calendar-x d-block text-danger"></i>
                                <p class="mb-0">No available slots for this date</p>
                            </div>

                            <div id="loadingSlots" class="time-prompt" style="display: none;">
                                <div class="spinner-border text-secondary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Next Button -->
            <div class="text-center">
                <button type="submit" class="btn btn-next" id="nextBtn" disabled>
                    NEXT
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const scheduledDateInput = document.getElementById('scheduledDate');
    const timeSlotInput = document.getElementById('selectedTimeSlotId');
    const nextBtn = document.getElementById('nextBtn');
    const counselorId = {{ $counselor->id }};

    // Blocked dates from backend
    const blockedDates = @json($blockedDates);
    const bookedDates = @json($bookedDates ?? []);

    // Calendar state
    let currentDate = new Date();
    let selectedDate = null;

    const monthNames = [
        'JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE',
        'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER'
    ];

    // Initialize calendar
    renderCalendar();

    // Month navigation
    document.getElementById('prevMonth').addEventListener('click', function() {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    });

    document.getElementById('nextMonth').addEventListener('click', function() {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    });

    function renderCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        
        // Update header
        document.getElementById('monthYearDisplay').textContent = monthNames[month];

        // Get first day and total days
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        const today = new Date();
        today.setHours(0, 0, 0, 0);

        const maxDate = new Date();
        maxDate.setMonth(maxDate.getMonth() + 3);

        const calendarDays = document.getElementById('calendarDays');
        calendarDays.innerHTML = '';

        // Empty cells before first day
        for (let i = 0; i < firstDay; i++) {
            const emptyCell = document.createElement('button');
            emptyCell.type = 'button';
            emptyCell.className = 'calendar-day empty';
            emptyCell.disabled = true;
            calendarDays.appendChild(emptyCell);
        }

        // Day cells
        for (let day = 1; day <= daysInMonth; day++) {
            const dayBtn = document.createElement('button');
            dayBtn.type = 'button';
            dayBtn.className = 'calendar-day';
            dayBtn.textContent = day;

            const dateObj = new Date(year, month, day);
            const dateString = formatDate(dateObj);
            const dayOfWeek = dateObj.getDay();

            // Check conditions
            const isWeekend = dayOfWeek === 0 || dayOfWeek === 6;
            const isPast = dateObj < today;
            const isFuture = dateObj > maxDate;
            const isBlocked = blockedDates.includes(dateString);
            const isToday = dateObj.getTime() === today.getTime();
            const isSelected = selectedDate === dateString;

            if (isWeekend) {
                dayBtn.classList.add('weekend');
                dayBtn.disabled = true;
            } else if (isPast || isFuture || isBlocked) {
                dayBtn.classList.add('disabled');
                dayBtn.disabled = true;
            } else {
                dayBtn.addEventListener('click', function() {
                    selectDate(dateString, day);
                });
            }

            if (isToday) {
                dayBtn.classList.add('today');
            }

            if (isSelected) {
                dayBtn.classList.add('selected');
            }

            calendarDays.appendChild(dayBtn);
        }
    }

    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    function selectDate(dateString, day) {
        // Update selection
        selectedDate = dateString;
        scheduledDateInput.value = dateString;

        // Update calendar UI
        document.querySelectorAll('.calendar-day').forEach(btn => {
            btn.classList.remove('selected');
        });
        event.target.classList.add('selected');

        // Reset time selection
        timeSlotInput.value = '';
        nextBtn.disabled = true;

        // Fetch available slots
        fetchAvailableSlots(dateString);
    }

    function fetchAvailableSlots(date) {
        document.getElementById('selectDatePrompt').style.display = 'none';
        document.getElementById('timeSlotsContent').style.display = 'none';
        document.getElementById('noSlotsMessage').style.display = 'none';
        document.getElementById('loadingSlots').style.display = 'block';

        fetch(`/booking/counselor/${counselorId}/slots?date=${date}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('loadingSlots').style.display = 'none';
                renderTimeSlots(data);
            })
            .catch(error => {
                console.error('Error fetching slots:', error);
                document.getElementById('loadingSlots').style.display = 'none';
                document.getElementById('noSlotsMessage').style.display = 'block';
            });
    }

    function renderTimeSlots(data) {
        const morningContainer = document.getElementById('morningSlots');
        const afternoonContainer = document.getElementById('afternoonSlots');
        
        morningContainer.innerHTML = '';
        afternoonContainer.innerHTML = '';

        let hasAvailableSlots = false;

        // Render morning slots
        if (data.morning && data.morning.length > 0) {
            data.morning.forEach(slot => {
                const btn = createSlotButton(slot);
                morningContainer.appendChild(btn);
                if (slot.is_available) hasAvailableSlots = true;
            });
        }

        // Render afternoon slots
        if (data.afternoon && data.afternoon.length > 0) {
            data.afternoon.forEach(slot => {
                const btn = createSlotButton(slot);
                afternoonContainer.appendChild(btn);
                if (slot.is_available) hasAvailableSlots = true;
            });
        }

        if (hasAvailableSlots || (data.morning && data.morning.length > 0) || (data.afternoon && data.afternoon.length > 0)) {
            document.getElementById('timeSlotsContent').style.display = 'block';
        } else {
            document.getElementById('noSlotsMessage').style.display = 'block';
        }
    }

    function createSlotButton(slot) {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'time-slot-btn';
        btn.dataset.slotId = slot.id;

        if (!slot.is_available) {
            btn.classList.add('disabled');
            btn.disabled = true;
        }

        btn.innerHTML = `
            <div class="time-slot-radio"></div>
            <span class="time-slot-text">${slot.formatted_time}</span>
        `;

        if (slot.is_available) {
            btn.addEventListener('click', function() {
                // Remove selection from all buttons
                document.querySelectorAll('.time-slot-btn').forEach(b => {
                    b.classList.remove('selected');
                    b.querySelector('.time-slot-radio').classList.remove('checked');
                });

                // Add selection to clicked button
                this.classList.add('selected');
                this.querySelector('.time-slot-radio').classList.add('checked');

                // Update hidden input
                timeSlotInput.value = this.dataset.slotId;

                // Enable next button
                nextBtn.disabled = false;
            });
        }

        return btn;
    }
});
</script>
@endpush
