@extends('layouts.counselor')

@section('title', 'My Availability')

@push('styles')
    <style>
        :root {
            --color-primary-bg: #69d297;
            --color-primary-light: #a7f0ba;
            --color-secondary: #3d9f9b;
            --color-secondary-dark: #235675;
        }

        .availability-card {
            background: white;
            border-radius: 12px;
            border: 2px solid var(--color-secondary);
            overflow: hidden;
        }

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

        .calendar-grid {
            padding: 20px 25px;
        }

        .calendar-weekdays {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 8px;
            margin-bottom: 15px;
        }

        .weekday {
            text-align: center;
            font-size: 0.8rem;
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
            justify-content: center;
            padding: 12px 4px;
            min-height: 60px;
            border-radius: 10px;
            transition: all 0.2s;
            position: relative;
            cursor: default;
        }

        .calendar-day.clickable {
            cursor: pointer;
        }

        .calendar-day.clickable:hover {
            background: rgba(61, 159, 155, 0.1);
            transform: scale(1.05);
        }

        .calendar-day.today .day-number {
            background: var(--color-secondary-dark);
            color: white;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .calendar-day.weekend {
            background: #f5f5f5;
        }

        .calendar-day.weekend .day-number {
            color: #bbb;
        }

        .calendar-day.other-month .day-number {
            color: #ccc;
        }

        .calendar-day.past {
            opacity: 0.5;
        }

        .calendar-day.unavailable {
            background: #fee2e2;
            border: 2px solid #fca5a5;
        }

        .calendar-day.unavailable .day-number {
            color: #dc2626;
            font-weight: 700;
        }

        .calendar-day.unavailable::after {
            content: '✕';
            position: absolute;
            top: 4px;
            right: 6px;
            font-size: 0.65rem;
            color: #dc2626;
            font-weight: 700;
        }

        .calendar-day.partial {
            background: #fff3cd;
            border: 2px solid #ffc107;
        }

        .calendar-day.partial .day-number {
            color: #b8860b;
            font-weight: 700;
        }

        .calendar-day.partial::after {
            content: '~';
            position: absolute;
            top: 4px;
            right: 6px;
            font-size: 0.75rem;
            color: #b8860b;
            font-weight: 700;
        }

        .day-number {
            font-size: 0.95rem;
            font-weight: 500;
            color: #333;
        }

        .legend {
            display: flex;
            gap: 25px;
            justify-content: center;
            padding: 15px 25px 20px;
            border-top: 1px solid #eee;
            flex-wrap: wrap;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            color: #666;
        }

        .legend-dot {
            width: 16px;
            height: 16px;
            border-radius: 4px;
        }

        .legend-dot.available {
            background: white;
            border: 1px solid #ddd;
        }

        .legend-dot.unavailable {
            background: #fee2e2;
            border: 2px solid #fca5a5;
        }

        .legend-dot.partial {
            background: #fff3cd;
            border: 2px solid #ffc107;
        }

        .legend-dot.weekend {
            background: #f5f5f5;
            border: 1px solid #ddd;
        }

        .instructions-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #e0e0e0;
            padding: 20px;
        }

        .toast-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            padding: 12px 24px;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(-10px);
        }

        .toast-notification.show {
            opacity: 1;
            transform: translateY(0);
        }

        .toast-notification.success {
            background: #10b981;
        }

        .toast-notification.error {
            background: #ef4444;
        }

        .slot-option {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            margin-bottom: 6px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .slot-option.available {
            background: #f0fdf4;
            border-color: #86efac;
        }

        .slot-option.unavailable {
            background: #fef2f2;
            border-color: #fca5a5;
            text-decoration: line-through;
            color: #999;
        }

        .slot-option:hover:not(.unavailable) {
            background: #dcfce7;
        }

        .slot-time {
            font-weight: 600;
            font-size: 0.95rem;
        }
    </style>
@endpush

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="mb-1">My Availability</h4>
            <p class="text-muted mb-0">Click a date to manage your available time slots</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="availability-card">
                {{-- Calendar Header --}}
                <div class="calendar-header">
                    <a href="{{ route('counselor.availability.index', ['month' => $prevMonth->format('Y-m')]) }}"
                        class="calendar-nav-btn">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                    <span class="calendar-month-title">{{ strtoupper($currentMonth->format('F Y')) }}</span>
                    <a href="{{ route('counselor.availability.index', ['month' => $nextMonth->format('Y-m')]) }}"
                        class="calendar-nav-btn">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </div>

                {{-- Calendar Grid --}}
                <div class="calendar-grid">
                    <div class="calendar-weekdays">
                        <div class="weekday weekend">SUN</div>
                        <div class="weekday">MON</div>
                        <div class="weekday">TUE</div>
                        <div class="weekday">WED</div>
                        <div class="weekday">THU</div>
                        <div class="weekday">FRI</div>
                        <div class="weekday weekend">SAT</div>
                    </div>

                    <div class="calendar-days">
                        @foreach ($calendarDays as $day)
                            <div class="calendar-day {{ $day['isToday'] ? 'today' : '' }} {{ $day['isWeekend'] ? 'weekend' : '' }} {{ !$day['isCurrentMonth'] ? 'other-month' : '' }} {{ $day['isPast'] ? 'past' : '' }} {{ $day['isUnavailable'] ? 'unavailable' : '' }} {{ $day['isPartiallyUnavailable'] ? 'partial' : '' }} {{ !$day['isWeekend'] && !$day['isPast'] && $day['isCurrentMonth'] ? 'clickable' : '' }}"
                                @if (!$day['isWeekend'] && !$day['isPast'] && $day['isCurrentMonth']) onclick="openSlotModal('{{ $day['date'] }}')" @endif
                                data-date="{{ $day['date'] }}">
                                <span class="day-number">{{ $day['dayNumber'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Legend --}}
                <div class="legend">
                    <div class="legend-item">
                        <div class="legend-dot available"></div>
                        <span>Available</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-dot partial"></div>
                        <span>Partial (some slots blocked)</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-dot unavailable"></div>
                        <span>Fully Blocked</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-dot weekend"></div>
                        <span>Weekend (auto-disabled)</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="instructions-card">
                <h5 class="mb-3">
                    <i class="bi bi-info-circle text-primary me-2"></i>How It Works
                </h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-3 d-flex align-items-start">
                        <i class="bi bi-cursor-fill text-success me-2 mt-1"></i>
                        <span><strong>Click a date</strong> to manage which time slots are available or blocked for that day.</span>
                    </li>
                    <li class="mb-3 d-flex align-items-start">
                        <i class="bi bi-toggle-on text-warning me-2 mt-1"></i>
                        <span><strong>Toggle individual slots</strong> to block or unblock specific time periods.</span>
                    </li>
                    <li class="mb-3 d-flex align-items-start">
                        <i class="bi bi-lock-fill text-danger me-2 mt-1"></i>
                        <span><strong>Block entire date</strong> to make all slots unavailable for that day.</span>
                    </li>
                    <li class="mb-3 d-flex align-items-start">
                        <i class="bi bi-calendar-x text-secondary me-2 mt-1"></i>
                        <span><strong>Weekends</strong> are automatically disabled.</span>
                    </li>
                    <li class="d-flex align-items-start">
                        <i class="bi bi-clock-history text-danger me-2 mt-1"></i>
                        <span><strong>Past dates</strong> cannot be changed.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Slot Management Modal --}}
    <div class="modal fade" id="slotModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-gear me-2"></i>
                        Manage Slots for <span id="slotModalDate"></span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-success mb-3">
                                <i class="bi bi-sunrise me-1"></i>Morning Slots
                            </h6>
                            <div id="morningSlots"></div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-sunset me-1"></i>Afternoon Slots
                            </h6>
                            <div id="afternoonSlots"></div>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-danger" id="btnBlockAll">
                            <i class="bi bi-lock-fill"></i> Block Entire Date
                        </button>
                        <button type="button" class="btn btn-success" id="btnUnblockAll">
                            <i class="bi bi-unlock-fill"></i> Unblock All Slots
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Toast Notification --}}
    <div class="toast-notification" id="toastNotification"></div>
