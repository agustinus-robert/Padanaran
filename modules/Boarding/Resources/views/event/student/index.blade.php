@extends('layouts.horizontal-layout')

@section('title', 'Kegiatan Siswa - ')

@section('navtitle', 'Kegiatan Siswa')

@section('breadcrumb')
    <li class="breadcrumb-item">Pondok</li>
    <li class="breadcrumb-item active">Kegiatan Siswa</li>
@endsection

@push('nav')
@include('boarding::layouts.includes.navbar-boarding')
@endpush

@php
$trashed = false; // Sesuaikan dengan logic controller jika sedang menampilkan trash

$columns = [
    [
        'field' => 'no', 
        'label' => 'No', 
        'slot' => fn($item, $loop) => $loop->iteration
    ],
    [
        'field' => 'modelable', 
        'label' => 'Peserta', 
        'slot' => function($item) {
            if ($item->modelable_type == 'Modules\Academic\Models\AcademicClassroom') {
                return optional($item->modelable)->name ?? '-';
            }
            return optional(optional($item->modelable)->user)->name ?? '-';
        }
    ],
    [
        'field' => 'teacher.user.name', 
        'label' => 'Guru Pengampu', 
        'slot' => fn($item) => $item->teacher->user->name ?? '-'
    ],
    [
        'field' => 'supervisor.user.name', 
        'label' => 'Guru Pengurus', 
        'slot' => fn($item) => $item->supervisor->user->name ?? '-'
    ],
    [
        'field' => 'event.name', 
        'label' => 'Kegiatan', 
        'slot' => function($item) {
            $name = $item->event->name ?? 'Kegiatan dihapus';
            $badge = '';

            if (optional($item->event)->type?->value == 2 && !empty($item->event->end_date)) {
                $today = \Carbon\Carbon::today();
                $endDate = \Carbon\Carbon::parse($item->event->end_date);

                if ($today->greaterThan($endDate)) {
                    $badge = '<p class="mb-0"><span class="badge bg-danger"><small>Selesai</small></span></p>';
                } else {
                    $badge = '<p class="mb-0"><span class="badge bg-secondary"><small>' . $endDate->format('d M Y') . '</small></span></p>';
                }
            }
            return '<div>' . $name . $badge . '</div>';
        }
    ],
    [
        'field' => 'actions', 
        'label' => '', 
        'slot' => fn($item) => view('components.partial-actions', [
            'item' => $item,
            'trashed' => $item->trashed(),
            'routes' => [
                'edit'    => 'boarding::event.event-student.edit',
                'destroy' => 'boarding::event.event-student.destroy',
                'restore' => 'boarding::facility.buildings.restore', // Sesuaikan jika ada route khusus restore event
                'kill'    => 'boarding::facility.buildings.kill',    // Sesuaikan jika ada route khusus kill event
            ],
            'params' => [
                'edit'    => ['event_student' => $item->id],
                'destroy' => ['event_student' => $item->id],
                'restore' => ['building' => $item->id],
                'kill'    => ['building' => $item->id],
            ]
        ])->render()
    ]
];
@endphp

@push('additional-content')
    <div class="card mb-3 shadow-none border">
        <div class="card-header pb-0 p-3 bg-transparent">
            <h6 class="mb-0 d-flex align-items-center text-sm">
                <i class="material-symbols-rounded me-2 text-dark">settings</i> Lanjutan
            </h6>
        </div>
        <div class="card-body p-3">
            <div class="list-group list-group-flush">
                <a href="{{ route('boarding::facility.student.index', ['trash' => request('trash', 0) ? null : 1]) }}" 
                   class="list-group-item list-group-item-action border-0 d-flex align-items-center px-0 py-2 text-xs {{ request('trash') ? 'text-dark' : 'text-danger' }}">
                    <i class="material-symbols-rounded me-2 {{ request('trash') ? 'text-dark' : 'text-danger' }} text-sm">
                        {{ request('trash') ? 'settings_backup_restore' : 'delete_sweep' }}
                    </i>
                    <span>
                        Tampilkan data yang {{ request('trash') ? 'aktif' : 'dihapus (Trash)' }}
                    </span>
                </a>
            </div>

            @if(request('trash'))
                <div class="mt-2 p-2 bg-gray-100 border-radius-sm">
                    <p class="text-xxs text-muted mb-0 d-flex align-items-center">
                        <i class="material-symbols-rounded text-xs me-1">info</i>
                        Mode: Melihat data di Tempat Sampah
                    </p>
                </div>
            @endif
        </div>
    </div>
@endpush

