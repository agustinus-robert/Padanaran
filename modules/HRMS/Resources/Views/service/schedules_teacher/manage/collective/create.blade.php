@extends('hrms::layouts.default')

@section('title', 'Jadwal kerja | ')
@section('container-type', 'container-fluid px-5')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href=""><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Kelola jadwal piket</h2>
            <div class="text-secondary">Anda dapat membuat jadwal piket dengan mengisi formulir di bawah</div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-sm-12">
            <div class="card border-0">
                {{-- <div class="card-body d-flex align-items-center justify-content-between py-2">
                    <div><i class="mdi mdi-calendar-multiselect"></i> Jadwal kerja </div>
                    <form class="tg-steps-presence-history" action="{{ route('portal::schedule-teacher.manages.collective') }}" method="GET">
                        <div class="input-group input-group-sm">
                            <select name="type" id="type" class="form-select">
                                <option value="">-- Pilih --</option>
                                <option {{ request()->get('type', $label) == 'teacher' ? 'selected' : '' }} data-type-id="2" value="teacher">Pengajar</option>
                                <option {{ request()->get('type', $label) == 'nonstaf' ? 'selected' : '' }} data-type-id="3" value="nonstaf">Pramu kantor</option>
                                <option {{ request()->get('type', $label) == 'security' ? 'selected' : '' }} data-type-id="4" value="security">Satpam</option>
                                <option {{ request()->get('type', $label) == 'driver' ? 'selected' : '' }} data-type-id="5" value="driver">Sopir</option>
                            </select>
                            <input class="form-control" type="month" name="month" value="{{ $month->format('Y-m') }}">
                            <button class="btn btn-dark"><i class="mdi mdi-eye-outline"></i> <span class="d-none d-sm-inline">Tampilkan</span></button>
                        </div>
                    </form>
                </div> --}}

                <!-- Calendar Section -->
                    <div class="table-responsive tg-steps-presence-calendar">
                        @php

                            \Carbon::setLocale('id');

                            $startFormatted = \Carbon::parse($start_at)->translatedFormat('d F Y');
                            $endFormatted = \Carbon::parse($end_at)->translatedFormat('d F Y');

                            $now = now(); 
                            $monthStart = $now->copy()->startOfMonth()->startOfWeek(Carbon::MONDAY); 
                            $currentWeekStart = $now->copy()->startOfWeek(Carbon::MONDAY);

                            $weekNumber = $monthStart->diffInWeeks($currentWeekStart) + 1;
                            $startOfWeek = $now->copy()->startOfWeek(Carbon::MONDAY);
                            $endOfWeek = $now->copy()->endOfWeek(Carbon::SUNDAY);
                        @endphp


                        <div class="container mb-4">
                            <input class="form-control" type="hidden" name="start_at" value="{{ $start_at }}" required>
                            `<input class="form-control" type="hidden" name="end_at" value="{{ $end_at }}" required>
                            {{-- <div class="col-6 mx-auto mb-3 mt-3">
                                <form class="form-block" action="{{ route('hrms::service.teacher.duty.create') }}" method="get">
                                    <div class="mb-3">
                                        <div class="input-group">
                                            <button type="button" class="btn btn-light dropdown-toggle" data-daterangepicker="true" data-daterangepicker-start="[name='start_at']" data-daterangepicker-end="[name='end_at']">
                                                <span class="d-inline d-sm-none"><i class="mdi mdi-sort-clock-descending-outline"></i></span>
                                                <span class="d-none d-sm-inline">Rentang waktu</span>
                                            </button>
                                            <input class="form-control" type="date" name="start_at" value="{{ $start_at }}" required>
                                            <input class="form-control" type="date" name="end_at" value="{{ $end_at }}" required>
                                        </div>
                                    </div>

                                    <p class="text-center"><input type="checkbox" name="is_active" {{ request('is_active') ? 'checked' : '' }} /> Centang jika ingin mematikan jadwal pegawai berdasarkan periode yang sudah ada sebelumnya</p>
                                    
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">Terapkan</button>
                                        <a href="{{ route('hrms::service.teacher.duty.create') }}" class="btn btn-secondary">Clear</a>
                                    </div>
                                </form>
                            </div> --}}

                        <h4 class="text-center mt-2">
                            @if(request('is_active') == 1)
                                Penghapusan Jadwal Piket
                            @else
                                Penambahan Jadwal Piket
                            @endif
                        </h4>
                        @if(request('start_at') && request('start_at'))
                            <p class="mb-2 text-center">Periode penjadwalan di set dari <b>{{ $startFormatted }}</b> sampai <b>{{ $endFormatted }}</b></p>
                        @endif

                        @if(!request('start_at') && !request('end_at'))
                            <h4 class="mb-2 p-3 text-center">Jadwal Mingguan Guru Piket <span class="badge badge-pill badge-soft-success font-size-11">Minggu ke-{{ $weekNumber }}</span>
                            </h4>
                        @endif

                        @php($daynames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu'])

                        @if (Session::has('success'))
                            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                                <div class="alert alert-success">
                                    {!! Session::get('success') !!}
                                </div>
                            </div>
                        @endif 

                        @if (Session::has('danger'))
                            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                                <div class="alert-danger alert">
                                    {!! Session::get('danger') !!}
                                </div>
                            </div>
                        @endif

                        <table class="table-bordered calendar mb-0 table">
                            <thead>
                                <tr>
                                    @foreach ($daynames as $dayname)
                                        <th class="text-center"><b>{{ $dayname }}</b></th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @php($daynames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu'])
                                @php($types = [1 => 'Putri', 2 => 'Putra'])
                                
                                @foreach ($types as $keyType => $label)
                                    <tr>
                                        <td colspan="{{ count($daynames) }}">
                                            <h5 class="mt-4 text-center">Piket {{ $label }}</h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        @foreach ($daynames as $dayIndex => $dayname)
                                            @php($_date = $startDate->copy()->addDays($dayIndex))
                                            <td style="height: 100px; min-height: 100px; min-width: 120px;">

                                                @foreach ($workshifts as $keyShift => $workshift)
                                        
                                                <input type="hidden" class="room-value" value="{{ $keyType }}" />

                                                    <div class="position-relative h-100 float-start">
                                                        <div class="d-flex">
                                                            <div class="small flex-grow-1 position-absolute text-muted" style="visibility: hidden;">
                                                                {{ $_date->day }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="small mt-4">
                                                            <div class="text-muted mb-2 text-center">

                                                                @if(request('is_active'))
                                                                    <form method="POST" action="{{ route('hrms::service.teacher.teacher.duty.sch_destroy') }}">
                                                                        @csrf
                                                                        <input type="hidden" name="start_at" value="{{ $start_at }}" />
                                                                        <input type="hidden" name="end_at" value="{{ $end_at }}" />

                                                                        @foreach ($calendarData as $entry)
                                                                            @foreach ($entry as $getEntry => $vEntry)
                                                                                @foreach ($vEntry->dates as $keyEntryDate => $valEntryDate)
                                                                                    @if ($keyEntryDate == $_date->toDateString())
                                                                                        @if ($vEntry->employee->position->position->type->value == $keyType)
                                                                                            @if (isset($valEntryDate[$keyShift]) && !is_null($valEntryDate[$keyShift][0]))
                                                                                                <input type="hidden" name="emp_id_arr[]" value="{{ $vEntry->empl_id }}" />
                                                                                            @endif
                                                                                        @endif
                                                                                    @endif
                                                                                @endforeach
                                                                            @endforeach
                                                                        @endforeach

                                                                        <input type="hidden" name="day" value="{{ $_date->format('l') }}" />
                                                                        <button type="submit" class="btn btn-link p-0 border-0 text-danger delete-btn" onclick="return confirm('Apakah Anda yakin ingin menghapus semua jadwal ini?')">
                                                                            <i class="mdi mdi-trash-can-outline cursor-pointer" style="font-size: 1.2em;"></i>
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            </div>

                                                                <div class="input-group mb-3" data-shift-id="{{ $workshift->value }}" data-date="{{ $dayIndex }}">
                                                            
                                                                    @php($displayed = [])                               

                                                                    @foreach ($calendarData as $entry)
                                                                        @foreach ($entry as $getEntry => $vEntry)
                                                                            @foreach ($vEntry->dates as $keyEntryDate => $valEntryDate)
                                                                                @php($carbonDate = \Carbon\Carbon::parse($keyEntryDate))
                                                                                @php($dayOfWeekName = $daynames[$carbonDate->dayOfWeek])
                                                                                @php($emplDayKey = $vEntry->empl_id . '_' . $dayOfWeekName)
                                                                               

                                                                                @if ($dayOfWeekName == $dayname)
                                                                                    @if ($vEntry->employee->position->position->type->value == $type)
                                                                                        @if (
                                                                                            isset($valEntryDate[2]) &&
                                                                                            in_array($keyType, $valEntryDate[2])
                                                                                        )
                                                                                            
                                                                                            @if (!in_array($emplDayKey, $displayed))
                                                                                                @php($displayed[] = $emplDayKey)
                                                                                                
                                                                                                    <div style="border:1px dotted gray;" class="p-2 d-flex align-items-start justify-content-between w-100 mb-2" data-date="{{ $dayIndex }}" data-empl_id="{{ $vEntry->empl_id }}">

                                                                                                        <div>
                                                                                                            <div class="employee-name fw-bold">{{ $vEntry->employee->user->name }}</div>
                                                                                                            <div class="mt-1">
                                                                                                                @foreach($valEntryDate[2] ?? [] as $key => $value)
                                                                                                                    @php($labelClass = ($loop->index % 2 === 0) ? 'primary' : 'success')
                                                                                                                    <span class="badge bg-{{ $labelClass }} me-1">
                                                                                                                        Shift ke-{{ $value }}
                                                                                                                    </span>
                                                                                                                @endforeach
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                {{-- </a> --}}
                                                                                            @endif

                                                                                        @endif
                                                                                    @endif
                                                                                @endif
                                                                            @endforeach
                                                                        @endforeach
                                                                    @endforeach


                                                                    {{-- <a href="javascript:void(0)" class="openShiftModal d-flex align-items-start justify-content-between w-100 mb-2" data-date="{{ $dayIndex }}" data-bs-toggle="modal" data-bs-target="#shiftModal">

                                                                        <div style="border:1px dotted gray; min-height: 40px;" class="p-2 d-flex align-items-start justify-content-between w-100 mb-2" data-date="{{ $dayIndex }}" data-empl_id="{{ $vEntry->empl_id }}">

                                                                            <div>
                                                                                
                                                                            </div>
                                                                        </div>
                                                                    </a> --}}

                                                                </div>
                                                            <a href="javascript:void(0)" class="openShiftModal d-flex align-items-start justify-content-between w-100 mb-2" data-date="{{ $dayIndex }}" data-bs-toggle="modal" data-bs-target="#shiftModal">

                                                                <div style="border:1px dotted gray; min-height: 40px;" class="p-2 d-flex align-items-start justify-content-between w-100 mb-2" data-date="{{ $dayIndex }}">

                                                                    <div>
                                                                        
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Employee List Section -->
        {{-- <div class="col-xl-3 col-sm-12 mb-3">
            <div class="card border-0">
                <div class="card-body fw-bold border-bottom mb-3">Daftar guru</div>
                <div class="card-body">
                    @foreach ($employees as $employee)
                        <a draggable class="btn btn-outline-secondary w-100 d-flex text-dark fc-event external-event-container count-{{ $employee->id }} @if (!$loop->last) mb-3 @endif rounded bg-white py-3 text-start" href="javascript:;" data-empl_id="{{ $employee->id }}" data-count="0">
                            <div class="employee-name">{{ $employee->user->name }}</div>
                            <span class="badge count-badge text-dark ms-auto" data-empl_id="{{ $employee->id }}">0</span>
                        </a>
                    @endforeach
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('hrms::service.teacher.duty.store') }}" enctype="multipart/form-data">@csrf
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
                        <button id="save" class="btn btn-soft-danger"><i class="mdi mdi-content-save-move-outline"></i> Simpan</button>
                    </form>
                </div>
            </div>
        </div> --}}
    </div>
@endsection

<!-- MODAL HTML -->
<div class="modal fade" id="shiftModal" tabindex="-1" aria-labelledby="shiftModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shiftModalLabel">Tambah Shift</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="mbody">
                {{-- Konten akan dimuat via AJAX --}}
                @include('hrms::service.schedules_teacher.manage.collective.modal');
            </div>
        </div>
    </div>
</div>

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

document.addEventListener('DOMContentLoaded', function () {
        let selectedDate = '';
        let selectedRoomId = '';

    // Tangkap semua link yang membuka modal
    document.querySelectorAll('.openShiftModal').forEach(link => {
        link.addEventListener('click', function () {
            selectedDate = this.getAttribute('data-date');
            const roomInput = this.closest('td').querySelector('.room-value');
            selectedRoomId = roomInput ? roomInput.value : '';

            let roomText = '';
            if (selectedRoomId == '1') {
                roomText = 'putri';
            } else if (selectedRoomId == '2') {
                roomText = 'putra';
            }

            document.getElementById('showRoom').textContent = roomText;

            // Reset dropdown select sebelum render
            const empSelect = document.getElementById('emp_change');
            const shiftSelect = document.getElementById('emp_shift');
            const showJson = document.getElementById('show_json');

            if (empSelect) empSelect.selectedIndex = 0;
            if (shiftSelect) shiftSelect.selectedIndex = 0;
            if (showJson) showJson.value = ''; // kosongkan JSON

            renderJS(selectedRoomId, selectedDate);
        });
    });
});
</script>

<script>
    function renderJS(room, dateInput) {
  const empSelect = document.getElementById('emp_change');
const shiftSelect = document.getElementById('emp_shift');
const showJson = document.getElementById('show_json');

let selectedEmpId = null;
let selectedShiftId = null;
let selectedRoomId = parseInt(room);
let selectedDayIndex = parseInt(dateInput); // 0 = Minggu, 1 = Senin, ..., 6 = Sabtu

let result = {};

const updateScheduleResult = () => {
    selectedEmpId = empSelect.value;
    selectedShiftId = shiftSelect.value;

    const startAt = document.querySelector("input[name='start_at']").value;
    const endAt = document.querySelector("input[name='end_at']").value;

    if (!selectedEmpId || !selectedShiftId || isNaN(selectedDayIndex) || !startAt || !endAt) {
        showJson.value = '';
        return;
    }

    const start = new Date(startAt);
    const end = new Date(endAt);

    result = {};
    result[selectedEmpId] = {};

    for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
        const current = new Date(d); // buat salinan, jangan ubah `d` langsung
        if (current.getDay() !== selectedDayIndex) continue;

        const yyyy = current.getFullYear();
        const mm = String(current.getMonth() + 1).padStart(2, '0');
        const dd = String(current.getDate()).padStart(2, '0');
        const formattedDate = `${yyyy}-${mm}-${dd}`;
        const groupKey = `${yyyy}-${mm}`;

        if (!result[selectedEmpId][groupKey]) {
            result[selectedEmpId][groupKey] = {};
        }

        if (!result[selectedEmpId][groupKey][formattedDate]) {
            result[selectedEmpId][groupKey][formattedDate] = {
                in: "07:00",
                out: "16:00",
                shift: {}
            };
        }

        let shiftIds = [];
        if (selectedShiftId === "all") {
            shiftIds = Array.from(shiftSelect.options)
                .filter(opt => opt.value !== "all")
                .map(opt => parseInt(opt.value));
        } else {
            shiftIds = [parseInt(selectedShiftId)];
        }

        shiftIds.forEach(shift => {
            result[selectedEmpId][groupKey][formattedDate].shift[shift] = selectedRoomId;
        });
    }

    showJson.value = JSON.stringify(result, null, 2);
};

empSelect.addEventListener('change', updateScheduleResult);
shiftSelect.addEventListener('change', updateScheduleResult);

updateScheduleResult();


    // updateFixedColumnTop();






    // window.addEventListener("scroll", updateFixedColumnTop);
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

            // document.querySelectorAll('.mdi-trash-can-outline').forEach((icon) => {
            //     icon.addEventListener('click', handleRemoveClick);
            // });

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
    }
    </script>
@endpush
