@extends('hrms::layouts.default')

@section('title', 'Jadwal kerja kolektif | ')
@section('navtitle', 'Jadwal kerja kolektif')

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
            <form class="form-block" action="{{ route('hrms::service.attendance-teacher.schedules.collective.store', ['next' => request('next')]) }}" method="POST"> @csrf
                <div class="row required mb-3">
                    <label class="col-lg-4 col-xl-3 col-form-label">Periode</label>
                    <div class="col-xl-8 col-xxl-6">
                        <input type="month" class="form-control @error('month') is-invalid @enderror" name="month" value="{{ old('month', request('month', date('Y-m'))) }}" readonly required />
                        @error('month')
                            <small class="text-danger d-block"> {{ $message }} </small>
                        @enderror
                    </div>
                </div>
                <div class="mb-3" style="max-height: 480px; overflow-y: auto;">
                    <div class="row d-none d-lg-block sticky-top bg-white pb-3">
                        <div class="col-xl-9 offset-lg-4 offset-xl-3">
                            <div class="row">
                                @foreach ($workshifts as $shift)
                                    <div class="col-xl-{{ 12 / count($workshifts) }} fw-bold text-center">{{ $shift->label() }}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @foreach ($dates as $date)
                        @php($moment = $moments->firstWhere('date', $date))
                        <div class="row mb-2">
                            <label class="col-lg-4 col-xl-3 col-form-label {{ $moment ? 'text-danger' : '' }}">
                                <span @if ($moment) data-bs-toggle="tooltip" title="{{ $moment->name }}" data-bs-placement="right" @endif>
                                    {{ strftime('%A, %d %B %Y', strtotime($date)) }} @if ($moment)
                                        <i class="mdi mdi-information-outline"></i>
                                    @endif
                                </span>
                            </label>
                            <div class="col-xl-9 col-xxl-9">
                                <div class="row">
                                    @foreach ($workshifts as $i => $shift)
                                        <div class="col-xl-{{ 12 / count($workshifts) }}">
                                            <div class="input-group">
                                                <input type="time" class="form-control time-{{ $date }}-{{ $i }}" name="dates[{{ $date }}][{{ $i }}][]" value="{{ $moment ? '' : $worktime_default[date('w', strtotime($date))][$i][0] ?? '' }}">
                                                <div class="input-group-text">s.d.</div>
                                                <input type="time" class="form-control time-{{ $date }}-{{ $i }}" name="dates[{{ $date }}][{{ $i }}][]" value="{{ $moment ? '' : $worktime_default[date('w', strtotime($date))][$i][1] ?? '' }}">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="row required mb-3">
                    <label class="col-lg-4 col-xl-3 col-form-label">Karyawan</label>
                    <div class="col-lg-8 col-xl-9">
                        <select class="form-select @error('empl_ids') is-invalid @enderror" name="empl_ids[]" style="height: 240px;" multiple>
                            @foreach ($contracts->groupBy('position.position.department.name') as $department => $_contracts)
                                <optgroup label="{{ $department ?: 'Lainnya' }}">
                                    @forelse($_contracts->sortBy('employee.user.name') as $contract)
                                        <option value="{{ $contract->id }}" @selected(in_array($contract->id, old('empl_ids', [])))>{{ $contract->employee->user->name }} &mdash; {{ $contract->position->position->name }} ({{ $contract->id }})</option>
                                    @empty
                                        <option value="">Tidak ada karyawan</option>
                                    @endforelse
                                </optgroup>
                            @endforeach
                        </select>
                        <small class="text-muted d-block mt-1">Tekan dan tahan <code>ctrl</code> untuk menghapus atau memilih lebih dari dua</small>
                        @error('empl_ids')
                            <small class="text-danger d-block"> {{ $message }} </small>
                        @enderror
                    </div>
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
