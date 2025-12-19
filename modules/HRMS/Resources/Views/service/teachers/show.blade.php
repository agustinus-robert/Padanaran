@extends('layouts.horizontal-layout')

@section('title', 'Jadwal kerja | ')
@section('navtitle', 'Jadwal kerja')

@push('nav')
    @include('hrms::layouts.includes.navbar-hrms')
@endpush

@section('body-content')
@include('components.navbar-admin')

<div class="row container-fluid">
    <div class="card mb-4 border-0">
        <x-card-header type="{{ config('theme.default') }}">
            <h6 class="text-white">Lihat Jadwal</h6>
        </x-card-header>

        <div class="card-body shadow-sm">

            {{-- FORM --}}
            <form class="form-block"
                action="{{ route('hrms::service.teacher.schedule.update', ['schedule' => $schedule->id, 'next' => request('next')]) }}"
                method="POST">
                @csrf
                @method('PUT')

                {{-- HIDDEN --}}
                <x-input type="hidden" name="start_at" :value="$schedule->start_at"/>
                <x-input type="hidden" name="end_at" :value="$schedule->end_at"/>

                {{-- NAMA --}}
                <div class="row required mb-3">
                    <label class="col-lg-4 col-xl-3 col-form-label">
                        Nama lengkap
                    </label>
                    <div class="col-xl-8 col-xxl-6">
                        <x-input
                            type="text"
                            :value="$schedule->employee->user->name"
                            disabled
                        />
                    </div>
                </div>

                {{-- PERIODE --}}
                <div class="row required mb-3">
                    <label class="col-lg-4 col-xl-3 col-form-label">
                        Periode
                    </label>
                    <div class="col-xl-8 col-xxl-6">
                        <x-input
                            type="month"
                            :value="$schedule->period"
                            disabled
                        />
                    </div>
                </div>

                {{-- JADWAL --}}
                <div class="mb-3" style="max-height:480px;overflow-y:auto;">
                    <div class="accordion" id="datesAccordion">

                        @foreach ($allDates as $date => $lessons)

                            @php
                                $formattedDate = strftime('%A, %d %B %Y', strtotime($date));
                                $accordionId = 'accordion-'.str_replace([' ',':','.'],'-',$date);
                            @endphp

                            <div class="accordion-item">
                                <h6 class="accordion-header" id="heading-{{ $accordionId }}">
                                    <div class="accordion-button bg-light p-2" style="cursor:default;">
                                        {{ $formattedDate }}

                                        @if (empty($lessons))
                                            <span class="badge badge-soft-danger ms-2">
                                                Tidak ada Jadwal
                                            </span>
                                        @endif
                                    </div>
                                </h6>

                                @if (!empty($lessons))
                                <div id="collapse-{{ $accordionId }}"
                                    class="accordion-collapse collapse show">

                                    <div class="accordion-body">
                                        <div class="row">

                                            @foreach ($lessons as $i => $lessonItem)
                                                <div class="col-xl-{{ 12 / count($lessons) }} mb-4">
                                                    <div class="row align-items-center">

                                                        <div class="col-md-3">
                                                            <b>
                                                                {{ $lessonItem[0] }}
                                                                -
                                                                {{ $lessonItem[1] }}
                                                            </b>
                                                        </div>

                                                        <div class="col-md-9">

                                                            {{-- MATA PELAJARAN --}}
                                                            <x-select
                                                                name="dates[{{ $date }}][{{ $i }}][lesson_id]"
                                                                placeholder="Pilih Mata Pelajaran"
                                                                :value="isset($lessonItem['lesson'][0]) ? $lessonItem['lesson'][0] : null"
                                                            >
                                                                <option value="">
                                                                    Pilih Mata Pelajaran
                                                                </option>

                                                                @foreach ($gradeLevel as $grade)
                                                                    <optgroup label="Kelas {{ $grade->name }}">
                                                                        @foreach ($defaultCategoryAcademic as $acCategory)
                                                                            @foreach ($academicSubject as $subject)
                                                                                @if (
                                                                                    $subject->level_id == $grade->id &&
                                                                                    $subject->category_id == $acCategory->id
                                                                                )
                                                                                    <option
                                                                                        value="{{ $subject->id }}"
                                                                                        @selected(
                                                                                            isset($lessonItem['lesson'][0]) &&
                                                                                            $lessonItem['lesson'][0] == $subject->id
                                                                                        )
                                                                                    >
                                                                                        {{ $acCategory->name }}
                                                                                        - {{ $subject->name }}
                                                                                    </option>
                                                                                @endif
                                                                            @endforeach
                                                                        @endforeach
                                                                    </optgroup>
                                                                @endforeach

                                                            </x-select>

                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        @endforeach

                    </div>
                </div>

                {{-- ACTION --}}
                <div class="row mt-4">
                    <div class="col-lg-8 offset-lg-4 offset-xl-3">
                        <button class="btn btn-soft-danger">
                            <i class="mdi mdi-check"></i> Simpan
                        </button>
                        <a class="btn btn-ghost-light text-dark"
                        href="{{ request('next', route('hrms::service.teacher.schedule.index')) }}">
                            Kembali
                        </a>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection


@push('scripts')
    <script type="text/javascript">
        function countWorkdays() {
            document.querySelector('[name="workdays_count"]').value = 0
            jumlah = 0
            dates = @json($dates)

            dates.forEach(function(date) {
                @foreach ($workshifts as $i => $shift)
                    selects = document.querySelectorAll('.time-' + date + '-{{ $i }}')
                    selects.forEach(function(el) {
                        if (el.value && el.value.trim() !== '') {
                            jumlah++
                        }
                    })
                @endforeach
            })

            document.querySelector('[name="workdays_count"]').value = jumlah
        }

        document.addEventListener('change', function(e) {
            if (e.target.tagName === 'SELECT') {
                countWorkdays()
            }
        })

        window.addEventListener('load', function() {
            countWorkdays()
        })

        document.querySelectorAll('.mdi-pencil').forEach(icon => {
            icon.addEventListener('click', function(e) {
                e.stopPropagation(); // cegah event click ke button accordion
            });
        });
    </script>

    <script>
    function confirmChangeCategory(el) {
        const currentCategory = parseInt(el.dataset.currentCategory);
        const newCategory = currentCategory === 1 ? 2 : 1;

        if(confirm('Apakah Anda yakin merubah kategori jadwal?')) {
            const formId = 'change-category-form-' + el.dataset.date;
            const form = document.getElementById(formId);
            if (form) {
                form.querySelector('input[name="category_id"]').value = newCategory;
                form.submit();
            } else {
                alert('Form tidak ditemukan!');
            }
        }
    }
    </script>
@endpush
