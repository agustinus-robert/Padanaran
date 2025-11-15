@extends('hrms::layouts.default')

@section('title', 'Jadwal kerja | ')
@section('navtitle', 'Jadwal kerja')

@php
    $prm = [21, 22, 43];
@endphp

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('hrms::service.attendance.schedules.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Buat jadwal kerja baru</h2>
            <div class="text-secondary">Anda dapat membuat jadwal kerja dengan mengisi formulir di bawah</div>
        </div>
    </div>
    <div class="card mb-4 border-0">
        <div class="card-body">
            <form class="form-block" action="{{ route('hrms::service.teacher.schedule.store', ['next' => request('next')]) }}" method="POST"> @csrf
                <input type="hidden" name="start_at" value="{{ $start_at }}" />
                <input type="hidden" name="start_at" value="{{ $end_at }}" />

                <input type="hidden" name="empl_id" value="{{ $employee->id }}" readonly>
                <div class="row required mb-3">
                    <label class="col-lg-4 col-xl-3 col-form-label">Nama lengkap</label>
                    <div class="col-xl-8 col-xxl-6">
                        <input type="text" class="form-control" value="{{ $employee->user->name ?? $employee->user->profile->name }}" readonly />
                    </div>
                </div>
                <div class="row required mb-3">
                    <label class="col-lg-4 col-xl-3 col-form-label">Periode</label>
                    <div class="col-xl-8 col-xxl-6">
                        <input type="month" class="form-control @error('month') is-invalid @enderror" name="month" value="{{ old('month', request('month', date('Y-m'))) }}" readonly required />
                        @error('month')
                            <small class="text-danger d-block"> {{ $message }} </small>
                        @enderror
                    </div>
                </div>
                <div class="row required">
                    <label class="col-lg-4 col-xl-3 col-form-label">Kategori Pengajaran</label>
                    <div class="col-xl-8 col-xxl-6">
                        <select class="form-select" name="category_lesson">
                            @foreach ($defaultCategoryLessons as $value)
                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                            @endforeach
                        </select>
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
                    @foreach ($dates as $key => $date)
                        @php($moment = $moments->firstWhere('date', $date))
                        @php($class = isset($holidays[$date]) ? 'disabled' : '')
                        <div class="row mb-2">
                            <label class="col-lg-4 col-xl-3 col-form-label {{ $moment ? 'text-danger' : '' }}">
                                <span @if ($moment) data-bs-toggle="tooltip" title="{{ $moment->name }}" data-bs-placement="right" @endif>
                                    {{ strftime('%A', strtotime($date)) }} @if ($moment)
                                        <i class="mdi mdi-information-outline"></i>
                                    @endif
                                </span>
                            </label>
                            <div class="col-xl-9 col-xxl-9">
                                <div class="row">
                                    @foreach ($defaultLessons as $i => $lesson)
                                        <div class="col-xl-{{ 12 / count($defaultLessons) }} mb-4">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="d-flex mt-2">
                                                        <b>{{ $lesson->name }}</b>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="d-flex">
                                                        <div class="input-group">
                                                            <input type="hidden" name="dates[{{ $date }}][{{ $i }}][]" value="{{ \Carbon\Carbon::parse($lesson->in)->format('H:i') }}">
                                                            <input type="hidden" name="dates[{{ $date }}][{{ $i }}][]" value="{{ \Carbon\Carbon::parse($lesson->out)->format('H:i') }}">
                                                            <select class="time-{{ $date }}-{{ $i }} form-select" name="dates[{{ $date }}][{{ $i }}][lesson][]" {{ $class }}>
                                                                <option value="">Pilih Mata Pelajaran</option>
                                                                @foreach ($gradeLevel as $grade)
                                                                    @foreach ($defaultCategoryAcademic as $acCategory)
                                                                        <optgroup label="Kelas {{ $grade->name }} - {{ $acCategory->name }}">
                                                                            @foreach ($academicSubject as $subject)
                                                                                @if ($subject->level_id == $grade->id && $subject->category_id == $acCategory->id)
                                                                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                                                                @endif
                                                                                </option>
                                                                            @endforeach
                                                                        </optgroup>
                                                                    @endforeach
                                                                @endforeach
                                                            </select>
                                                            <select class="rombel-{{ $date }}-{{ $i }} form-select" name="dates[{{ $date }}][{{ $i }}][rombel][]" {{ $class }}>
                                                                <option value="">Pilih Rombel</option>
                                                                @foreach($defaultClassroom as $classroom)
                                                                    <option value="{{ $classroom->id }}">{{$classroom->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="row required mb-3">
                    <label class="col-lg-4 col-xl-3 col-form-label">Hari efektif kerja</label>
                    <div class="col-xl-7 col-xxl-5">
                        <input type="number" class="form-control @error('workdays_count') is-invalid @enderror" name="workdays_count" value="{{ old('workdays_count') }}" required />
                        <small class="text-muted">Dihitung otomatis dari jumlah kolom shift yang telah terisi</small>
                        @error('workdays_count')
                            <small class="text-danger d-block"> {{ $message }} </small>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-lg-8 offset-lg-4 offset-xl-3">
                        <div class="form-check mb-3">
                            <input class="form-check-input" id="agreement" type="checkbox" required>
                            <label class="form-check-label" for="agreement">Dengan ini saya menyatakan data di atas adalah valid</label>
                        </div>
                        <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                        <a class="btn btn-ghost-light text-dark" href="{{ request('next', route('hrms::service.attendance.schedules.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        const renderMe = (e) => {
            let time_in = e.currentTarget.dataset.in;
            let time_out = e.currentTarget.dataset.out;
            console.log(time_in)
            e.currentTarget.parentNode.nextElementSibling.querySelector('.in').value = e.currentTarget.checked ? time_in : '';
            e.currentTarget.parentNode.nextElementSibling.querySelector('.out').value = e.currentTarget.checked ? time_out : '';
            countWorkdays();
        }

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
    </script>
@endpush
