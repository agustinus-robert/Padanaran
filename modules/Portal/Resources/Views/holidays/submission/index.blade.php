@extends('portal::layouts.default')

@section('title', 'Pengajuan libur | ')

@include('components.tourguide', [
    'steps' => array_filter([
        [
            'selector' => '.tg-steps-holiday-submission',
            'title' => 'Pengajuan libur',
            'content' => 'Tekan tombol ini untuk melakukan pengajuan libur.',
        ],
        [
            'selector' => '.tg-steps-holiday-count',
            'title' => 'Statistik libur',
            'content' => 'Kolom ini menampilkan statistik libur yang telah kamu gunakan di tahun ini.',
        ],
        [
            'selector' => '.tg-steps-holiday-filter',
            'title' => 'Filter riwayat libur',
            'content' => 'Gunakan filter ini untuk melihat riwayat libur pada bulan-bulan sebelumnya.',
        ],
        [
            'selector' => '.tg-steps-holiday-table',
            'title' => 'Tabel riwayat libur',
            'content' => 'Menampilkan riwayat libur berdasarkan filter yang diterapkan.',
        ],
    ]),
])

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('portal::home')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Libur</h2>
            <div class="text-muted">Ajukan libur harian kamu!</div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-4">
            <div class="card tg-steps-holiday-submission border-0">
                <div class="card-body py-4 text-center">
                    <div class="my-4">
                        <a class="btn btn-soft-danger rounded-circle d-flex justify-content-center align-items-center mx-auto" href="{{ route('portal::holiday.submission.create', ['next' => url()->full()]) }}" style="width: 100px; height: 100px;"><i class="mdi mdi-exit-to-app mdi-48px"></i></a>
                    </div>
                    <h4 class="mb-1">Pengajuan baru</h4>
                    <p class="text-muted mb-0">Silakan tekan tombol di atas untuk mengajukan tanggal libur baru</p>
                </div>
            </div>
            <div class="card tg-steps-holiday-count border-0">
                <div class="card-body border-top py-4">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <div class="small text-uppercase">Jumlah libur yang diambil</div>
                            <div class="small text-muted">Tahun {{ date('Y') }}</div>
                        </div>
                        <div class="col-4">
                            <div class="h1 mb-0 text-end">{{ $holiday_this_year_count ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @if (in_array($employee->position?->position->level->value ?: 0, array_column(config('modules.core.features.services.leaves.approvable_steps', []), 'value')))
                <div class="list-group mb-4">
                    <a class="list-group-item list-group-item-action p-4" href="{{ route('portal::holiday.manage.index', ['next' => url()->current()]) }}" style="border-style: dashed;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-inline-block bg-soft-secondary text-danger me-2 rounded text-center" style="height: 36px; width: 36px;">
                                <i class="mdi mdi-calendar-check-outline mdi-24px"></i>
                            </div>
                            <div class="flex-grow-1">Kelola pengajuan libur</div>
                            <i class="mdi mdi-chevron-right-circle-outline"></i>
                        </div>
                    </a>
                </div>
            @endif
        </div>
        <div class="col-xl-8">
            <div class="card border-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <i class="mdi mdi-calendar-multiselect"></i> Kalender hari libur
                    </div>
                    <label class="btn btn-outline-secondary text-dark btn-sm rounded px-2 py-1" data-bs-toggle="collapse" data-bs-target="#collapse-filter" for="collapse-btn"><i class="mdi mdi-filter-outline"></i> <span class="d-none d-sm-inline">Filter</span></label>
                </div>
                <div class="collapse" id="collapse-filter">
                    <div class="card-body">
                        <form class="tg-steps-presence-history" action="{{ route('portal::holiday.submission.index') }}" method="GET">
                            <div class="d-flex">
                                <div class="flex-grow-1 col-auto">
                                    <div class="input-group">
                                        <div class="input-group-text"><span class="d-inline d-sm-none"><i class="mdi mdi-sort-clock-descending-outline"></i></span><span class="d-none d-sm-inline">Periode</span></div>
                                        <button type="button" class="btn btn-light dropdown-toggle d-none d-sm-block" data-daterangepicker="true" data-daterangepicker-start="[name='start_at']" data-daterangepicker-end="[name='end_at']">Rentang waktu</button>
                                        <input class="form-control" type="date" name="start_at" value="{{ request('start_at') }}">
                                        <input class="form-control" type="date" name="end_at" value="{{ request('end_at') }}">
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <a class="btn btn-light" href="{{ route('portal::holiday.submission.index') }}"><i class="mdi mdi-refresh"></i> <span class="d-sm-none">Reset</span></a>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-dark"><i class="mdi mdi-magnify"></i> Cari</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="table-responsive table-responsive-xl tg-steps-leave-table">
                    <table class="mb-0 table align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th nowrap>Tgl pengajuan</th>
                                <th class="text-center">Periode</th>
                                <th nowrap>Tgl libur</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($holidays as $holiday)
                                <tr @if ($holiday->trashed()) class="text-muted" @endif>
                                    <td class="text-center">{{ $loop->iteration + $holidays->firstItem() - 1 }}</td>
                                    <td class="small">{{ $holiday->created_at->formatLocalized('%d %B %Y') }}</td>
                                    <td style="min-width: 200px;" class="py-3">
                                        <div class="justify-content-center align-items-center d-flex">
                                            @if (!$holiday->start_at->isSameDay($holiday->end_at))
                                                <div class="">
                                                    <h6 class="mb-0">{{ $holiday->start_at->format('d-M') }}</h6> <small class="text-muted">{{ $holiday->start_at->format('Y') }}</small>
                                                </div>
                                                <div class="text-muted small mx-2">&mdash; s.d. &mdash;</div>
                                            @endif
                                            <div class="">
                                                <h6 class="mb-0">{{ $holiday->end_at->format('d-M') }}</h6> <small class="text-muted">{{ $holiday->end_at->format('Y') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="min-width: 200px;">
                                        @foreach ($holiday->dates->take(3) as $date)
                                            <span class="badge bg-soft-secondary text-dark fw-normal user-select-none {{ isset($date['c']) ? 'text-decoration-line-through' : '' }}" @isset($date['f']) data-bs-toggle="tooltip" title="Sebagai freelancer" @endisset>
                                                @isset($date['f'])
                                                    <i class="mdi mdi-account-network-outline text-danger"></i>
                                                @endisset
                                                {{ strftime('%d %B %Y', strtotime($date['d'])) }}
                                            </span>
                                        @endforeach
                                        @php($remain = $holiday->dates->count() - 3)
                                        @if ($remain > 0)
                                            <span class="badge text-dark fw-normal user-select-none">+{{ $remain }} lainnya</span>
                                        @endif
                                    </td>
                                    <td nowrap class="py-1 text-end">
                                        @unless ($holiday->trashed())
                                            @if ($holiday->hasApprovables())
                                                <span data-bs-toggle="collapse" data-bs-target="#collapse-{{ $holiday->id }}">
                                                    <button class="btn btn-soft-primary btn-sm rounded px-2 py-1" data-bs-toggle="tooltip" title="Status pengajuan"><i class="mdi mdi-progress-clock"></i></button>
                                                </span>
                                            @endif
                                            <div class="dropstart d-inline">
                                                <button class="btn btn-soft-secondary text-dark rounded px-2 py-1" type="button" data-bs-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></button>
                                                <ul class="dropdown-menu border-0 shadow">
                                                    <li><a class="dropdown-item" href="{{ route('portal::holiday.submission.show', ['holiday' => $holiday->id, 'next' => request('next')]) }}"><i class="mdi mdi-eye-outline me-1"></i> Lihat detail</a></li>
                                                    <li><a class="dropdown-item" href="{{ route('portal::holiday.print', ['holiday' => $holiday->id]) }}" target="_blank"><i class="mdi mdi-printer-outline me-1"></i> Cetak dokumen (.pdf)</a></li>
                                                </ul>
                                            </div>
                                        @endunless
                                    </td>
                                </tr>
                                @if ($holiday->hasApprovables() && !$holiday->trashed())
                                    <tr>
                                        <td class="p-0" colspan="6">
                                            <div class="@if ($holiday->hasAnyApprovableResultIn('PENDING')) show @endif collapse" id="collapse-{{ $holiday->id }}">
                                                <table class="table-borderless table-hover table-sm mb-0 table align-middle">
                                                    <thead>
                                                        <tr class="text-muted small bg-light">
                                                            <th class="border-bottom fw-normal">Jenis</th>
                                                            <th class="border-bottom fw-normal" colspan="2">Persetujuan</th>
                                                            <th class="border-bottom fw-normal">Penanggungjawab</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($holiday->approvables as $approvable)
                                                            <tr>
                                                                <td class="small {{ $approvable->cancelable ? 'text-danger' : 'text-muted' }}">{{ ucfirst($approvable->type) }} #{{ $approvable->level }}</td>
                                                                <td @if ($loop->last) class="border-0" @endif>
                                                                    <div class="badge bg-{{ $approvable->result->color() }} fw-normal text-white"><i class="{{ $approvable->result->icon() }}"></i> {{ $approvable->result->label() }}</div>
                                                                </td>
                                                                <td class="small ps-0">{{ $approvable->reason }}</td>
                                                                <td class="small">{{ $approvable->userable->getApproverLabel() }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="5">@include('components.notfound')</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-body">
                    {{ $holidays->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/vendor/moment.min.js') }}"></script>
    <script src="{{ asset('js/vendor/daterangepicker.js') }}"></script>
@endpush
