<div class="nepali-datepicker" id="{{ $id }}-datepicker" data-year="{{ $calendar->year }}"
    data-month="{{ $calendar->month }}" data-url="{{ route('event-calendar.datepicker.calendar') }}"
    data-today-year="{{ $today->year() }}" data-today-month="{{ $today->month() }}" data-today-day="{{ $today->day() }}">

    {{-- Submitted value: AD date --}}
    <input type="hidden" name="{{ $name }}" id="{{ $id }}" value="{{ $value }}"
        @if ($required) required @endif>

    {{-- Visible input --}}
    <div class="np-input-wrapper">
        <input type="text" class="np-date-input {{ $attributes->get('class', '') }}"
            id="{{ $id }}-display" placeholder="{{ $placeholder }}"
            value="{{ $value ? toNepaliNumber($value) : '' }}" readonly autocomplete="off"
            @if ($required) aria-required="true" @endif>

        <button type="button" class="np-toggle" aria-label="Open calendar">
            <i class="bi bi-calendar3"></i>
        </button>
    </div>

    {{-- Calendar popup --}}
    <div class="np-calendar-popup">
        <div class="np-calendar-content">
            @include('event-calendar::datepicker-calendar')
        </div>
    </div>

</div>

<style>
    /* =========================================
       Main wrapper
    ========================================= */

    .nepali-datepicker {
        position: relative;
        width: 100%;
        min-width: 0;
    }

    .nepali-datepicker *,
    .nepali-datepicker *::before,
    .nepali-datepicker *::after {
        box-sizing: border-box;
    }

    /* =========================================
       Input styling - Bootstrap 4 compatible
    ========================================= */

    .nepali-datepicker .np-input-wrapper {
        display: flex;
        align-items: stretch;
        width: 100%;
        min-width: 0;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        background-color: #fff;
        overflow: hidden;
        transition: border-color .15s ease-in-out,
            box-shadow .15s ease-in-out;
    }

    .nepali-datepicker .np-date-input {
        display: block;
        flex: 1 1 auto;
        width: 1%;
        min-width: 0;
        height: auto;
        margin: 0;
        padding: 0.375rem 0.75rem;
        font-family: inherit;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-image: none;
        border: 0 !important;
        border-radius: 0;
        outline: none !important;
        box-shadow: none !important;
        cursor: pointer;
        appearance: none;
    }

    .nepali-datepicker .np-date-input::placeholder {
        color: #6c757d;
        opacity: 1;
    }

    /* Small Bootstrap input */
    .nepali-datepicker .np-date-input.form-control-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
    }

    /* Large Bootstrap input */
    .nepali-datepicker .np-date-input.form-control-lg {
        padding: 0.5rem 1rem;
        font-size: 1.25rem;
        line-height: 1.5;
    }

    /* Focus */
    .nepali-datepicker .np-input-wrapper:focus-within {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
    }

    /* Calendar button */
    .nepali-datepicker .np-toggle {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
        padding: 0.375rem 0.75rem;
        margin: 0;
        border: 0;
        /* border-left: 1px solid #ced4da; */
        border-radius: 0;
        background: white;
        color: #495057;
        font-size: 1rem;
        line-height: 1.5;
        cursor: pointer;
        outline: none;
    }

    .nepali-datepicker .np-toggle:hover {
        /* background: #e9ecef; */
        color: blue;
    }

    .nepali-datepicker .np-toggle:focus {
        outline: none;
    }

    /* =========================================
       Calendar popup
    ========================================= */

    .nepali-datepicker .np-calendar-popup {
        display: none;
        position: absolute;
        top: calc(100% + 5px);
        left: 0;
        right: auto;
        width: 310px;
        min-width: 310px;
        max-width: calc(100vw - 16px);
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 12px;
        z-index: 999999;
        box-shadow: 0 8px 25px rgba(0, 0, 0, .12);
    }

    .nepali-datepicker .np-calendar-popup.show {
        display: block;
    }

    .nepali-datepicker .np-calendar-content {
        width: 100%;
        min-width: 0;
    }

    /* =========================================
       Calendar header
    ========================================= */

    .nepali-datepicker .np-calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 5px;
        margin-bottom: 12px;
    }

    .nepali-datepicker .np-calendar-header>button {
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

    .nepali-datepicker .np-month-year {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        flex: 1;
        min-width: 0;
    }

    .nepali-datepicker .np-month-select,
    .nepali-datepicker .np-year-select {
        min-width: 0;
        max-width: 125px;
        border: 1px solid #e2e8f0;
        border-radius: 5px;
        padding: 6px 4px;
        font-size: 13px;
        background: #fff;
        color: #1e293b;
        cursor: pointer;
    }

    /* =========================================
       Calendar table
    ========================================= */

    .nepali-datepicker .np-calendar-table {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
        text-align: center;
    }

    .nepali-datepicker .np-calendar-table th {
        padding: 8px 2px;
        font-size: 12px;
        background: #f1f5f9;
    }

    .nepali-datepicker .np-calendar-table td {
        padding: 2px;
        height: 38px;
    }

    .nepali-datepicker .np-day,
    .nepali-datepicker .np-empty {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        width: 34px;
        height: 34px;
    }

    .nepali-datepicker .np-day {
        border: none;
        background: transparent;
        border-radius: 50%;
        cursor: pointer;
        font-size: 13px;
    }

    .nepali-datepicker .np-day:hover {
        background: #dbeafe;
    }

    .nepali-datepicker .np-day.today {
        border: 1px solid #2563eb;
    }

    .nepali-datepicker .np-day.selected {
        background: #2563eb;
        color: #fff;
    }

    /* Holidays */
    .nepali-datepicker .np-day.holiday,
    .nepali-datepicker .np-calendar-table th.holiday {
        color: #dc2626;
    }

    .nepali-datepicker .np-day.selected.holiday {
        color: #fff;
    }

    /* =========================================
       Calendar footer
    ========================================= */

    .nepali-datepicker .np-calendar-footer {
        display: flex;
        justify-content: space-between;
        margin-top: 12px;
        border-top: 1px solid #e2e8f0;
        padding-top: 10px;
    }

    .nepali-datepicker .np-calendar-footer button {
        border: none;
        background: transparent;
        color: #2563eb;
        cursor: pointer;
        font-size: 13px;
    }

    /* =========================================
       Loading and error
    ========================================= */

    .nepali-datepicker .np-calendar-loading {
        opacity: .5;
        pointer-events: none;
    }

    .nepali-datepicker .np-calendar-error {
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

                /* Position popup */

                function positionCalendar() {

                    popup.style.left = '0';
                    popup.style.right = 'auto';

                    const inputRect = datepicker.getBoundingClientRect();

                    const popupWidth = Math.min(
                        310,
                        window.innerWidth - 16
                    );

                    popup.style.width = popupWidth + 'px';

                    const availableRight = window.innerWidth - inputRect.left;

                    if (availableRight < popupWidth) {
                        popup.style.left = 'auto';
                        popup.style.right = '0';
                    } else {
                        popup.style.left = '0';
                        popup.style.right = 'auto';
                    }
                }

                function openCalendar() {
                    popup.classList.add('show');
                    positionCalendar();
                }

                function closeCalendar() {
                    popup.classList.remove('show');
                }

                /* Load calendar using AJAX */

                async function loadCalendar(year, month) {

                    const thisRequest = ++requestNumber;

                    content.classList.add('np-calendar-loading');

                    const url = new URL(
                        datepicker.dataset.url,
                        window.location.origin
                    );

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

                        if (thisRequest !== requestNumber) {
                            return;
                        }

                        content.innerHTML =
                            '<div class="np-calendar-error">क्यालेन्डर लोड गर्न सकिएन। पुनः प्रयास गर्नुहोस्।</div>';

                        console.error(error);

                    } finally {

                        if (thisRequest === requestNumber) {
                            content.classList.remove('np-calendar-loading');
                        }
                    }
                }

                /* Change month */

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

                /* Select date */

                function selectDate(date) {

                    hiddenInput.value = date;
                    displayInput.value = formatDate(date);

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

                /* Open and close calendar */

                displayInput.addEventListener('click', function() {

                    if (popup.classList.contains('show')) {
                        closeCalendar();
                    } else {
                        openCalendar();
                    }

                });

                toggleButton.addEventListener('click', function(event) {

                    event.stopPropagation();

                    if (popup.classList.contains('show')) {
                        closeCalendar();
                    } else {
                        openCalendar();
                    }

                });

                /* Calendar interactions */

                content.addEventListener('click', function(event) {

                    const previous = event.target.closest('.np-prev');
                    const next = event.target.closest('.np-next');
                    const day = event.target.closest('.np-day');
                    const todayButton = event.target.closest('.np-today');
                    const clearButton = event.target.closest('.np-clear');

                    if (previous) {
                        event.preventDefault();
                        changeMonth(-1);
                        return;
                    }

                    if (next) {
                        event.preventDefault();
                        changeMonth(1);
                        return;
                    }

                    if (day) {
                        event.preventDefault();
                        selectDate(day.dataset.date);
                        return;
                    }

                    /* Today button */

                    if (todayButton) {

                        event.preventDefault();

                        const year = parseInt(datepicker.dataset.todayYear, 10);
                        const month = parseInt(datepicker.dataset.todayMonth, 10);
                        const dayNumber = parseInt(datepicker.dataset.todayDay, 10);

                        const todayDate =
                            year + '-' +
                            String(month).padStart(2, '0') + '-' +
                            String(dayNumber).padStart(2, '0');

                        if (year !== currentYear || month !== currentMonth) {

                            loadCalendar(year, month).then(function() {

                                if (year !== currentYear || month !== currentMonth) {
                                    return;
                                }

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

                    /* Clear button */

                    if (clearButton) {

                        event.preventDefault();

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

                /* Month and year dropdown */

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

                /* Close when clicking outside */

                document.addEventListener('click', function(event) {

                    if (!datepicker.contains(event.target)) {
                        closeCalendar();
                    }

                });

                /* Close with Escape */

                document.addEventListener('keydown', function(event) {

                    if (event.key === 'Escape') {
                        closeCalendar();
                    }

                });

                /* Reposition on resize */

                window.addEventListener('resize', function() {

                    if (popup.classList.contains('show')) {
                        positionCalendar();
                    }

                });

                /* Reposition on scroll */

                window.addEventListener('scroll', function() {

                    if (popup.classList.contains('show')) {
                        positionCalendar();
                    }

                }, true);

            });
        }

        /* Initialize */

        if (document.readyState === 'loading') {

            document.addEventListener(
                'DOMContentLoaded',
                initializeNepaliDatepickers
            );

        } else {

            initializeNepaliDatepickers();

        }

    })();
</script>
