@extends('hrms::layouts.default')

@section('title', 'Rekapitulasi rekap mengajar | ')
@section('navtitle', 'Rekapitulasi rekap mengajar')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('hrms::recapitulation.teachers.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Ubah rekap</h2>
            <div class="text-secondary">Anda dapat mengubah rekap dengan mengisi formulir di bawah</div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-4">
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-history"></i> Riwayat Rekap Mengajar
                </div>
                <div class="table-responsive border-top" style="overflow: auto; max-height: 960px;">
                    <table class="mb-0 table">
                        <tbody>
                            @forelse ($entries as $date => $shifts)
                                @foreach ($shifts as $entry)
                                    <tr>
                                        <td>{{ $loop->parent->iteration }}</td>
                                        <td>
                                            <span @if ($moment = $moments->firstWhere('date', $date)) data-bs-toggle="tooltip" title="" data-bs-placement="right" data-bs-original-title="{{ $moment->name }}" @endif @class(['fw-bold', 'text-danger' => $moment])>
                                                @php
                                                    $modifier = $entry->modifier ?? null;
                                                    $adjustment = 0;

                                                    if ($modifier !== null) {
                                                        if (str_starts_with($modifier, '+')) {
                                                            $adjustment = floatval($modifier);
                                                        } elseif (str_starts_with($modifier, '-')) {
                                                            $adjustment = floatval($modifier);
                                                        }
                                                    }

                                                    $baseHour = 2 + $adjustment;
                                                @endphp

                                                {{ strftime('%A, %d %b %Y', strtotime($date)) }}
                                                @if ($moment)
                                                    <i class="mdi mdi-information-outline"></i>
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            @php($original['times'][$date] = $entry->in?->format('H:i:s') ?? null)
                                            @php($currentday = $scanlogs[$date] ?? [])
                                            {{-- {{ implode(', ', array_map(fn($location) => $locations[$location], $entry->location)) }} --}}
                                            @foreach ($entry->location ?? [1] as $k => $v)
                                                @if ($loop->first && $loop->last)
                                                @elseif($loop->last && !$loop->first)
                                                    @php($current = $currentday->where('location', $v))
                                                    <span data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $current->first()->created_at->format('H:i:s') }}">
                                                        <span class="text-dark">{{ $locations[$v] }}</span>
                                                        <small>
                                                            <i class="mdi mdi-information-outline text-muted"></i>
                                                        </small>
                                                    </span>
                                                @else
                                                    {{ $locations[$v] }},
                                                @endif
                                            @endforeach

                                            @php($dateWeekEnd = date('w', strtotime($entry->date)))

                                            @if ($dateWeekEnd == 0 || $dateWeekEnd == 6)
                                                {{ $entry->shift->label() }} <sup><b>Extra</b></sup>
                                            @else
                                                @if ($entry->shift->value == 5)
                                                    {{ $entry->shift->label() }} <sup><b>Extra</b></sup>
                                                @else
                                                    {{ $entry->shift->label() }}
                                                @endif
                                            @endif
                                        </td>
                                        <td @class(['text-center'])>
                                            @if ($baseHour < 2)
                                                <span class="badge bg-danger">
                                                    {{ $baseHour }} jam</span>
                                            @elseif($baseHour == 2)
                                                <span class="badge bg-primary">
                                                    {{ $baseHour }} jam</span>
                                            @elseif($baseHour > 2)
                                                <span class="badge bg-success">
                                                    {{ $baseHour }} jam</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td>@include('components.notfound-vertical')</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-plus-circle-outline"></i> Buat rekapitulasi baru
                </div>
                <div class="card-body border-top border-light">
                    <div class="row gy-3">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="text-muted">Nama karyawan</div>
                                    <div class="fw-bold">{{ $attendance->employee->user->name }}</div>
                                </div>

                                <div class="col-md-6">
                                    <div class="text-muted">Status Karyawan </div>
                                    <div class="fw-bold">{{ $employee->contract->work_location == 1 ? 'WFO' : 'WFA' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted">
                                <span data-bs-toggle="tooltip" data-bs-placement="right" title="Tanggal pada periode ini akan digunakan untuk penghitungan gaji, jadi pastikan tanggal yang Kamu isi adalah benar">
                                    <span>Periode</span>
                                    <i class="mdi mdi-information-outline"></i>
                                </span>
                            </div>
                            <div class="align-items-center d-flex">
                                <div>{{ $attendance->start_at->format('d-M-Y') }}</div>
                                <div class="text-muted small mx-2">&mdash; s.d. &mdash;</div>
                                <div>{{ $attendance->end_at->format('d-M-Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body border-top border-light">
                    <form class="form-block form-confirm" action="{{ route('hrms::summary.teachings.update', ['teaching' => $attendance->empl_id, 'start_at' => $attendance->start_at->format('Y-m-d'), 'end_at' => $attendance->end_at->format('Y-m-d'), 'next' => request('next', route('hrms::summary.attendances.index'))]) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="row gy-4">
                            <div class="col-lg-6">
                                <h6 class="fw-bold mb-3">Rekapitulasi umum periode ini</h6>
                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Jumlah hari</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[days]" value="{{ $attendance->result->days }}">
                                            <div class="input-group-text">hari</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Hari efektif</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[workdays]" value="{{ $attendance->result->workdays }}">
                                            <div class="input-group-text">hari</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Hari libur nasional</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[holidays]" value="{{ $attendance->result->holidays }}">
                                            <div class="input-group-text">hari</div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <h6 class="fw-bold mb-3">Rekapitulasi perizinan</h6>
                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Jumlah izin</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[unpresence][leave]" value="{{ count($employeeLeaves) }}">
                                            <div class="input-group-text">hari &nbsp;<i class="mdi mdi-information-outline text-primary" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#leaveDatesModal" id="openLeaveDates" title="Cek tanggal Izin"></i></div>
                                        </div>
                                    </div>
                                </div>
                                @foreach (Modules\Core\Enums\VacationTypeEnum::cases() as $type)
                                    <div class="row align-items-center mb-2">
                                        <label class="col-form-label col-md-4" for="">Jumlah {{ $type->label() }}</label>
                                        <div class="col-md-7">
                                            <div class="input-group">
                                                <input class="form-control" type="number" min="0" step="0.1" name="summary[unpresence][vacation][{{ strtolower($type->name) }}]" value="{{ $employeeVacationsSums[strtolower($type->name)] }}">
                                                <div class="input-group-text">hari &nbsp;<i onclick="modalVacation('vacation{{ $type->value }}')" style="cursor:pointer;" class="mdi mdi-information-outline text-primary" title="Cek tanggal {{ $type->label() }}"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="col-lg-6">
                                <h6 class="fw-bold mb-3">Rekapitulasi Kehadiran Mengajar</h6>
                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Jam reguler</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[attendance_work]" value="{{ $attendance->result->attendance_work }}">
                                            <div class="input-group-text">kehadiran</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Jam ekstra</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="1" name="summary[additional_workdays]" value="{{ $attendance->result->additional_workdays }}">
                                            <div class="input-group-text">kehadiran</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Jumlah kehadiran</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[attendance_total]" value="{{ $presenced->count() }}">
                                            <div class="input-group-text">kehadiran</div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Jumlah tepat waktu</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[ontime_total]" value="{{ $attendance->result->ontime_total }}">
                                            <div class="input-group-text">kehadiran</div>
                                        </div>
                                    </div>
                                </div> --}}
                                {{-- <div class="row mb-3">
                                    <label class="col-form-label col-md-4" for="">Jumlah terlambat</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[late_total]" value="{{ $attendance->result->late_total }}">
                                            <div class="input-group-text">kehadiran</div>
                                        </div>
                                    </div>
                                </div> --}}



                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Mengajar WFO</label>

                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[presence][wfo]" value="{{ $presenced->filter(fn($entry) => $entry->location == 1)->count() + $adtWfo }}">
                                            <div class="input-group-text">kehadiran</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Mengajar WFA</label>

                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[presence][wfa]" value="{{ $presenced->filter(fn($entry) => $entry->location == 2)->count() + $adtWfh }}">
                                            <div class="input-group-text">kehadiran</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- @if ($employee->contract->work_location->value == 1)
                                    <i>Pada karyawan <b>WFO</b> akan mendapatkan <b>tunjangan internet</b> jika <b>mengajar WFA</b>, dengan ketentuan:</i>

                                    <ul>
                                        <li>bekerja pada jam lembur</li>
                                        <li>bekerja pada hari libur</li>
                                    </ul>
                                @else
                                    <i>Pada karyawan <b>WFA</b> akan mendapatkan <b>tunjangan makan</b> dan <b>tunjangan transportasi</b> jika <b>mengajar WFO</b></i>
                                @endif --}}

                                @if (count($scanlogs) > 0)
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="card border-0">
                                                <div class="card-body">
                                                    <i class="mdi mdi-history"></i> Riwayat Presensi
                                                </div>
                                                <div class="table-responsive border-top" style="overflow: auto; max-height: 480px;">
                                                    <table class="mb-0 table text-center">
                                                        <tbody>
                                                            <tr>
                                                                <th>Tanggal</th>
                                                                <th>Jam Absen</th>
                                                                <th>Kehadian</th>
                                                            </tr>
                                                            @foreach ($scanlogs as $scan)
                                                                @foreach ($scan as $value)
                                                                    <tr>
                                                                        <td>{{ \Carbon\Carbon::parse($value->created_at)->translatedFormat('l, d F Y') }}</td>
                                                                        <td>{{ date('H:i', strtotime($value->created_at)) }}</td>
                                                                        <td>{!! $value->location == 1 ? '<label class="badge bg-primary">WFO</label>' : '<label class="badge bg-danger">WFA</label>' !!}</td>
                                                                        <td></td>
                                                                    </tr>
                                                                @endforeach
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <h6 class="fw-bold mb-3">Jam Mengajar</h6>

                            <div class="col-lg-6">
                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Total jam mengajar</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="teach[amount_total]" value="{{ round($hourTotal, 1) }}">
                                            <div class="input-group-text">Jam</div>
                                        </div>
                                    </div>
                                    <i>Jika cuti atau sakit dihitung 1,5 jam</i>
                                </div>

                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Jam Kerja Kelebihan</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="teach[overhour]" value="{{ round($extraOver, 1) }}">
                                            <div class="input-group-text">Jam</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Jam Kerja Extra</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="teach[extrahour]" value="{{ round($hourExtra, 1) }}">
                                            <div class="input-group-text">Jam</div>
                                        </div>
                                    </div>
                                </div>

                                @if ($workHour > $hourReguler && $hourExtra > 0)
                                    <p>Berlaku rumus <b>rate mengajar + (extra x 35%)</b> karena tidak mencukupi beban kerja</p>
                                    <p>Beban kerja untuk {{ $employee->user->name }} : <b>{{ $workHour }}</b>
                                    <p>Hasil rate mengajar didapatkan : <b>{{ $workHour + $hourExtra * 0.35 }}</b>
                                @endif
                            </div>
                        </div>
                        <hr>

                        @if ($userNow->position_id !== 3)
                            <div class="form-check mb-3">
                                <input class="form-check-input" id="agreement" type="checkbox" required>
                                <label class="form-check-label" for="agreement">Dengan ini saya selaku {{ Auth::user()->employee->position->position->name ?? 'Human Resource (HR)' }} menyatakan data di atas adalah valid</label>
                            </div>

                            <div>
                                <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                                <a class="btn btn-ghost-light text-dark" href="{{ request('next', route('portal::recapitulation.teachers.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                            </div>
                            <input class="position-fixed d-none" type="hidden" name="original" value='{{ json_encode($original) }}'>
                        @endif
                    </form>

                    @if ($userNow->position_id == 3)
                        <div class="row">
                            <div class="col-md-6">
                                @foreach ($attendance->approvables as $approvable)
                                    <div class="row gy-2 @if (!$loop->last) mb-4 @endif">
                                        <div class="col-md-12">
                                            <div class="text-muted small mb-1">
                                                {{ ucfirst($approvable->type) }} #{{ $approvable->level }}
                                            </div>
                                            <strong>{{ $approvable->userable->getApproverLabel() }}</strong>
                                        </div>
                                        <div class="col-md-12">
                                            @if ($approvable->userable->is($userNow) && !$attendance->trashed())
                                                <form class="form-block" action="{{ route('hrms::summary.summary.permission', ['next' => request('next', route('hrms::summary.teachings.index'))]) }}" method="post"> @csrf @method('PUT')
                                                    <input type="hidden" name="id_attendance" value="{{ $attendance->id }}" />
                                                    <input type="hidden" name="id_teaching" value="{{ $teach->id }}" />
                                                    <div class="mb-3">
                                                        <select class="form-select @error('result') is-invalid @enderror" name="result">
                                                            @foreach ($results as $result)
                                                                @unless (($approvable->cancelable && in_array($result, Modules\HRMS\Models\EmployeeRecapSubmission::$cancelable_disable_result)) || in_array($result, Modules\HRMS\Models\EmployeeRecapSubmission::$approvable_disable_result ?? []))
                                                                    <option value="{{ $result->value }}" @selected($result->value == old('result', $approvable->result->value))>{{ $result->label() }}</option>
                                                                @endunless
                                                            @endforeach
                                                        </select>
                                                        @error('result')
                                                            <div class="text-danger small">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3">
                                                        <textarea class="form-control @error('reason') is-invalid @enderror" type="text" name="reason" placeholder="Alasan ...">{{ old('reason', $approvable->reason) }}</textarea>
                                                        @error('reason')
                                                            <div class="text-danger small">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                                                    <a class="btn btn-soft-secondary text-dark" href="{{ request('next', route('portal::schedule.submission.index')) }}"><i class="mdi mdi-arrow-left-circle-outline"></i> Kembali</a>
                                                </form>
                                            @else
                                                <div class="h-100 d-flex">
                                                    <div class="align-self-center badge bg-{{ $approvable->result->color() }} fw-normal text-white"><i class="{{ $approvable->result->icon() }}"></i> {{ $approvable->result->label() }}</div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    @if ($approvable->history)
                                        <div class="row p-0">
                                            <div class="col-md-6 offset-md-6">
                                                <hr class="text-muted mt-0">
                                                <p class="small text-muted mb-1">Catatan riwayat sebelumnya</p>
                                                {{ $approvable->history->reason }}
                                            </div>
                                        </div>
                                    @endif
                                    @if (!$loop->last)
                                        <hr class="text-muted">
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const sumOvertimeTotal = () => {
            let overdays = parseFloat(document.querySelector('[name="summary[overtime][overdays]"]').value || 0)
            let holidays = parseFloat(document.querySelector('[name="summary[overtime][holidays]"]').value || 0)
            document.querySelector('[name="summary[overtime][total]"]').value = (works + overdays + holidays).toFixed(2);
        }
    </script>
@endpush

<div class="modal fade" id="leaveDatesModal" tabindex="-1" aria-labelledby="leaveDatesModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="leaveDatesModalLabel">Rincian Izin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if (count($employeeLeaves) > 0)
                    <table class="table text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal Izin</th>
                                <th>Tipe Izin</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php($i = 1)
                            @foreach ($employeeLeaves as $value)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    @foreach (json_decode($value->dates) as $v)
                                        <td>{{ \Carbon\Carbon::parse($v->d)->translatedFormat('l, d F Y') }}</td>
                                    @endforeach
                                    <td>
                                        @if ($value->ctg_id == 4)
                                            <label class="badge bg-danger">Sakit</label>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="row text-center">
                        <h1>Belum ada Data</h1>
                    </div>
                @endif

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function modalVacation(id) {
            const modal = new bootstrap.Modal(document.getElementById(id));
            modal.show();
        }
    </script>
@endpush

@foreach (Modules\Core\Enums\VacationTypeEnum::cases() as $type)
    @if ($employeeVacationsSums[strtolower($type->name)] > 0)
        <div class="modal fade" id="vacation{{ $type->value }}" tabindex="-1" aria-labelledby="vacationLabel{{ $type->value }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="vacationLabel{{ $type->value }}">Tanggal {{ $type->label() }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table-bordered table text-center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($i = 1)
                                @foreach ($employeeVacations->where(fn($vac) => $vac->quota?->category?->type->value === $type->value) as $vac)
                                    @php($dates = json_decode($vac->dates, true))

                                    @foreach ($dates as $date)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ \Carbon\Carbon::parse($date['d'])->translatedFormat('l, d F Y') }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach
