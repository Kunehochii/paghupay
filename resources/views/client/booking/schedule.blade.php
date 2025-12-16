@extends('layouts.app')

@section('title', 'Select Date & Time')

@section('content')
<div class="container py-4">
    <!-- Progress Steps -->
    <div class="row justify-content-center mb-4">
        <div class="col-md-10 col-lg-8">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-center flex-fill">
                    <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-check"></i>
                    </div>
                    <div class="small mt-1 text-success">Counselor</div>
                </div>
                <div class="flex-fill border-top border-2 border-success" style="margin-top: -15px;"></div>
                <div class="text-center flex-fill">
                    <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <span class="fw-bold">2</span>
                    </div>
                    <div class="small mt-1 fw-semibold text-primary">Schedule</div>
                </div>
                <div class="flex-fill border-top border-2" style="margin-top: -15px;"></div>
                <div class="text-center flex-fill">
                    <div class="rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <span class="fw-bold">3</span>
                    </div>
                    <div class="small mt-1 text-muted">Reason</div>
                </div>
                <div class="flex-fill border-top border-2" style="margin-top: -15px;"></div>
                <div class="text-center flex-fill">
                    <div class="rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <span class="fw-bold">4</span>
                    </div>
                    <div class="small mt-1 text-muted">Confirm</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-calendar3 me-2"></i>Select Date & Time
                    </h4>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Selected Counselor Info -->
                    <div class="alert alert-info d-flex align-items-center mb-4">
                        <i class="bi bi-person-circle me-2" style="font-size: 1.5rem;"></i>
                        <div>
                            <strong>Selected Counselor:</strong> {{ $counselor->name }}
                            @if($counselor->counselorProfile && $counselor->counselorProfile->position)
                                <span class="text-muted">({{ $counselor->counselorProfile->position }})</span>
                            @endif
                        </div>
                    </div>

                    <form action="{{ route('booking.select-schedule', $counselor) }}" method="POST" id="scheduleForm">
                        @csrf

                        <div class="row g-4">
                            <!-- Calendar Section -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-calendar-date me-1"></i>Select Date
                                </label>
                                <input type="date" 
                                       name="scheduled_date" 
                                       id="scheduledDate"
                                       class="form-control form-control-lg"
                                       min="{{ date('Y-m-d') }}"
                                       max="{{ date('Y-m-d', strtotime('+3 months')) }}"
                                       required>
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Weekends and blocked dates are not available.
                                </div>
                            </div>

                            <!-- Time Slots Section -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-clock me-1"></i>Select Time Slot
                                </label>
                                
                                <div id="timeSlotsContainer">
                                    <div class="text-center py-4 text-muted" id="selectDatePrompt">
                                        <i class="bi bi-calendar-x" style="font-size: 2rem;"></i>
                                        <p class="mb-0 mt-2">Please select a date first</p>
                                    </div>

                                    <div id="timeSlotsContent" style="display: none;">
                                        <!-- Morning Slots -->
                                        <div class="mb-3">
                                            <h6 class="text-muted mb-2">
                                                <i class="bi bi-sunrise me-1"></i>Morning
                                            </h6>
                                            <div id="morningSlots" class="d-grid gap-2">
                                                <!-- Populated via JS -->
                                            </div>
                                        </div>

                                        <!-- Afternoon Slots -->
                                        <div>
                                            <h6 class="text-muted mb-2">
                                                <i class="bi bi-sunset me-1"></i>Afternoon
                                            </h6>
                                            <div id="afternoonSlots" class="d-grid gap-2">
                                                <!-- Populated via JS -->
                                            </div>
                                        </div>
                                    </div>

                                    <div id="noSlotsMessage" class="text-center py-4 text-muted" style="display: none;">
                                        <i class="bi bi-calendar-x text-danger" style="font-size: 2rem;"></i>
                                        <p class="mb-0 mt-2">No available slots for this date</p>
                                    </div>

                                    <div id="loadingSlots" class="text-center py-4" style="display: none;">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="time_slot_id" id="selectedTimeSlotId" value="">
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('booking.choose-counselor') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Back
                            </a>
                            <button type="submit" class="btn btn-primary" id="nextBtn" disabled>
                                Next<i class="bi bi-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('scheduledDate');
    const timeSlotInput = document.getElementById('selectedTimeSlotId');
    const nextBtn = document.getElementById('nextBtn');
    const counselorId = {{ $counselor->id }};

    // Blocked dates from backend
    const blockedDates = @json($blockedDates);
    const bookedDates = @json($bookedDates);

    // Function to check if date is weekend
    function isWeekend(dateString) {
        const date = new Date(dateString);
        const day = date.getDay();
        return day === 0 || day === 6; // Sunday = 0, Saturday = 6
    }

    // Function to check if date is blocked
    function isBlocked(dateString) {
        return blockedDates.includes(dateString) || bookedDates.includes(dateString);
    }

    // Date change handler
    dateInput.addEventListener('change', function() {
        const selectedDate = this.value;

        // Validate weekend
        if (isWeekend(selectedDate)) {
            alert('Appointments cannot be booked on weekends. Please select a weekday.');
            this.value = '';
            return;
        }

        // Validate blocked date
        if (isBlocked(selectedDate)) {
            alert('This date is not available for booking. Please select another date.');
            this.value = '';
            return;
        }

        // Fetch available slots
        fetchAvailableSlots(selectedDate);
    });

    // Fetch available time slots from API
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

    // Render time slots
    function renderTimeSlots(data) {
        const morningContainer = document.getElementById('morningSlots');
        const afternoonContainer = document.getElementById('afternoonSlots');
        
        morningContainer.innerHTML = '';
        afternoonContainer.innerHTML = '';

        let hasAvailableSlots = false;

        // Render morning slots
        data.morning.forEach(slot => {
            const btn = createSlotButton(slot);
            morningContainer.appendChild(btn);
            if (slot.is_available) hasAvailableSlots = true;
        });

        // Render afternoon slots
        data.afternoon.forEach(slot => {
            const btn = createSlotButton(slot);
            afternoonContainer.appendChild(btn);
            if (slot.is_available) hasAvailableSlots = true;
        });

        if (hasAvailableSlots) {
            document.getElementById('timeSlotsContent').style.display = 'block';
        } else {
            document.getElementById('noSlotsMessage').style.display = 'block';
        }

        // Reset selection
        timeSlotInput.value = '';
        nextBtn.disabled = true;
    }

    // Create slot button
    function createSlotButton(slot) {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = slot.is_available 
            ? 'btn btn-outline-primary text-start slot-btn' 
            : 'btn btn-outline-secondary text-start disabled';
        btn.dataset.slotId = slot.id;
        btn.innerHTML = `
            <i class="bi bi-clock me-2"></i>
            ${slot.formatted_time}
            ${!slot.is_available ? '<span class="badge bg-danger ms-2">Booked</span>' : ''}
        `;

        if (slot.is_available) {
            btn.addEventListener('click', function() {
                // Remove selection from all buttons
                document.querySelectorAll('.slot-btn').forEach(b => {
                    b.classList.remove('btn-primary');
                    b.classList.add('btn-outline-primary');
                });

                // Add selection to clicked button
                this.classList.remove('btn-outline-primary');
                this.classList.add('btn-primary');

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

@push('styles')
<style>
.slot-btn {
    transition: all 0.2s;
}
.slot-btn:not(.disabled):hover {
    transform: translateX(5px);
}
</style>
@endpush
