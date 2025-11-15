@extends('portal::layouts.index')

@section('title', 'Jadwal kerja | ')
@section('container-type', 'container-fluid px-5')

@include('portal::components.top-bar')

@section('contents')
    {{-- <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="javascript:void(0)"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Buat jadwal kerja baru</h2>
            <div class="text-secondary">Anda dapat membuat jadwal kerja dengan mengisi formulir di bawah</div>
        </div>
    </div> --}}
    <div class="topnav">
        <div class="container-fluid">
            <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

                <div class="navbar-collapse collapse" id="topnav-menu-content">
                    <ul class="navbar-nav">

                        <li class="nav-item">
                            <a class="nav-link arrow-none" href="{{ route('portal::dashboard-msdm.index') }}" id="topnav-dashboard" role="button">
                                <i class="bx bx-home-circle me-2"></i><span key="t-dashboards">Dashboards</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link arrow-none" href="#" id="topnav-uielement" role="button">
                                <i class="bx bx-user-pin me-2"></i>
                                <span key="t-ui-elements"> Profil</span>
                            </a>
                        </li>

                    </ul>
                </div>
            </nav>
        </div>
    </div>

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                @if (Session::has('msg-sukses'))
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                        <div class="alert alert-success">
                            {{ Session::get('msg-sukses') }}
                        </div>
                    </div>
                @endif

                @if (Session::has('msg-gagal'))
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                        <div class="alert-danger alert">
                            {{ Session::get('msg-gagal') }}
                        </div>
                    </div>
                @endif

                <div class="row">
                    <div class="col-xl-12 col-sm-12">
                        <div class="card border-0">
                            <div class="card-body d-flex align-items-center justify-content-between mb-3 py-2">
                                <div><i class="mdi mdi-calendar-multiselect"></i> Jadwal kerja </div>
                            </div>

                            <!-- Calendar Section -->
                            <div class="table-responsive tg-steps-presence-calendar">
                                @php($daynames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu'])
                                <table class="table-bordered calendar mb-0 table">
                                    <thead>
                                        <tr>
                                            @foreach ($daynames as $dayname)
                                                <th class="text-center">{{ $dayname }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php($_month = $month->copy()->startOfMonth())
                                        @php($day = 1)
                                        @php($totalWeekOfMonth = $_month->dayOfWeek >= 5 && $_month->daysInMonth >= 30 ? 6 : ($_month->dayOfWeek == 0 && $_month->daysInMonth <= 28 ? 4 : 5))
                                        @for ($week = 1; $week <= $totalWeekOfMonth; $week++)
                                            <tr>
                                                @foreach ($daynames as $dayindex => $dayname)
                                                    @php($_date = date('Y-m-d', mktime(0, 0, 0, $_month->month, $day, $_month->year)))
                                                    <td data-droppable="true" class="calendar-cell" style="height: 100px; min-height: 100px; min-width: 120px;">
                                                        @if ((($week == 1 && $dayindex >= $_month->dayOfWeek) || $week > 1) && ($week != $totalWeekOfMonth || $day <= $_month->daysInMonth))
                                                            <div class="position-relative h-100 float-start">
                                                                <div class="d-flex">
                                                                    <div class="small flex-grow-1 position-absolute text-muted">{{ $day }}</div>
                                                                </div>
                                                            </div>
                                                            <div class="small mt-4">

                                                                @foreach ($workshifts as $keyShift => $workshift)
                                                                    <div class="text-muted mb-2">{{ $workshift->label() }}</div>
                                                                    <div class="input-group droppable-shift mb-3" data-shift-id="{{ $workshift->value }}" data-date="{{ $_date }}" style="border: 1px dashed #ddd; min-height: 40px;">

                                                                        @foreach ($calendarData as $entry)
                                                                            @foreach ($entry as $getEntry => $vEntry)
                                                                                @foreach ($vEntry->dates as $keyEntryDate => $valEntryDate)
                                                                                    @if ($keyEntryDate == $_date)
                                                                                        {{-- @if ($vEntry->employee->position->position->type->value == $type) --}}
                                                                                        @if (isset($valEntryDate[$keyShift]))
                                                                                            @if (!is_null($valEntryDate[$keyShift][0]))
                                                                                                <div class="d-flex align-items-center justify-content-between calendar-badge w-100 mb-2" data-date="{{ $keyEntryDate }}" data-empl_id="{{ $vEntry->empl_id }}">
                                                                                                    <div class="employee-name">{{ $vEntry->employee->user->name }}</div> <!-- You can fetch employee name here -->
                                                                                                    <i title="Validasi Absensi" class="bx bx-check-double text-primary ms-auto cursor-pointer" style="font-size: 1.2em;"></i>

                                                                                                    {{-- <i class="mdi mdi-trash-can-outline text-danger ms-auto cursor-pointer" style="font-size: 1.2em;"></i> --}}
                                                                                                </div>
                                                                                            @endif
                                                                                        @endif
                                                                                        {{-- @endif --}}
                                                                                    @endif
                                                                                @endforeach
                                                                            @endforeach
                                                                        @endforeach
                                                                    </div>
                                                                @endforeach

                                                            </div>
                                                            @php($day++)
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Employee List Section -->
                    {{-- <div class="col-xl-3 col-sm-12 fixed-column mb-3">
                        <div class="card border-0">
                            <div class="card-body fw-bold border-bottom mb-3">Daftar karyawan</div>
                            <div class="card-body">
                                @foreach ($employees as $employee)
                                    <a draggable class="btn btn-outline-secondary w-100 d-flex text-dark fc-event external-event-container count-{{ $employee->id }} @if (!$loop->last) mb-3 @endif rounded bg-white py-3 text-start" href="javascript:;" data-empl_id="{{ $employee->id }}" data-count="0">
                                        <div class="employee-name">{{ $employee->user->name }}</div>
                                        <span class="badge count-badge text-dark ms-auto" data-empl_id="{{ $employee->id }}">0</span>
                                    </a>
                                @endforeach
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('portal::schedule-teacher.manages.collective.store') }}" enctype="multipart/form-data">@csrf
                                    <input type="hidden" class="form-control d-none" name="empl_type" value="{{ request()->get('type', $label) }}">
                                    <input type="hidden" class="form-control d-none" name="schedule_month" value="{{ request()->get('month', $month->format('Y-m')) }}">
                                    <textarea hidden id="show_json" name="dates">{{ $databaseResult }}</textarea>
                                    <div class="card card-body border">
                                        <div class="form-check d-flex align-items-center mb-2">
                                            <input class="form-check-input" id="as_template" name="as_template" type="checkbox" value="1">
                                            <label class="form-check-label ms-3" for="as_template">
                                                <div>Dengan ini saya menyatakan data di atas adalah <strong>valid</strong>.</div>
                                            </label>
                                        </div>
                                    </div>
                                    <button id="save" class="btn btn-soft-danger"><i class="mdi mdi-content-save-move-outline"></i> Ajukan jadwal</button>
                                </form>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .fixed-column {
            position: fixed;
            top: 80px;
            right: 0;
            width: 25%;
            height: 100%;
            overflow-y: auto;
            z-index: 1020;
            background: #fff;
            padding: 1rem;
        }
    </style>
@endpush

@push('scripts')
    <script>
        const updateFixedColumnTop = () => {
            const navbar = document.querySelector(".navbar"); // Replace with your navbar selector
            const fixedColumn = document.querySelector(".fixed-column");
            const navbarHeight = navbar ? navbar.offsetHeight : 0;
            if (window.scrollY > navbarHeight) {
                fixedColumn.style.top = "0"; // When scrolled past the navbar
            } else {
                fixedColumn.style.top = `${navbarHeight - window.scrollY}px`; // Adjust for navbar height
            }
        };

        document.addEventListener('DOMContentLoaded', function() {
            "use strict";
            updateFixedColumnTop();
            window.addEventListener("scroll", updateFixedColumnTop);

            collectDropzoneData();

            function callCount(empl = '') {
                let jsonString = document.getElementById('show_json').value;
                let sNm = 0;
                let arr = []
                if (jsonString.length > 0) {
                    let resultData = JSON.parse(jsonString);

                    Object.keys(resultData).forEach(function(key) {
                        let employeeData = resultData[key];

                        Object.keys(employeeData).forEach(function(level2Key) {
                            var level2Data = employeeData[level2Key];
                            var numShifts = 0;

                            Object.keys(level2Data).forEach(function(dateKey) {
                                var shiftsForDate = level2Data[dateKey];

                                if (shiftsForDate && shiftsForDate.length > 0) {
                                    numShifts += shiftsForDate.length;
                                }


                            });

                            let badgeElement = document.querySelector('.badge.count-badge[data-empl_id="' + key + '"]');


                            if (badgeElement) {
                                badgeElement.textContent = numShifts;
                            }
                        });
                    });

                    if (empl) {

                        let badgeElement = document.querySelector('.badge.count-badge[data-empl_id="' + empl + '"]');

                        if (JSON.parse(jsonString)[empl] == undefined) {
                            badgeElement.textContent = 0;
                        }
                    }
                }
            }

            callCount();

            // Draggable elements setup
            document.querySelectorAll('[draggable]').forEach((el) => {
                el.addEventListener('dragstart', function(e) {
                    e.dataTransfer.effectAllowed = 'move';
                    e.dataTransfer.setData('text/plain', this.getAttribute('data-empl_id')); // Use data-empl_id
                    this.classList.add('drag');
                });

                el.addEventListener('dragend', function() {
                    this.classList.remove('drag');
                });
            });

            document.addEventListener('click', function(event) {
                if (event.target && event.target.matches('.mdi-trash-can-outline')) {
                    handleRemoveClick(event)
                }
            });

            function handleRemoveClick(event) {
                event.preventDefault();
                const calendarBadge = event.target.closest('.calendar-badge');

                if (calendarBadge) {
                    const emplId = calendarBadge.getAttribute('data-empl_id');
                    const dateKey = calendarBadge.getAttribute('data-date');
                    const shiftId = calendarBadge.getAttribute('data-shift-id');
                    collectDropzoneData();
                    const jsonString = document.getElementById('show_json').value;
                    const employeeEl = document.querySelector(`.external-event-container[data-empl_id="${emplId}"]`);

                    let resultData = {};
                    if (jsonString.length > 0) {
                        resultData = JSON.parse(jsonString);
                        Object.keys(resultData).forEach(function(key) {
                            if (key === emplId) {
                                Object.keys(resultData[key]).forEach(function(level2Key) {
                                    if (resultData[key][level2Key][dateKey]) {
                                        resultData[key][level2Key][dateKey].forEach(function(shiftEntry) {
                                            if (shiftEntry.shiftId === shiftId) {
                                                resultData[key][level2Key][dateKey] = [];
                                            }
                                        });
                                    }
                                });
                            }
                        });
                    }


                    document.getElementById('show_json').value = JSON.stringify(resultData);
                    calendarBadge.remove();
                    callCount(emplId);
                }
            }

            document.querySelectorAll('.mdi-trash-can-outline').forEach((icon) => {
                icon.addEventListener('click', handleRemoveClick);
            });

            // Droppable elements setup
            document.querySelectorAll('.droppable-shift').forEach((el) => {
                let count = 0;
                el.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    e.dataTransfer.dropEffect = 'move';
                    this.classList.add('over');
                    collectDropzoneData()
                });

                el.addEventListener('dragenter', function() {
                    this.classList.add('over');
                    collectDropzoneData()
                });

                el.addEventListener('dragleave', function() {
                    this.classList.remove('over');
                    collectDropzoneData()
                });

                el.addEventListener('drop', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    this.classList.remove('over');
                    const emplId = e.dataTransfer.getData('text/plain');
                    const employeeEl = document.querySelector(`.external-event-container[data-empl_id="${emplId}"]`);
                    if (employeeEl) {
                        const employeeName = employeeEl.querySelector('.employee-name')?.textContent || "Employee";
                        const clone = document.createElement('div');
                        console.log(employeeEl.getAttribute('data-count'));

                        // let count = parseInt(employeeEl.getAttribute('data-count'), 10) || 0;
                        // count++;

                        clone.classList.add('d-flex', 'align-items-center', 'justify-content-between', 'mb-2', 'calendar-badge', 'w-100');
                        clone.setAttribute('data-empl_id', emplId);

                        const nameEl = document.createElement('div');
                        nameEl.textContent = employeeName;
                        clone.appendChild(nameEl);

                        const removeIcon = document.createElement('i');
                        removeIcon.classList.add('mdi', 'mdi-trash-can-outline', 'text-danger', 'ms-auto', 'cursor-pointer');
                        removeIcon.style.fontSize = '1.2em';
                        removeIcon.onclick = function() {
                            clone.remove();
                        };
                        clone.appendChild(removeIcon);

                        this.appendChild(clone);
                        collectDropzoneData()
                        callCount()

                    } else {
                        console.error("Original employee element with the given ID not found.");
                    }
                    // console.log(count)
                });
            });
        });


        function collectDropzoneData() {
            const result = {};
            const selectedType = document.querySelector('option:checked').getAttribute('data-type-id');
            const dropzones = document.querySelectorAll('.droppable-shift');

            dropzones.forEach(dropzone => {
                const shiftId = dropzone.getAttribute('data-shift-id');
                const users = dropzone.getAttribute('data-empl_id');
                const date = dropzone.getAttribute('data-date');
                const employees = dropzone.querySelectorAll('.calendar-badge');

                employees.forEach(employee => {
                    const emplId = employee.getAttribute('data-empl_id');

                    if (!result[emplId]) {
                        result[emplId] = {};
                    }

                    if (!result[emplId][selectedType]) {
                        result[emplId][selectedType] = {};
                    }

                    if (!result[emplId][selectedType][date]) {
                        result[emplId][selectedType][date] = [];
                    }


                    if (shiftId) {
                        result[emplId][selectedType][date].push(shiftId);
                    }
                });
            });

            const allDates = Array.from(dropzones).map(dropzone => dropzone.getAttribute('data-date'));

            Object.keys(result).forEach(emplId => {
                Object.keys(result[emplId]).forEach(type => {
                    allDates.forEach(date => {
                        if (!result[emplId][type][date]) {
                            result[emplId][type][date] = [];
                        }
                    });
                });
            });

            const showJsonElement = document.getElementById('show_json');
            showJsonElement.value = JSON.stringify(result);
        }
    </script>
@endpush
