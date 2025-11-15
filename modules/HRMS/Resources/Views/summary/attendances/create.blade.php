@extends('hrms::layouts.default')

@section('title', 'Rekapitulasi presensi | ')
@section('navtitle', 'Rekapitulasi presensi')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('hrms::summary.attendances.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Buat rekap presensi baru</h2>
            <div class="text-secondary">Anda dapat membuat rekap presensi dengan mengisi formulir di bawah</div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-4">
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-history"></i> Riwayat presensi
                </div>
                <div class="table-responsive border-top" style="overflow: auto; max-height: 720px;">
                    <table class="mb-0 table">
                        <tbody>
                            @forelse ($entries as $date => $shifts)
                                @foreach ($shifts as $entry)
                                    <tr>
                                        <td>{{ $loop->parent->iteration }}</td>
                                        <td>
                                            <span @if ($moment = $moments->firstWhere('date', $date)) data-bs-toggle="tooltip" title="" data-bs-placement="right" data-bs-original-title="{{ $moment->name }}" @endif @class(['fw-bold', 'text-danger' => $moment])>
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
                                                    {{ $locations[$v] }}
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
                                        </td>
                                        <td @class([
                                            'text-danger' => !$entry->ontime,
                                            'text-center',
                                        ])>{{ $entry->in?->format('H:i:s') ?? '-' }}</td>
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
        <div class="col-xl-8 order-first">
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
                    <form class="form-block form-confirm" action="{{ route('hrms::summary.attendances.store', ['employee' => $employee->id, 'start_at' => $start_at->format('Y-m-d'), 'end_at' => $end_at->format('Y-m-d'), 'next' => request('next', route('hrms::summary.attendances.index'))]) }}" method="post"> @csrf
                        <div class="row gy-4">
                            <div class="col-lg-6">
                                <h6 class="fw-bold mb-3">Rekapitulasi keseluruhan</h6>
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
                                @if ($adtList->count() > 0)
                                    <div class="row align-items-center mb-2">
                                        <label class="col-form-label col-md-4" for="">Presensi kerja</label>
                                        <div class="col-md-7">
                                            <div class="input-group">
                                                <input class="form-control" type="number" min="0" step="0.1" name="summary[attendance_work]" value="{{ $presences->count() }}">
                                                <div class="input-group-text">hari</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-2">
                                        <label class="col-form-label col-md-4" for="">Pekerjaan lainnya</label>
                                        <div class="col-md-7">
                                            <div class="input-group">
                                                <input class="form-control" type="number" min="0" step="1" name="summary[additional_workdays]" value="{{ $adtDays }}">
                                                <div class="input-group-text">hari</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Jumlah presensi</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[attendance_total]" value="{{ $presences->count() + $adtDays }}">
                                            <div class="input-group-text">hari</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Jumlah tepat waktu</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[ontime_total]" value="{{ $original['ontime_total'] = $entries->flatten()->filter(fn($entry) => $entry->ontime === true)->count() }}">
                                            <div class="input-group-text">hari</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-form-label col-md-4" for="">Jumlah terlambat</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[late_total]" value="{{ $original['late_total'] = $entries->flatten()->filter(fn($entry) => $entry->ontime === false)->count() }}">
                                            <div class="input-group-text">hari</div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <h6 class="fw-bold mb-3">Rekapitulasi berdasar lokasi</h6>
                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Presensi</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[presence][wfo]" value="{{ $presences->filter(fn($entry) => count($entry->location) == 1 && $entry->location[0] == 1)->count() + $presences->filter(fn($entry) => count($entry->location) == 2)->count() + $adtDays }}">
                                            <div class="input-group-text">hari</div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Presensi WFA</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[presence][wfa]" value="{{ $presences->filter(fn($entry) => count($entry->location) == 1 && $entry->location[0] == 2)->count() }}">
                                            <div class="input-group-text">hari</div>
                                        </div>
                                    </div>
                                </div> --}}
                                {{-- <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Jumlah WFO ke WFA</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[presence][move]" value="{{ $presences->filter(fn($entry) => count($entry->location) == 2)->count() }}">
                                            <div class="input-group-text">hari</div>
                                        </div>
                                    </div>
                                </div>
                                <br> --}}
                                <h6 class="fw-bold mb-3">Rekapitulasi tunjangan kehadiran</h6>
                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Presensi Ontime</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[ontime][wfo]" value="{{ $presences->filter(fn($entry) => $entry->ontime === true && count($entry->location) == 1 && $entry->location[0] == 1)->count() }}">
                                            <div class="input-group-text">hari</div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Presensi WFA Ontime</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[ontime][wfa]" value="{{ $presences->filter(fn($entry) => $entry->ontime === true && count($entry->location) == 1 && $entry->location[0] == 2)->count() }}">
                                            <div class="input-group-text">hari</div>
                                        </div>
                                    </div>
                                </div> --}}
                                {{-- <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Jumlah WFA ke WFO Ontime</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[ontime][move]" value="{{ $entries->flatten()->filter(fn($entry) => $entry->ontime === true && count($entry->location) == 2)->count() }}">
                                            <div class="input-group-text">hari</div>
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                            <div class="col-lg-6">
                                <h6 class="fw-bold mb-3">Rekapitulasi perizinan</h6>
                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Jumlah izin</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[unpresence][leave]" value="{{ $leaves->flatMap(fn($leave) => $leave->dates->pluck('d'))->filter(fn($date) => $start_at->lte($date) && $end_at->gte($date))->count() ?? 0 }}">
                                            <div class="input-group-text">hari</div>
                                        </div>
                                    </div>
                                </div>
                                @foreach (Modules\Core\Enums\VacationTypeEnum::cases() as $type)
                                    <div class="row align-items-center mb-2">
                                        <label class="col-form-label col-md-4" for="">Jumlah {{ $type->label() }}</label>
                                        <div class="col-md-7">
                                            <div class="input-group">
                                                <input class="form-control" type="number" min="0" step="0.1" name="summary[unpresence][vacation][{{ strtolower($type->name) }}]" value="{{ $vacations->where('quota.category.type', $type)->pluck('dates')->flatten(1)->filter(fn($v) => empty($v['cashable']) && $start_at->lte($v['d']) && $end_at->gte($v['d']))->count() ?? 0 }}">
                                                <div class="input-group-text">hari</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                {{-- <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Kompensasi cuti</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[unpresence][cashable_vacation]" value="{{ $vacations->pluck('dates')->flatten(1)->filter(fn($v) => isset($v['cashable']) && $start_at->lte($v['d']) && $end_at->gte($v['d']))->count() ?? 0 }}">
                                            <div class="input-group-text">hari</div>
                                        </div>
                                    </div>
                                </div> --}}
                                <h6 class="fw-bold mb-3">Rekapitulasi lembur</h6>
                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Jumlah lembur kelebihan hari</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            @php($overtime_days_minutes = $employee->position->position->getMeta('payroll_sum_overdays', false) ? $overtime_days->map(fn($overday) => (float) number_format($overday->schedule[0]->diffInMinutes($overday->schedule[1]) / 60, 2))->sum() ?? 0 : 0)
                                            <input class="form-control" type="number" min="0" step="0.01" name="summary[overtime][overdays]" onkeyup="sumOvertimeTotal()" value="{{ number_format($overtime_days_minutes, 2) }}">
                                            <div class="input-group-text">jam</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Jumlah lembur tanggal merah</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            @php($overtime_holidays_minutes = $overtime_holidays->map(fn($overday) => (float) number_format($overday->schedule[0]->diffInMinutes($overday->schedule[1]) / 60, 2))->sum())
                                            <input class="form-control" type="number" min="0" step="0.01" name="summary[overtime][holidays]" onkeyup="sumOvertimeTotal()" value="{{ number_format($overtime_holidays_minutes, 2) }}">
                                            <div class="input-group-text">jam</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Jumlah lembur keseluruhan</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.01" readonly name="summary[overtime][total]" value="{{ number_format($overtime_days_minutes + $overtime_holidays_minutes, 2) }}">
                                            <div class="input-group-text">jam</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-check mb-3">
                            <input class="form-check-input" id="agreement" type="checkbox" required>

                            <label class="form-check-label" for="agreement">Dengan ini saya selaku Human Resource (HR) menyatakan data di atas adalah valid</label>
                        </div>
                        <div>
                            <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                            <a class="btn btn-ghost-light text-dark" href="{{ request('next', route('hrms::service.attendance.schedules.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                        </div>
                        <input class="position-fixed d-none" type="hidden" name="original" value='{{ json_encode($original) }}'>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const sumOvertimeTotal = () => {

            // let works = parseFloat(document.querySelector('[name="summary[overtime][works]"]').value || 0)
            let works = 0

            let overdays = parseFloat(document.querySelector('[name="summary[overtime][overdays]"]').value || 0)
            let holidays = parseFloat(document.querySelector('[name="summary[overtime][holidays]"]').value || 0)
            document.querySelector('[name="summary[overtime][total]"]').value = (works + overdays + holidays).toFixed(2);
        }
    </script>
@endpush
