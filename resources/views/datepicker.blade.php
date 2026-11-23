<div class="nepali-datepicker" id="{{ $id }}-datepicker" data-year="{{ $calendar->year }}"
    data-month="{{ $calendar->month }}" data-url="{{ route('event-calendar.datepicker.calendar') }}"
    data-today-year="{{ $today->year() }}" data-today-month="{{ $today->month() }}" data-today-day="{{ $today->day() }}">
    {{-- Submitted value --}}
    <input type="hidden" name="{{ $name }}" id="{{ $id }}" value="{{ $value }}"
        @if ($required) required @endif>

    {{-- Visible input --}}
    <div class="np-input-wrapper">
        <input type="text" class="np-date-input" id="{{ $id }}-display" placeholder="{{ $placeholder }}"
            value="{{ $value ? toNepaliNumber($value) : '' }}" readonly autocomplete="off">

        <button type="button" class="np-toggle" aria-label="Open calendar">
            <i class="bi bi-calendar3"></i>
        </button>
    </div>

    {{-- Calendar --}}
    <div class="np-calendar-popup">

        <div class="np-calendar-content">
            @include('event-calendar::datepicker-calendar')
        </div>

    </div>
</div>

<style>
    .nepali-datepicker {
        position: relative;
        width: 100%;
    }

    .nepali-datepicker *,
    .nepali-datepicker *::before,
    .nepali-datepicker *::after {
        box-sizing: border-box;
    }

    .np-input-wrapper {
        display: flex;
        align-items: center;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        overflow: hidden;
        background: #fff;
    }

    .np-date-input {
        width: 100%;
        min-width: 0;
        border: none;
        outline: none;
        padding: 10px 12px;
        cursor: pointer;
        background: transparent;
    }

    .np-toggle {
        flex-shrink: 0;
        border: none;
        background: white;
        padding: 10px 14px;
        cursor: pointer;
        color: #2563eb;
    }

    .np-calendar-popup {
        display: none;
        position: absolute;
        top: calc(100% + 5px);
        left: 0;
        width: 310px;
        max-width: 100%;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 12px;
        z-index: 99999;
        box-shadow: 0 8px 25px rgba(0, 0, 0, .12);
    }

    .np-calendar-popup.show {
        display: block;
    }

    .np-calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 5px;
        margin-bottom: 12px;
    }

    .np-calendar-header>button {
        flex-shrink: 0;
        border: none;
        background: #eff6ff;
        color: #2563eb;
        width: 30px;
        height: 32px;
        border-radius: 5px;
        font-size: 20px;
        cursor: pointer;
    }

    .np-month-year {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        flex: 1;
        min-width: 0;
    }

    .np-month-select,
    .np-year-select {
        min-width: 0;
        max-width: 125px;
        border: 1px solid #e2e8f0;
        border-radius: 5px;
        padding: 6px 4px;
        font-size: 13px;
        background: white;
        color: #1e293b;
        cursor: pointer;
    }

    .np-calendar-table {
        width: 100%;
        border-collapse: collapse;
        text-align: center;
    }

    .np-calendar-table th {
        padding: 8px 2px;
        font-size: 12px;
        background: #f1f5f9;
    }

    .np-calendar-table td {
        padding: 2px;
        height: 38px;
    }

    .np-day,
    .np-empty {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        width: 34px;
        height: 34px;
    }

    .np-day {
        border: none;
        background: transparent;
        border-radius: 50%;
        cursor: pointer;
        font-size: 13px;
    }

    .np-day:hover {
        background: #dbeafe;
    }

    .np-day.today {
        border: 1px solid #2563eb;
    }

    .np-day.selected {
        background: #2563eb;
        color: white;
    }

    .np-day.holiday,
    .np-calendar-table th.holiday {
        color: #dc2626;
    }

    .np-day.selected.holiday {
        color: white;
    }

    .np-calendar-footer {
        display: flex;
        justify-content: space-between;
        margin-top: 12px;
        border-top: 1px solid #e2e8f0;
        padding-top: 10px;
    }

    .np-calendar-footer button {
        border: none;
        background: transparent;
        color: #2563eb;
        cursor: pointer;
        font-size: 13px;
    }

    .np-calendar-loading {
        opacity: .5;
        pointer-events: none;
    }

    .np-calendar-error {
        padding: 10px;
        color: #dc2626;
        font-size: 13px;
    }
</style>

