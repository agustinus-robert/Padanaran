@extends('hrms::layouts.default')

@section('title', 'Detail perjanjian kerja | ')
@section('navtitle', 'Detail perjanjian kerja')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('hrms::employment.employees.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Lihat detail perjanjian kerja</h2>
            <div class="text-secondary">Menampilkan informasi perjanjian kerja, detail kontak, alamat, dan peran.</div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="card border-0">
                <div class="card-body">
                    <h4 class="mb-1">Info perjanjian kerja</h4>
                    <p class="text-muted mb-2">Informasi detail perjanjian kerja</p>
                </div>
                <div class="list-group list-group-flush">
                    <div class="list-group-item border-0">
                        Nama karyawan <br> <strong>{{ $contract->employee->user->name }}</strong>
                    </div>
                    <div class="list-group-item border-0">
                        Dibuat pada <br> <strong> {{ $contract->created_at->diffForHumans() }}</strong>
                    </div>
                    <div class="list-group-item border-0">
                        Masa berlaku <br> <i class="mdi mdi-circle {{ $contract->is_active ? 'text-success' : 'text-danger' }}" style="font-size: 11pt;"></i> &nbsp; <strong>{{ $contract->start_at->isoFormat('LL') }}</strong> s.d. <strong>{{ $contract->end_at?->isoFormat('LLL') ?: 'tidak ditentukan' }}</strong>
                    </div>
                    <div class="list-group-item border-0">
                        Lokasi kerja <br> <strong>{{ $contract->work_location->label() }}</strong>
                    </div>
                    <div class="list-group-item text-muted border-0">
                        <i class="mdi mdi-account-circle"></i> ID perjanjian kerja : {{ $contract->id }}
                    </div>
                    <a class="list-group-item border-top {{ $contract->document ? 'text-danger' : 'text-muted' }} py-3" @isset($contract->document) href="{{ $contract->document->url() ?: 'javascript:;' }}" download="{{ $contract->document->label }}" @endisset>
                        <i class="mdi mdi-file-download-outline"></i> Unduh dokumen perjanjian kerja
                    </a>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-tag-outline"></i> Jabatan
                </div>
                <div class="list-group list-group-flush border-top">
                    @forelse($contract->positions as $position)
                        <div class="list-group-item">
                            <div class="fw-bold">{{ $position->position->name }}</div>
                            <div class="text-muted">{{ $position->position->department->name }}</div>
                            <i class="mdi mdi-circle {{ $position->is_active ? 'text-success' : 'text-danger' }}" style="font-size: 11pt;"></i> &nbsp; <strong>{{ $position->start_at->isoFormat('LL') }}</strong> s.d. <strong>{{ $position->end_at?->isoFormat('LLL') ?: 'tidak ditentukan' }}</strong>
                        </div>
                    @empty
                        <div class="list-group-item">
                            <div class="text-muted">Belum ada jabatan yang dibuat, klik tombol di bawah untuk menambahkan.</div>
                        </div>
                    @endforelse
                </div>
                <div class="card-body border-top">
                    <a class="btn btn-soft-danger" href="{{ route('hrms::employment.contracts.positions.create', ['contract' => $contract->id, 'next' => url()->full()]) }}"><i class="mdi mdi-plus"></i> Tambahkan jabatan baru</a>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-file-edit-outline"></i> Adendum perjanjian
                </div>
                <div class="table-responsive">
                    <table class="table-hover mb-0 table align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Jenis adendum</th>
                                <th>Dokumen</th>
                                <th>Masa berlaku</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4" class="text-muted py-4">Belum ada adendum perjanjian kerja yang ditambahkan, klik tombol di bawah untuk menambahkan.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-body">
                    <a class="btn btn-soft-danger disabled" href="javascript:;"><i class="mdi mdi-plus"></i> Buat adendum</a>
                </div>
            </div>
            <div class="card border-0">
                <a class="card-body text-dark" href="javascript:;" data-bs-toggle="collapse" data-bs-target="#worktimes_default">
                    <i class="mdi mdi-account-clock-outline"></i> Jam kerja bawaan
                    <i class="mdi mdi-chevron-down float-end"></i>
                </a>
                <div class="collapse" id="worktimes_default">
                    <form class="form-block" action="{{ route('hrms::employment.contracts.workdays', ['contract' => $contract->id]) }}" method="post"> @csrf @method('PUT')
                        <div class="table-responsive border-top">
                            <table class="table-hover mb-0 table align-middle">
                                <thead>
                                    <tr>
                                        <th>Hari</th>
                                        @foreach ($workshifts as $shift)
                                            <th class="text-center">{{ $shift->label() }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($workdays as $day)
                                        <tr>
                                            <td class="ps-3">{{ $day->label() }}</td>
                                            @foreach ($workshifts as $i => $shift)
                                                <td>
                                                    <div class="input-group">
                                                        <input type="time" class="form-control" name="worktimes_default[{{ $day->value }}][{{ $i }}][]" value="{{ $contract->getMeta('worktimes_default')[$day->value][$i][0] ?? '' }}">
                                                        <div class="input-group-text">s.d.</div>
                                                        <input type="time" class="form-control" name="worktimes_default[{{ $day->value }}][{{ $i }}][]" value="{{ $contract->getMeta('worktimes_default')[$day->value][$i][1] ?? '' }}">
                                                    </div>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-body">
                            <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan jam kerja</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0">
                <div class="card-body d-flex align-items-center justify-content-between py-2">
                    <div><i class="mdi mdi-account-clock-outline"></i> Jadwal kerja</div>
                    <form action="{{ route('hrms::employment.contracts.show', ['contract' => $contract->id]) }}" method="GET">
                        <div class="input-group input-group-sm">
                            <input class="form-control" type="month" name="workdays_period" value="{{ request('workdays_period', date('Y-m')) }}">
                            <button class="btn btn-dark"><i class="mdi mdi-eye-outline"></i> Tampilkan</button>
                        </div>
                    </form>
                </div>
                <div class="card-body border-top">
                    @if ($schedule = $schedules->first())
                        <div style="max-height: 360px; overflow-y: auto;">
                            <div class="row d-none d-xl-block sticky-top border-bottom bg-white pb-2">
                                <div class="col-xl-9 col-xxl-9 offset-lg-4 offset-xl-3">
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
                                    <label class="col-lg-4 col-xl-3 {{ $moment ? 'text-danger' : '' }} mb-1">
                                        <span @if ($moment) data-bs-toggle="tooltip" title="{{ $moment->name }}" data-bs-placement="right" @endif>
                                            {{ strftime('%A, %d %B %Y', strtotime($date)) }} @if ($moment)
                                                <i class="mdi mdi-information-outline"></i>
                                            @endif
                                        </span>
                                    </label>
                                    <div class="col-xl-9 col-xxl-9">
                                        <div class="row">
                                            @foreach ($workshifts as $i => $shift)
                                                <div class="col-xl-{{ 12 / count($workshifts) }} text-xl-center">
                                                    <span>{{ isset($schedule->dates[$date][$i][0]) ? date('H:i', strtotime($schedule->dates[$date][$i][0])) : '-' }}</span>
                                                    s.d.
                                                    <span>{{ isset($schedule->dates[$date][$i][1]) ? date('H:i', strtotime($schedule->dates[$date][$i][1])) : '-' }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-muted">Belum ada jadwal kerja di periode ini, silakan <a href="{{ route('hrms::service.attendance.schedules.create', ['contract' => $contract->id, 'period' => request('workdays_period', date('Y-m')), 'next' => url()->current()]) }}">tambahkan jadwal kerja baru</a> untuk menambahkan di periode ini</div>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection
