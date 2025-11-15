@extends('hrms::layouts.default')

@section('title', 'Rekapitulasi mengajar | ')
@section('navtitle', 'Rekapitulasi mengajar')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('portal::dashboard-msdm.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Buat rekap mengajar baru</h2>
            <div class="text-secondary">Anda dapat membuat rekap mengajar dengan mengisi formulir di bawah</div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-4">
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-history"></i> Riwayat jadwal mengajar
                </div>
                <div class="table-responsive border-top" style="overflow: auto; max-height: 960px;">
                    <table class="mb-0 table">
                        <tbody>
                            @forelse ($entries as $date => $shifts)
                                @foreach ($shifts as $entry)
                                    @if (!empty($entry->lesson))
                                        <tr>
                                            <td></td>
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
                                                @foreach ($entry->location ?? [1] as $k => $v)
                                                    @if ($loop->first && $loop->last)
                                                        {{-- {{ $locations[$v] }} --}}
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
                                            </td>
                                            <td @class(['text-center'])>
                                                {{-- @class([
                                                'text-danger' => !$entry->ontime,
                                                'text-center',
                                            ]) --}}
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
                                            {{-- {{ $entry->in?->format('H:i:s') ?? '-' }}</td> --}}
                                        </tr>
                                    @endif
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
                            <div class="text-muted">Nama karyawan</div>
                            <div class="fw-bold">{{ $employee->user->name }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted">
                                <span data-bs-toggle="tooltip" data-bs-placement="right" title="Tanggal pada periode ini akan digunakan untuk penghitungan gaji, jadi pastikan tanggal yang Kamu isi adalah benar">
                                    <span>Periode</span>
                                    <i class="mdi mdi-information-outline"></i>
                                </span>
                            </div>
                            <div class="align-items-center d-flex">
                                <div>{{ $start_at->format('d-M-Y') }}</div>
                                <div class="text-muted small mx-2">&mdash; s.d. &mdash;</div>
                                <div>{{ $end_at->format('d-M-Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body border-top border-light">
                    <form class="form-block form-confirm" action="{{ route('hrms::summary.teachings.store', ['employee' => $employee->id, 'start_at' => $start_at->format('Y-m-d'), 'end_at' => $end_at->format('Y-m-d'), 'next' => request('next', route('hrms::summary.attendances.index'))]) }}" method="post"> @csrf
                        <div class="row gy-4">
                            <div class="col-lg-6">
                                <h6 class="fw-bold mb-3">Rekapitulasi umum periode ini</h6>
                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Jumlah hari</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[days]" value="{{ $start_at->diffInDays($end_at) + 1 }}">
                                            <div class="input-group-text">hari</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Hari efektif</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[workdays]" value="{{ $workDays }}">
                                            <div class="input-group-text">hari</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Hari libur nasional</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[holidays]" value="{{ $moments->groupBy('date')->count() }}">
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
                                            <div class="input-group-text">hari</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <h6 class="fw-bold mb-3">Rekapitulasi Kehadiran Mengajar</h6>
                                {{-- <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Jam reguler</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[attendance_work]" value="{{ $presences->count() }}">
                                            <div class="input-group-text">kehadiran</div>
                                        </div>
                                    </div>
                                </div> --}}

                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Jumlah kehadiran</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[attendance_total]" value="{{ $presences->count() }}">
                                            <div class="input-group-text">kehadiran</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Piket</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[duty]" value="0">
                                            <div class="input-group-text">jam</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Panitia PAT</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[pat]" value="0">
                                            <div class="input-group-text">jam</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">UKM</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[ukm]" value="0">
                                            <div class="input-group-text">jam</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Pengawas</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[invigilator]" value="0">
                                            <div class="input-group-text">soal</div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Jumlah tepat waktu</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[ontime_total]" value="{{ $original['ontime_total'] = $entries->flatten()->filter(fn($entry) => $entry->ontime === true)->count() }}">
                                            <div class="input-group-text">kehadiran</div>
                                        </div>
                                    </div>
                                </div> --}}
                                {{-- <div class="row mb-3">
                                    <label class="col-form-label col-md-4" for="">Jumlah terlambat</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[late_total]" value="{{ $original['late_total'] = $entries->flatten()->filter(fn($entry) => $entry->ontime === false)->count() }}">
                                            <div class="input-group-text">kehadiran</div>
                                        </div>
                                    </div>
                                </div> --}}

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
                            <div class="col-lg-6">
                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Total jam mengajar</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[resultHour]" value="{{ round($hourTotal, 1) }}">
                                            <div class="input-group-text">Jam</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>

                        @if ($userNow !== 3)
                            <div class="form-check mb-3">
                                <input class="form-check-input" id="agreement" type="checkbox" required>
                                <label class="form-check-label" for="agreement">Dengan ini saya selaku {{ Auth::user()->employee->position->position->name ?? 'Human Resource (HR)' }} menyatakan data di atas adalah valid</label>
                            </div>
                        @else
                        @endif

                        <div>
                            <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                            <a class="btn btn-ghost-light text-dark" href="{{ request('next', route('portal::dashboard-msdm.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                        </div>
                        {{-- <input class="position-fixed d-none" type="hidden" name="original" value='{{ json_encode($original) }}'> --}}
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const sumOvertimeTotal = () => {
            let works = 0
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