<script>
    (function() {

        function initializeNepaliDatepickers() {

            document.querySelectorAll('.nepali-datepicker').forEach(function(datepicker) {

                if (datepicker.dataset.initialized === 'true') {
                    return;
                }

                datepicker.dataset.initialized = 'true';

                const hiddenInput = datepicker.querySelector('input[type="hidden"]');
                const displayInput = datepicker.querySelector('.np-date-input');
                const toggleButton = datepicker.querySelector('.np-toggle');
                const popup = datepicker.querySelector('.np-calendar-popup');
                const content = datepicker.querySelector('.np-calendar-content');

                let currentYear = parseInt(datepicker.dataset.year, 10);
                let currentMonth = parseInt(datepicker.dataset.month, 10);
                let requestNumber = 0;

                function toNepaliDigits(value) {
                    return String(value).replace(
                        /\d/g,
                        digit => '०१२३४५६७८९' [digit]
                    );
                }

                function formatDate(date) {
                    return toNepaliDigits(date);
                }

                function openCalendar() {
                    popup.classList.add('show');
                }

                function closeCalendar() {
                    popup.classList.remove('show');
                }

                async function loadCalendar(year, month) {

                    const thisRequest = ++requestNumber;

                    content.classList.add('np-calendar-loading');

                    const url = new URL(datepicker.dataset.url, window.location.origin);

                    url.searchParams.set('year', year);
                    url.searchParams.set('month', month);
                    url.searchParams.set('value', hiddenInput.value || '');

                    try {

                        const response = await fetch(url, {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (!response.ok) {
                            throw new Error('Unable to load calendar.');
                        }

                        const result = await response.json();

                        if (thisRequest !== requestNumber) {
                            return;
                        }

                        content.innerHTML = result.html;

                        currentYear = year;
                        currentMonth = month;

                        datepicker.dataset.year = year;
                        datepicker.dataset.month = month;

                    } catch (error) {

                        content.innerHTML =
                            '<div class="np-calendar-error">क्यालेन्डर लोड गर्न सकिएन। पुनः प्रयास गर्नुहोस्।</div>';

                        console.error(error);

                    } finally {

                        if (thisRequest === requestNumber) {
                            content.classList.remove('np-calendar-loading');
                        }
                    }
                }

                function changeMonth(offset) {

                    let year = currentYear;
                    let month = currentMonth + offset;

                    if (month > 12) {
                        month = 1;
                        year++;
                    }

                    if (month < 1) {
                        month = 12;
                        year--;
                    }

                    loadCalendar(year, month);
                }

                function selectDate(date) {

                    hiddenInput.value = date;
                    displayInput.value = formatDate(date);

                    // Update selected styling.
                    content.querySelectorAll('.np-day.selected').forEach(function(button) {
                        button.classList.remove('selected');
                    });

                    const selectedButton = content.querySelector(
                        '.np-day[data-date="' + date + '"]'
                    );

                    if (selectedButton) {
                        selectedButton.classList.add('selected');
                    }

                    closeCalendar();

                    hiddenInput.dispatchEvent(new Event('change', {
                        bubbles: true
                    }));
                }

                // Open and close.
                displayInput.addEventListener('click', openCalendar);

                toggleButton.addEventListener('click', function(event) {
                    event.stopPropagation();
                    popup.classList.toggle('show');
                });

                // Handle calendar interactions using event delegation.
                content.addEventListener('click', function(event) {

                    const previous = event.target.closest('.np-prev');
                    const next = event.target.closest('.np-next');
                    const day = event.target.closest('.np-day');
                    const todayButton = event.target.closest('.np-today');
                    const clearButton = event.target.closest('.np-clear');

                    if (previous) {
                        changeMonth(-1);
                        return;
                    }

                    if (next) {
                        changeMonth(1);
                        return;
                    }

                    if (day) {
                        selectDate(day.dataset.date);
                        return;
                    }

                    if (todayButton) {

                        const year = parseInt(datepicker.dataset.todayYear, 10);
                        const month = parseInt(datepicker.dataset.todayMonth, 10);
                        const dayNumber = parseInt(datepicker.dataset.todayDay, 10);

                        const todayDate =
                            year + '-' +
                            String(month).padStart(2, '0') + '-' +
                            String(dayNumber).padStart(2, '0');

                        if (year !== currentYear || month !== currentMonth) {
                            loadCalendar(year, month).then(function() {
                                const today = content.querySelector(
                                    '.np-day[data-date="' + todayDate + '"]'
                                );

                                if (today) {
                                    selectDate(todayDate);
                                }
                            });
                        } else {
                            selectDate(todayDate);
                        }

                        return;
                    }

                    if (clearButton) {

                        hiddenInput.value = '';
                        displayInput.value = '';

                        content.querySelectorAll('.np-day.selected').forEach(function(button) {
                            button.classList.remove('selected');
                        });

                        closeCalendar();

                        hiddenInput.dispatchEvent(new Event('change', {
                            bubbles: true
                        }));
                    }
                });

                // Month dropdown.
                content.addEventListener('change', function(event) {

                    if (event.target.matches('.np-month-select')) {
                        loadCalendar(
                            currentYear,
                            parseInt(event.target.value, 10)
                        );
                    }

                    if (event.target.matches('.np-year-select')) {
                        loadCalendar(
                            parseInt(event.target.value, 10),
                            currentMonth
                        );
                    }
                });

                // Close when clicking outside.
                document.addEventListener('click', function(event) {
                    if (!datepicker.contains(event.target)) {
                        closeCalendar();
                    }
                });

                // Close with Escape.
                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape') {
                        closeCalendar();
                    }
                });

            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeNepaliDatepickers);
        } else {
            initializeNepaliDatepickers();
        }

    })();
</script>
