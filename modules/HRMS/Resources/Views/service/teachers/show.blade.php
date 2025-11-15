@extends('hrms::layouts.default')

@section('title', 'Jadwal kerja | ')
@section('navtitle', 'Jadwal kerja')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('hrms::service.teacher.schedule.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Ubah jadwal kerja</h2>
            <div class="text-secondary">Anda dapat membuat jadwal kerja dengan mengisi formulir di bawah</div>
        </div>
    </div>
    <div class="card mb-4 border-0">
        <div class="card-body">
            {{-- <form class="form-block" action="{{ route('hrms::service.teacher.schedule.update', ['schedule' => $schedule->id, 'next' => request('next')]) }}" method="POST"> @csrf @method('PUT') --}}
                <input type="hidden" name="start_at" value="{{ $schedule->start_at }}" />
                <input type="hidden" name="start_at" value="{{ $schedule->end_at }}" />

                <div class="row required mb-3">
                    <label class="col-lg-4 col-xl-3 col-form-label">Nama lengkap</label>
                    <div class="col-xl-8 col-xxl-6">
                        <input type="text" class="form-control" value="{{ $schedule->employee->user->name }}" disabled />
                    </div>
                </div>
                <div class="row required mb-3">
                    <label class="col-lg-4 col-xl-3 col-form-label">Periode</label>
                    <div class="col-xl-8 col-xxl-6">
                        {{-- {{  }} --}}
                        {{-- <input type="month" class="form-control" value="{{ $schedule->period }}" disabled /> --}}
                    </div>
                </div>
           
                <div class="mb-3" style="max-height: 480px; overflow-y: auto;">
                    <div class="row d-none d-lg-block sticky-top bg-white pb-3">
                        <div class="col-xl-9 offset-lg-4 offset-xl-3">
                            <div class="row">
                                {{-- @foreach ($workshifts as $shift)
                                    <div class="col-xl-{{ 12 / count($workshifts) }} fw-bold text-center">{{ $shift->label() }}</div>
                                @endforeach --}}
                            </div>
                        </div>
                    </div>

                    <div class="accordion" id="datesAccordion">
                        @foreach ($allDates as $date => $lessons)
                            @php
                                $formattedDate = strftime('%A, %d %B %Y', strtotime($date));
                                $accordionId = 'accordion-' . str_replace([' ', ':', '.'], '-', $date);
                            @endphp

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading-{{ $accordionId }}">
                                    <div class="accordion-button bg-light" style="cursor: default;">
                                        {{ $formattedDate }}
                                        @if (empty($lessons))
                                            <span class="badge badge-pill badge-soft-danger font-size-11 ms-2">Tidak ada Jadwal</span>
                                        @endif
                                    </div>
                                </h2>

                                @if (!empty($lessons))
                                    <div id="collapse-{{ $accordionId }}" class="accordion-collapse collapse show" aria-labelledby="heading-{{ $accordionId }}" data-bs-parent="#datesAccordion">
                                        <div class="accordion-body">
                                            <div class="row">
                                                @foreach ($lessons as $i => $lessonItem)
                                                    <div class="col-xl-{{ 12 / count($lessons) }} mb-4">
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <b>{{ $lessonItem[0] }} - {{ $lessonItem[1] }}</b>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <select class="form-select" name="dates[{{ $date }}][{{ $i }}][lesson_id]">
                                                                    <option value="">Pilih Mata Pelajaran</option>
                                                                    @foreach ($gradeLevel as $grade)
                                                                        @foreach ($defaultCategoryAcademic as $acCategory)
                                                                            @foreach ($academicSubject as $subject)
                                                                                @if ($subject->level_id == $grade->id && $subject->category_id == $acCategory->id)
                                                                                    <option {{ isset($lessonItem['lesson'][0]) && $lessonItem['lesson'][0] == $subject->id ? 'selected' : '' }} value="{{ $subject->id }}">
                                                                                        {{ $subject->name }}
                                                                                    </option>
                                                                                @endif
                                                                            @endforeach
                                                                        @endforeach
                                                                    @endforeach
                                                                </select>
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
            {{-- </form> --}}
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
