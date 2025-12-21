@extends('layouts.horizontal-layout')

@section('title', 'Jadwal kerja | ')
@section('navtitle', 'Jadwal kerja')

@push('nav')
    @include('hrms::layouts.includes.navbar-hrms')
@endpush

@php
    $isEdit = !is_null($schedule);
    $prm = [21, 22, 43];
@endphp

@section('body-content')

@include('components.navbar-admin')
<div class="row container-fluid justify-content-center">
    <div class="col-xxl-11">
        <div class="card mb-4 border-0 shadow-sm">
            <x-card-header type="{{ config('theme.default') }}">
                {{ $isEdit ? 'Ubah jadwal kerja' : 'Buat jadwal kerja baru' }}
                {{-- {{ $isEdit
                        ? 'Anda dapat mengubah jadwal kerja di bawah'
                        : 'Anda dapat membuat jadwal kerja dengan mengisi formulir di bawah'
                    }} --}}
            </x-card-header>

            <div class="card-body">
                <form class="form-block"
                    action="{{ $isEdit
                            ? route('hrms::service.attendance.schedules.update', ['schedule' => $schedule->id, 'next' => request('next')])
                            : route('hrms::service.attendance.schedules.store', ['next' => request('next')])
                    }}"
                    method="POST">

                    @csrf
                    @if($isEdit)
                        @method('PUT')
                    @endif

                    {{-- ================= HIDDEN ================= --}}
                    @if($isEdit)
                        <x-input name="start_at" type="hidden" value="{{ $schedule->start_at }}" />
                        <x-input name="end_at" type="hidden" value="{{ $schedule->end_at }}" />
                    @endif

                    {{-- ================= NAMA ================= --}}
                    {{-- <div class="row required mb-3">
                        <label class="col-lg-4 col-xl-3 col-form-label">Nama lengkap</label>
                        <div class="col-xl-8 col-xxl-6">
                            <x-input type="text" value="{{ $isEdit
                                        ? $schedule->employee->user->name
                                        : $employee->user->name }}"
                                disabled />
                        </div>
                    </div> --}}

                    <x-input-group label="Nama lengkap" required>
                        <x-input type="text" value="{{ $isEdit
                                        ? $schedule->employee->user->name
                                        : $employee->user->name }}"
                                disabled />
                    </x-input-group>

                    <x-input-group label="Periode" required>
                        <x-input type="monh" value="{{ $isEdit
                                        ? $schedule->period
                                        : request('month', date('Y-m')) }}"
                                disabled />
                    </x-input-group>

                    {{-- ================= KATEGORI ================= --}}
                    {{-- <div class="row required mb-3">
                        <label class="col-lg-4 col-xl-3 col-form-label">
                            Kategori Pengajaran
                        </label>
                        <div class="col-xl-8 col-xxl-6">
                            <select class="form-select" name="category_lesson">
                                @foreach ($defaultCategoryLessons as $value)
                                    <option
                                        value="{{ $value->id }}"
                                        @selected(
                                            old('category_lesson',
                                                $schedule->empl_category_id ?? null
                                            ) == $value->id
                                        )
                                    >
                                        {{ $value->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div> --}}


                    <x-input-group label="Kategori Pengajaran" required>
                        <x-select
                            name="category_lesson"
                            :options="$defaultCategoryLessons"
                            :value="old('category_lesson', $schedule?->empl_category_id)"
                            placeholder="-- Pilih kategori pengajaran --"
                        />
                    </x-input-group>


                    {{-- ================= JADWAL ================= --}}
                   <div class="mb-3" style="max-height: 480px; overflow-y: auto;">
                        @foreach ($dates as $date)
                            @php($moment = $moments->firstWhere('date', $date))

                            <x-input-group
                                :label="strftime('%A, %d %B %Y', strtotime($date))"
                                :isForm="true"
                                :isInputGroup="false"
                                :isRow="true"
                                :isLegend="true"
                            >

                                <div class="row">
                                    @foreach ($workshifts as $i => $shift)
                                        <x-col size="{{ 12 / count($workshifts) }}">
                                            <div class="d-flex">
                                                <div class="flex-grow-0 mt-3" style="width:160px">
                                                    <b>{{ $shift->label() }}</b>
                                                </div>

                                                <div class="form-check py-2">
                                                    <x-input
                                                        type="checkbox"
                                                        class="form-check-input"
                                                        data-in="{{ $shift == \Modules\HRMS\Enums\WorkShiftEnum::NORMAL && in_array($employee->position->position_id, $prm)
                                                            ? $shift->defaultTime()['in'][1]
                                                            : $shift->defaultTime()['in'][0] }}"
                                                        data-out="{{ $shift == \Modules\HRMS\Enums\WorkShiftEnum::NORMAL && in_array($employee->position->position_id, $prm)
                                                            ? $shift->defaultTime()['out'][1]
                                                            : $shift->defaultTime()['out'][0] }}"
                                                        onclick="renderMe(event)"
                                                    />
                                                </div>

                                                <div class="input-group input-group-dynamic">

                                                    <x-input
                                                        type="time"
                                                        class="in time-{{ $date }}-{{ $i }}"
                                                        name="dates[{{ $date }}][{{ $i }}][]"
                                                        :value="$moment ? '' : (!in_array($employee->position->position_id, $prm)
                                                            ? $worktimes[date('w', strtotime($date))][$i][0] ?? ''
                                                            : '')"
                                                    />

                                                    <div class="mt-3 p-1">
                                                        s.d
                                                    </div>

                                                    <x-input
                                                        type="time"
                                                        class="form-control out time-{{ $date }}-{{ $i }}"
                                                        name="dates[{{ $date }}][{{ $i }}][]"
                                                        :value="$moment ? '' : (!in_array($employee->position->position_id, $prm)
                                                            ? $worktimes[date('w', strtotime($date))][$i][0] ?? ''
                                                            : '')"
                                                    />
                                                </div>
                                            </div>
                                        </x-col>
                                    @endforeach
                                </div>
                            </x-input-group>
                        @endforeach
                    </div>



                    {{-- ================= HARI EFEKTIF ================= --}}
                    {{-- <div class="row required mb-3">
                        <label class="col-lg-4 col-xl-3 col-form-label">
                            Hari efektif kerja
                        </label>
                        <div class="col-xl-7 col-xxl-5">

                        </div>
                    </div> --}}

                    <x-input-group :isRow="false" :isInputGroup="true" label="Hari Efektif Kerja">
                         <x-input type="number"
                            name="workdays_count"
                            value="{{ old('workdays_count', $schedule->workdays_count ?? 0) }}"
                                required />
                    </x-input-group>

                    <div class="row mb-3">
                        <div class="col-lg-8 offset-lg-4 offset-xl-3">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" required>
                                <label class="form-check-label">
                                    Dengan ini saya menyatakan data di atas valid
                                </label>
                            </div>

                            <button class="btn btn-soft-danger">
                                <i class="mdi mdi-check"></i> Simpan
                            </button>

                            <a class="btn btn-ghost-light text-dark"
                            href="{{ request('next', route('hrms::service.attendance.schedules.index')) }}">
                                Kembali
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection


@push('scripts')
    <script type="text/javascript">
        const renderMe = (e) => {
            let time_in = e.currentTarget.dataset.in;
            let time_out = e.currentTarget.dataset.out;
            e.currentTarget.parentNode.nextElementSibling.querySelector('.in').value = e.currentTarget.checked ? time_in : '';
            e.currentTarget.parentNode.nextElementSibling.querySelector('.out').value = e.currentTarget.checked ? time_out : '';
            countWorkdays();
        }

        const countWorkdays = () => {
            let count = 0;
            @json($dates).forEach((date) => {
                @foreach ($workshifts as $i => $shift)
                    count += Array.from(document.querySelectorAll(`[type="time"].time-${date}-{{ $i }}`)).filter((input) => !!input.value).length == 2 ? 1 : 0;
                @endforeach
            })
            document.querySelector('[name="workdays_count"]').value = count;
        }

        document.addEventListener('DOMContentLoaded', () => {
            countWorkdays();

            [].slice.call(document.querySelectorAll('[type="time"]')).map((el) => {
                el.addEventListener('change', (e) => {
                    countWorkdays();
                });
            });
        });
    </script>
@endpush

