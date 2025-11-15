@extends('finance::layouts.default')

@section('title', 'Rekapitulasi presensi mengajar | ')
@section('navtitle', 'Rekapitulasi presensi mengajar')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('finance::summary.teachings.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Buat rekap mengajar baru</h2>
            <div class="text-secondary">Anda dapat membuat rekap presensi mengajar dengan mengisi formulir di bawah</div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-4">
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-history"></i> Riwayat mengajar
                </div>
                <div class="table-responsive border-top" style="overflow: auto; max-height: 960px;">
                    <table class="mb-0 table">
                        <tbody>
                            @forelse ($entries as $date => $shifts)
                                @foreach ($shifts as $entry)
                                    <tr>
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

                                        <td>{{ mapel($entry->lesson)->name ?? '-' }}</td>
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
                    <form class="form-block form-confirm" action="{{ route('finance::summary.teachings.store', ['employee' => $employee->id, 'start_at' => $start_at->format('Y-m-d'), 'end_at' => $end_at->format('Y-m-d'), 'next' => request('next', route('hrms::summary.attendances.index'))]) }}" method="post"> @csrf
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
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[unpresence][leave]" value="{{ $leaves->flatMap(fn($leave) => collect($leave->dates)->pluck('d'))->filter(fn($date) => $start_at->lte($date) && $end_at->gte($date))->count() ?? 0 }}">
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
                            </div>
                            <div class="col-lg-6">
                                <h6 class="fw-bold mb-3">Rekapitulasi Kehadiran Mengajar</h6>
                                
                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Jumlah kehadiran</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[attendance_total]" value="{{ count($entries) }}">
                                            <div class="input-group-text">kehadiran</div>
                                        </div>
                                    </div>
                                </div>

                                <h6>Rekapitulasi Piket</h6>

                                 <div class="row align-items-center mb-5">
                                    <label class="col-form-label col-md-4" for="">Jumlah piket</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[duty]" value="{{ collect(optional($scheduleDuty->first())->dates ?? [])->filter(fn($shifts) => !empty($shifts[0]) && !empty($shifts[1]))->count() }}">
                                            <div class="input-group-text">kehadiran</div>
                                        </div>
                                    </div>
                                </div>

                                <h6 class="fw-bold mb-3">Rincian Rekapitulasi Piket</h6>
                        
                                <div class="row align-items-center mb-2">
                                    <table class="mb-0 table">
                                        <tbody>
                                            @forelse (optional($scheduleDuty->first())->dates ?? [] as $date => $shifts)
                                                @if(!empty($shifts[0]) && !empty($shifts[1]))
                                                    @foreach ($shifts[2] as $shift => $location)
                                                        <tr>
                                                            <td>
                                                                <span @if ($moment = $moments->firstWhere('date', $date)) data-bs-toggle="tooltip" title="" data-bs-placement="right" data-bs-original-title="{{ $moment->name }}" @endif @class(['fw-bold', 'text-danger' => $moment])>
                                                                    {{ strftime('%A, %d %b %Y', strtotime($date)) }}
                                                                    @if ($moment)
                                                                        <i class="mdi mdi-information-outline"></i>
                                                                    @endif
                                                                </span>
                                                            </td>
                                                            <td>
                                                                {{ $shift == 1 ? 'Shift 1' : 'Shift 2' }}
                                                            </td>
                                                            <td>
                                                                {{ $location == 1 ? 'Putri' : 'Putra' }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
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
                        <div class="row">
                            <h6 class="fw-bold mb-3">Jam Mengajar</h6>

                            <div class="col-lg-6">
                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Total jam mengajar</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[amount_total]" value="{{ round($total, 1) }}">
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
                            <a class="btn btn-ghost-light text-dark" href="{{ request('next', route('finance::summary.teachings.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                        </div>
                        <input class="position-fixed d-none" type="hidden" name="original" value='{{ json_encode($original ?? []) }}'>
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