@endsection

@push('scripts')
    <script>
        let currentModalDate = null;

        function openSlotModal(date) {
            currentModalDate = date;
            document.getElementById('slotModalDate').textContent = formatDate(date);
            loadSlots(date);
            const modal = new bootstrap.Modal(document.getElementById('slotModal'));
            modal.show();
        }

        function formatDate(dateStr) {
            const date = new Date(dateStr + 'T00:00:00');
            return date.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }

        function loadSlots(date) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const morningDiv = document.getElementById('morningSlots');
            const afternoonDiv = document.getElementById('afternoonSlots');

            morningDiv.innerHTML = '<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-muted"></div></div>';
            afternoonDiv.innerHTML = '<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-muted"></div></div>';

            fetch(`{{ route('counselor.availability.slots') }}?date=${date}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderSlots(data.morning, 'morning');
                        renderSlots(data.afternoon, 'afternoon');
                        updateBlockButtons(data.isFullyBlocked);
                    } else {
                        showToast(data.message || 'Unable to load slots.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Failed to load slots.', 'error');
                });
        }

        function renderSlots(slots, type) {
            const container = document.getElementById(type + 'Slots');
            container.innerHTML = '';

            slots.forEach(slot => {
                const div = document.createElement('div');
                div.className = 'slot-option ' + (slot.is_available ? 'available' : 'unavailable');
                div.innerHTML = `
                    <span class="slot-time">${slot.formatted_time}</span>
                    <span class="badge ${slot.is_available ? 'bg-success' : 'bg-danger'}">
                        ${slot.is_available ? 'Available' : 'Blocked'}
                    </span>
                `;
                div.onclick = function() {
                    toggleSlot(currentModalDate, slot.id, slot.is_available);
                };
                container.appendChild(div);
            });
        }

        function updateBlockButtons(isFullyBlocked) {
            document.getElementById('btnBlockAll').style.display = isFullyBlocked ? 'none' : 'inline-block';
            document.getElementById('btnUnblockAll').style.display = 'inline-block';
        }

        function toggleSlot(date, timeSlotId, currentlyAvailable) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('{{ route('counselor.availability.toggle-slot') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        date: date,
                        time_slot_id: timeSlotId
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadSlots(date);
                        updateCalendarDay(date, data);
                        showToast(data.message, 'success');
                    } else {
                        showToast(data.message || 'Failed to update.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Failed to update slot.', 'error');
                });
        }

        function updateCalendarDay(date, data) {
            const dayEl = document.querySelector(`.calendar-day[data-date="${date}"]`);
            if (!dayEl) return;

            if (data.all_blocked) {
                dayEl.classList.add('unavailable');
                dayEl.classList.remove('partial');
            } else if (data.all_available) {
                dayEl.classList.remove('unavailable', 'partial');
            } else {
                dayEl.classList.remove('unavailable');
                dayEl.classList.add('partial');
            }
        }

        document.getElementById('btnBlockAll').addEventListener('click', function() {
            if (!currentModalDate) return;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('{{ route('counselor.availability.toggle', [], false) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        date: currentModalDate
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadSlots(currentModalDate);
                        const dayEl = document.querySelector(`.calendar-day[data-date="${currentModalDate}"]`);
                        if (dayEl) {
                            if (data.available) {
                                dayEl.classList.remove('unavailable', 'partial');
                            } else {
                                dayEl.classList.add('unavailable');
                                dayEl.classList.remove('partial');
                            }
                        }
                        showToast(data.message, 'success');
                    } else {
                        showToast(data.message || 'Failed.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Failed to update.', 'error');
                });
        });

        document.getElementById('btnUnblockAll').addEventListener('click', function() {
            if (!currentModalDate) return;
            fetch(`{{ route('counselor.availability.slots') }}?date=${currentModalDate}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                })
                .then(r => r.json())
                .then(data => {
                    if (!data.success) return;
                    const unavailableSlots = [...data.morning, ...data.afternoon].filter(s => !s
                        .is_available);
                    if (unavailableSlots.length === 0 && !data.isFullyBlocked) {
                        showToast('All slots are already available.', 'success');
                        return;
                    }

                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    let promises = [];

                    if (data.isFullyBlocked) {
                        promises.push(fetch('{{ route('counselor.availability.toggle') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                date: currentModalDate
                            }),
                        }));
                    }

                    unavailableSlots.forEach(slot => {
                        promises.push(fetch('{{ route('counselor.availability.toggle-slot') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                date: currentModalDate,
                                time_slot_id: slot.id
                            }),
                        }));
                    });

                    Promise.all(promises).then(() => {
                        loadSlots(currentModalDate);
                        const dayEl = document.querySelector(`.calendar-day[data-date="${currentModalDate}"]`);
                        if (dayEl) {
                            dayEl.classList.remove('unavailable', 'partial');
                        }
                        showToast('All slots unblocked.', 'success');
                    }).catch(() => {
                        showToast('Failed to unblock all slots.', 'error');
                    });
                });
        });

        function showToast(message, type) {
            const toast = document.getElementById('toastNotification');
            toast.textContent = message;
            toast.className = 'toast-notification ' + type + ' show';

            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }
    </script>
@endpush