@section('body-content')
    @include('components.navbar-admin')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <x-table
                    type="material"
                    :data="$boardingEventStdn"
                    :columns="$columns"
                    title="Daftar Kegiatan Siswa"
                    searchRoute="{{ route('boarding::event.event-student.index', ['academic' => request('academic')]) }}"
                    :trash="$trashed"
                />
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header"><i class="mdi mdi-office-building float-left mr-2"></i>Kelola Kegiatan Siswa</div>
                    <div class="card-body">
                        <form class="form-block" action="{{ isset($editItem) ? route('boarding::event.event-student.update', ['event_student' => $editItem->id, 'next' => request()->fullUrl()]) : route('boarding::event.event-student.store', ['next' => request()->fullUrl()]) }}" method="POST">
                        @csrf
                        @if (isset($editItem))
                            @method('PUT')
                        @endif

                        {{-- Kegiatan --}}
                        <x-input-group label="Kegiatan" required>
                            @php
                                $groupedEvents = $events->groupBy(fn($event) => $event->type->value);
                            @endphp
                            <select name="event_id" id="event-select" class="form-select select-2" required>
                                <option value="">Pilih Kegiatan</option>
                                @foreach ($groupedEvents as $typeValue => $group)
                                    @php 
                                        $enumType = \Modules\Boarding\Enums\BoardingEventTypeEnum::tryFrom((int) $typeValue); 
                                    @endphp
                                    @if ($enumType)
                                        <optgroup label="{{ $enumType->label() }}">
                                            @foreach ($group as $value)
                                                <option data-participant="{{ $value->type_participant }}" value="{{ $value->id }}"
                                                    {{ (isset($editItem) && $editItem->event_id == $value->id) || old('event_id') == $value->id ? 'selected' : '' }}>
                                                    {{ $value->name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                @endforeach
                            </select>
                            <input type="hidden" name="participant_type" id="participant-type-input" value="{{ old('participant_type', $editItem->event->type_participant ?? '') }}">
                            </x-input-group>

                            {{-- Siswa --}}
                            <div id="participant-student-container" style="display:none;">
                                <x-input-group label="Siswa">
                                    <x-select id="student-select" name="student_id" class="select-2"
                                        :options="$students->map(fn($s) => ['value' => $s->id, 'label' => $s->user->profile->name])"
                                        :selected="isset($editItem) ? $editItem->modelable_id : old('student_id')"
                                        placeholder="Pilih Siswa" 
                                    />
                                </x-input-group>
                            </div>

                            {{-- Rombel --}}
                            <div id="participant-rombel-container" style="display:none; width: 100%;">
                                <x-input-group label="Rombel">
                                    <select id="participant-select" name="academic_id" class="form-select select-2">
                                        <option value="">Pilih Rombel</option>
                                    </select>
                                </x-input-group>
                            </div>

                            {{-- Guru Pengampu --}}
                            <x-input-group label="Guru Pengampu" required>
                                <x-select name="teacher_id" class="select-2" required
                                    :options="$employeeTeacher->map(fn($t) => ['value' => $t->id, 'label' => $t->user->name])"
                                    :selected="isset($editItem) ? $editItem->teacher_id : old('teacher_id')"
                                    placeholder="Pilih Guru Pengampu"
                                />
                            </x-input-group>

                            {{-- Guru Pengurus --}}
                            <x-input-group label="Pengurus" required>
                                <x-select name="supervisor_id" class="select-2" required
                                    :options="$employeeSupervisor->map(fn($s) => ['value' => $s->id, 'label' => $s->user->name])"
                                    :selected="isset($editItem) ? $editItem->supervisor_id : old('supervisor_id')"
                                    placeholder="Pilih Pengasuh"
                                />
                            </x-input-group>

                            <div class="mt-3">
                                <x-btn type="submit" variant="dark">
                                    {{ isset($editItem) ? 'Update' : 'Simpan' }}
                                </x-btn>
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
document.addEventListener('DOMContentLoaded', function () {
    $('#event-select').select2({ width: '100%' });
    $('#student-select').select2({ width: '100%' });
    $('#participant-select').select2({ width: '100%' });

    const participantTypeInput = document.getElementById('participant-type-input');

    function updateParticipantTypeInput() {
        const eventSelect = document.getElementById('event-select');
        const selectedOption = eventSelect.options[eventSelect.selectedIndex];
        const participantType = selectedOption ? selectedOption.getAttribute('data-participant') : '';
        participantTypeInput.value = participantType;
    }

    function toggleParticipantFields() {
        const eventSelect = document.getElementById('event-select');
        const selectedOption = eventSelect.options[eventSelect.selectedIndex];
        const participantType = selectedOption ? selectedOption.getAttribute('data-participant') : null;

        const studentContainer = document.getElementById('participant-student-container');
        const rombelContainer = document.getElementById('participant-rombel-container');

        if (participantType === '2') {
            rombelContainer.style.display = 'block';
            fillRombelOptions();
            $('#participant-select').prop('required', true).select2({ width: '100%' });

            studentContainer.style.display = 'none';
            $('#student-select').prop('required', false).val(null).trigger('change');
        } else if (participantType === '1') {
            studentContainer.style.display = 'block';
            $('#student-select').prop('required', true).select2({ width: '100%' });

            rombelContainer.style.display = 'none';
            $('#participant-select').prop('required', false).val(null).trigger('change');
        } else {
            studentContainer.style.display = 'none';
            $('#student-select').prop('required', false).val(null).trigger('change');

            rombelContainer.style.display = 'none';
            $('#participant-select').prop('required', false).val(null).trigger('change');
        }
    }

   const acdmcClassData = @json($acdmcClass);
    const modelableType = @json(isset($editItem) ? $editItem->modelable_type : null);
    const selectedRombelId = @json(old('academic_id', isset($editItem) ? $editItem->modelable_id : null));

    function fillRombelOptions() {
        const rombelSelect = document.getElementById('participant-select');
        rombelSelect.innerHTML = '';

        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = 'Pilih Rombel';
        rombelSelect.appendChild(defaultOption);

        // Kalau mode tambah data, atau modelable_type adalah AcademicClassroom
        if (!modelableType || modelableType === "Modules\\Academic\\Models\\AcademicClassroom") {
            acdmcClassData.forEach(function (rombel) {
                const option = document.createElement('option');
                option.value = rombel.id;
                option.textContent = rombel.name;

                if (selectedRombelId && rombel.id == selectedRombelId) {
                    option.selected = true;
                }

                rombelSelect.appendChild(option);
            });
        }

        $('#participant-select').trigger('change.select2');
    }


    $('#event-select').on('select2:select', function () {
        updateParticipantTypeInput();
        toggleParticipantFields();
    });

    // Jalankan saat load, pastikan input hidden ikut terupdate dan fields sesuai data edit
    updateParticipantTypeInput();
    toggleParticipantFields();
});


</script>
@endpush
