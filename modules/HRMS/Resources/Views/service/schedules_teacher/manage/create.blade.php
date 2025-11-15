@extends('administration::layouts.default')

@section('title', 'Jadwal kerja | ')
@section('navtitle', 'Jadwal kerja')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('portal::schedule.manages.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Buat jadwal kerja baru</h2>
            <div class="text-secondary">Anda dapat membuat jadwal kerja dengan mengisi formulir di bawah</div>
        </div>
    </div>
    <form class="form-block" action="{{ route('administration::service.manages.store', ['next' => request('next')]) }}" method="POST"> @csrf
        <div class="card mb-4 border-0">
            <div class="card-body">
                <input type="hidden" name="empl_id" value="{{ $employee->id ?? null }}" readonly>
                <div class="row required mb-3">
                    <label class="col-lg-4 col-xl-3 col-form-label">Nama lengkap</label>
                    <div class="col-xl-8 col-xxl-6">
                        <input type="text" class="form-control" value="{{ $employee->user->name }}" readonly />
                    </div>
                </div>
                <div class="row required mb-3">
                    <label class="col-lg-4 col-xl-3 col-form-label">Jabatan</label>
                    <div class="col-xl-8 col-xxl-6">
                        <input type="text" class="form-control" value="{{ $employee->position->position->name }}" readonly />
                    </div>
                </div>
                <div class="row required mb-3">
                    <label class="col-lg-4 col-xl-3 col-form-label">Periode</label>
                    <div class="col-xl-8 col-xxl-6">
                        <div class="input-group">
                            <input type="datetime-local" class="form-control @error('start_at') is-invalid @enderror" name="start_at" value="{{ old('start_at', $start_at) }}" required />
                            <input type="datetime-local" class="form-control @error('en_at') is-invalid @enderror" name="end_at" value="{{ old('end_at', $end_at) }}" />
                        </div>
                        @error(['start_at', 'end_at'])
                            <small class="text-danger d-block"> {{ $message }} </small>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="card-body border-top">
                <div class="mb-3" style="max-height: 480px; overflow-y: auto;">
                    @foreach ($dates as $key => $date)
                        @php($moment = $moments->firstWhere('date', $date))
                        @php($class = isset($holidays[$date]) ? 'disabled' : '')
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
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="d-flex mt-2">
                                                        @if($i < 4)
                                                        <b>{{ $shift->label() }}</b>
                                                        @else
                                                        <b><i>{{ $shift->label() }}</i></b>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-10">
                                                    <div class="d-flex">
                                                        <div class="form-check py-2">
                                                            <input class="form-check-input" type="checkbox" value="" id="" data-in="{{ $shift->defaultTime()['in'][0] }}" data-out="{{ $shift->defaultTime()['out'][0] }}" onclick="renderMe(event)" {{ $class }} />
                                                        </div>
                                                        <div class="input-group">
                                                            <input type="time" class="form-control in time-{{ $date }}-{{ $i }}" name="dates[{{ $date }}][{{ $i }}][]" value="{{ $moment ? '' : $schedules[date('w', strtotime($date))][$i][0] ?? '' }}" {{ $class }}>
                                                            <div class="input-group-text">s.d.</div>
                                                            <input type="time" class="form-control out time-{{ $date }}-{{ $i }}" name="dates[{{ $date }}][{{ $i }}][]" value="{{ $moment ? '' : $schedules[date('w', strtotime($date))][$i][1] ?? '' }}" {{ $class }}>
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
            </div>
        </div>
    </form>
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
