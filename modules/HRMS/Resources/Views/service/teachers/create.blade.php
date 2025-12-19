@extends('layouts.horizontal-layout')

@section('title', 'Jadwal kerja | ')
@section('navtitle', 'Jadwal kerja')

@push('nav')
    @include('hrms::layouts.includes.navbar-hrms')
@endpush

@section('body-content')
@include('components.navbar-admin')
<div class="row container-fluid justify-content-center">
    <div class="col-md-8">
        <div class="card mb-4 border-0 shadow-sm">
            <x-card-header type="{{ config('theme.default') }}">
                <h6 class="text-white">Buat Jadwal</h6>
            </x-card-header>

            <div class="card-body">
                <form action="{{ route('hrms::service.teacher.schedule.store', ['next' => request('next')]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="start_at" value="{{ $start_at }}">
                    <input type="hidden" name="end_at" value="{{ $end_at }}">
                    <input type="hidden" name="empl_id" value="{{ $employee->id }}" readonly>

                    {{-- Nama Lengkap --}}
                    <x-input-group :isRow="true">
                        <x-label value="Nama lengkap" />
                        <x-col size="12">
                            <x-input value="{{ $employee->user->name ?? $employee->user->profile->name }}" readonly />
                        </x-col>
                    </x-input-group>

                    {{-- Periode --}}
                    <x-input-group :isRow="true">
                        <x-label value="Periode" />
                        <x-col size="12">
                            <x-input type="month" name="month" value="{{ old('month', request('month', date('Y-m'))) }}" readonly required />
                        </x-col>
                    </x-input-group>

                    {{-- Kategori Pengajaran --}}
                    <x-input-group :isRow="true">
                        <x-label value="Kategori Pengajaran" />
                        <x-col size="12">
                            <x-select name="category_lesson" :options="$defaultCategoryLessons->map(fn($c) => ['value'=>$c->id,'label'=>$c->name])" placeholder="-- Pilih --" />
                        </x-col>
                    </x-input-group>

                    {{-- Jadwal per tanggal --}}
                    <x-input-group>
                        @foreach ($dates as $date)
                             @php
                                $moment = $moments->firstWhere('date', $date);
                                $class = isset($holidays[$date]) ? 'disabled' : '';

                                $dayIndo = [
                                    'Monday' => 'Senin',
                                    'Tuesday' => 'Selasa',
                                    'Wednesday' => 'Rabu',
                                    'Thursday' => 'Kamis',
                                    'Friday' => 'Jumat',
                                    'Saturday' => 'Sabtu',
                                    'Sunday' => 'Minggu'
                                ];

                                $nameDay = $dayIndo[date('l', strtotime($date))];
                            @endphp

                            <fieldset class="mb-3 p-3 border rounded">
                                <legend class="fw-bold">
                                    <span @if ($moment) data-bs-toggle="tooltip" title="{{ $moment->name }}" data-bs-placement="right" @endif>
                                        {{ $nameDay }}
                                        @if($moment)
                                            <i class="mdi mdi-information-outline text-danger"></i>
                                        @endif
                                    </span>
                                </legend>

                                <div class="row">
                                    @foreach ($defaultLessons as $i => $lesson)
                                        <div class="col-xl-{{ 12 / count($defaultLessons) }} mb-4">
                                            <div class="row align-items-center">
                                                {{-- Nama Lesson --}}
                                                <div class="col-md-2">
                                                    <b>{{ $lesson->name }}</b>
                                                </div>

                                                {{-- Select Mata Pelajaran & Rombel --}}
                                                <div class="col-md-10">
                                                    <div class="d-flex gap-2">
                                                        <input type="hidden" name="dates[{{ $date }}][{{ $i }}][]" value="{{ \Carbon\Carbon::parse($lesson->in)->format('H:i') }}">
                                                        <input type="hidden" name="dates[{{ $date }}][{{ $i }}][]" value="{{ \Carbon\Carbon::parse($lesson->out)->format('H:i') }}">

                                                        {{-- Mata Pelajaran --}}
                                                        <x-select
                                                            class="time-{{ $date }}-{{ $i }}"
                                                            name="dates[{{ $date }}][{{ $i }}][lesson][]"
                                                            :options="$gradeLevel->map(fn($grade) => [
                                                                'label' => 'Kelas '.$grade->name,
                                                                'children' => $defaultCategoryAcademic->map(fn($cat) => [
                                                                    'label' => $cat->name,
                                                                    'children' => $academicSubject
                                                                        ->filter(fn($sub) => $sub->level_id==$grade->id && $sub->category_id==$cat->id)
                                                                        ->map(fn($sub) => ['value'=>$sub->id,'label'=>$sub->name])
                                                                ])
                                                            ])"
                                                            placeholder="Pilih Mata Pelajaran"
                                                            :disabled="$class==='disabled'"
                                                        />

                                                        {{-- Rombel --}}
                                                        <x-select
                                                            class="rombel-{{ $date }}-{{ $i }}"
                                                            name="dates[{{ $date }}][{{ $i }}][rombel][]"
                                                            :options="$defaultClassroom->map(fn($r)=>['value'=>$r->id,'label'=>$r->name])"
                                                            placeholder="Pilih Rombel"
                                                            :disabled="$class==='disabled'"
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </fieldset>
                        @endforeach
                    </x-input-group>

                    {{-- Hari efektif --}}
                    <x-input-group :isRow="true">
                        <x-label value="Hari efektif kerja" />
                        <x-col size="12">
                            <x-input type="number" name="workdays_count" value="{{ old('workdays_count') }}" required />
                            <small class="text-muted">Dihitung otomatis dari jumlah kolom shift yang terisi</small>
                        </x-col>
                    </x-input-group>

                    {{-- Checkbox & tombol --}}
                    <div class="row mb-3 text-center">
                        <div class="col-lg-6 offset-lg-4 offset-xl-3">
                            <div class="form-check mb-3">
                                <input class="form-check-input" id="agreement" type="checkbox" required>
                                <label class="form-check-label" for="agreement">Dengan ini saya menyatakan data di atas adalah valid</label>
                            </div>
                            <x-btn type="submit" variant="dark"><i class="mdi mdi-check"></i> Simpan</x-btn>
                            <a class="btn btn-light text-dark" href="{{ request('next', route('hrms::service.attendance.schedules.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function countWorkdays() {
    let jumlah = 0;
    const dates = @json($dates);
    dates.forEach(date=>{
        @foreach($workshifts as $i=>$shift)
        document.querySelectorAll('.time-' + date + '-{{ $i }}').forEach(el=>{
            if(el.value && el.value.trim()!==''){ jumlah++; }
        })
        @endforeach
    });
    document.querySelector('[name="workdays_count"]').value = jumlah;
}

document.addEventListener('change', e => { if(e.target.tagName==='SELECT'){ countWorkdays(); } });
window.addEventListener('load', countWorkdays);
</script>
@endpush
