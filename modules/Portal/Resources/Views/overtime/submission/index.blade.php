@extends('layouts.dashboarding')

@section('title', 'Lembur | ')

@section('navtitle', 'Lembur')

@include('components.tourguide', [
    'steps' => array_values(
        array_filter(
            [
                [
                    'selector' => '.tg-steps-overtime-submission',
                    'title' => 'Pengajuan lembur',
                    'content' => 'Tekan tombol ini untuk melakukan pengajuan lembur.',
                ],
                [
                    'disabled' => !$approvers->contains($employee->position->id),
                    'selector' => '.tg-steps-overtime-manage',
                    'title' => 'Kelola lembur',
                    'content' => 'Silakan akses menu ini buat mengelola pengajuan lembur karyawan.',
                ],
                [
                    'selector' => '.tg-steps-overtime-filter',
                    'title' => 'Filter riwayat lembur',
                    'content' => 'Gunakan filter ini untuk melihat riwayat lembur pada bulan-bulan sebelumnya.',
                ],
                [
                    'selector' => '.tg-steps-overtime-table',
                    'title' => 'Tabel riwayat lembur',
                    'content' => 'Menampilkan riwayat lembur berdasarkan filter yang diterapkan.',
                ],
            ],
            fn($step) => !($step['disabled'] ?? false))),
])

@section('body-content')
    @include('layouts.component.material-nav')

    <style>
        .material-symbols-rounded {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
        .card-soft {
            border-radius: 1rem;
            border: none;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
        .bg-light-soft {
            background-color: #f8f9fa;
        }
        .timeline-step {
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            z-index: 1;
        }
        .badge-schedule {
            background-color: #f1f3f5;
            color: #495057;
            font-weight: 500;
            margin-bottom: 2px;
            display: inline-block;
            border-radius: 6px;
            padding: 4px 8px;
        }
    </style>

    <div class="container-fluid py-4">
        {{-- Header Halaman --}}
        <div class="d-flex align-items-center mb-4 ps-2">
            <a href="{{ request('next', route('portal::dashboard-msdm.index')) }}" class="btn btn-link text-dark p-0 me-3">
                <span class="material-symbols-rounded" style="font-size: 32px;">arrow_back_ios_new</span>
            </a>
            <div>
                <h3 class="font-weight-bolder mb-0 text-dark">Lembur</h3>
                <p class="text-sm mb-0 text-secondary">Kelola pengajuan lembur dan pantau riwayat kerja lemburmu.</p>
            </div>
        </div>

        {{-- Alerts --}}
        <div class="row px-2">
            @if (Session::has('success') || Session::has('danger'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" class="w-100">
                    <div class="alert {{ Session::has('success') ? 'alert-success' : 'alert-danger' }} border-0 text-white shadow-sm" style="border-radius: 12px;">
                        <div class="d-flex align-items-center">
                            <span class="material-symbols-rounded me-2">{{ Session::has('success') ? 'check_circle' : 'error' }}</span>
                            <span>{{ Session::get('success') ?? Session::get('danger') }}</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="row">
            {{-- Sisi Kiri: Aksi --}}
            <div class="col-xl-4">
                {{-- Card Pengajuan --}}
                <div class="card card-soft tg-steps-overtime-submission mb-4 overflow-hidden">
                    <div class="card-body py-5 text-center position-relative">
                        <div class="mb-3">
                            <a class="btn btn-icon-only btn-rounded btn-outline-primary btn-lg d-inline-flex align-items-center justify-content-center bg-white shadow-sm"
                               href="{{ route('portal::overtime.submission.create', ['next' => url()->full()]) }}"
                               style="width: 70px; height: 70px; border-width: 2px; position: relative; z-index: 2;">
                                <span class="material-symbols-rounded" style="font-size: 36px;">add</span>
                            </a>
                        </div>
                        <h5 class="font-weight-bolder">Buat Pengajuan Baru</h5>
                        <p class="text-muted text-sm px-4">Ada lembur hari ini? Jangan lupa dicatat agar masuk hitungan.</p>
                        <span class="material-symbols-rounded position-absolute text-primary" style="right: -15px; bottom: -15px; font-size: 120px; opacity: 0.05;">work_history</span>
                    </div>
                </div>

                {{-- Action List --}}
                <div class="list-group mb-4">
                    {{-- Manage Overtime (Jika berhak) --}}
                    @if (isset($employee->position->position_id) && in_array($employee->position->position_id, [\Modules\Core\Enums\PositionTypeEnum::KEPALASEKOLAH->value, \Modules\Core\Enums\PositionTypeEnum::HUMAS->value], true))
                        @if ($approvers->contains($employee->position->id))
                            <a href="{{ route('portal::overtime.manage.index', ['next' => url()->current()]) }}" class="list-group-item list-group-item-action card-soft mb-3 border-0 py-3 d-flex align-items-center tg-steps-overtime-manage">
                                <div class="icon icon-shape bg-soft-primary text-primary border-radius-md me-3 d-flex align-items-center justify-content-center">
                                    <span class="material-symbols-rounded">verified_user</span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 text-sm font-weight-bold">Kelola Lembur</h6>
                                    <p class="text-xs text-secondary mb-0">Verifikasi pengajuan karyawan</p>
                                </div>
                                <span class="material-symbols-rounded text-secondary">chevron_right</span>
                            </a>
                        @endif
                    @endif

                    {{-- Export --}}
                    <a href="javascript:;" onclick="exportExcel()" class="list-group-item list-group-item-action card-soft border-0 py-3 d-flex align-items-center">
                        <div class="icon icon-shape bg-soft-success text-success border-radius-md me-3 d-flex align-items-center justify-content-center">
                            <span class="material-symbols-rounded">description</span>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0 text-sm font-weight-bold">Ekspor Data</h6>
                            <p class="text-xs text-secondary mb-0">Unduh riwayat lembur ke Excel</p>
                        </div>
                        <span class="material-symbols-rounded text-secondary">download</span>
                    </a>
                </div>
            </div>

            {{-- Sisi Kanan: Tabel --}}
            <div class="col-xl-8">
                <div class="card card-soft">
                    <div class="card-header pb-2 pt-3 bg-white border-bottom">
                        <div class="d-flex align-items-center justify-content-between px-2">
                            <div class="d-flex align-items-center">
                                <div class="icon icon-sm shadow-sm border-radius-md bg-gradient-primary text-center me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                    <span class="material-symbols-rounded text-white" style="font-size: 18px;">history</span>
                                </div>
                                <h6 class="font-weight-bolder mb-0 text-dark">Riwayat Lembur</h6>
                            </div>
                            <button class="btn btn-sm btn-outline-primary mb-0 py-1 px-3 d-flex align-items-center border-radius-md" data-bs-toggle="collapse" data-bs-target="#collapse-filter">
                                <span class="material-symbols-rounded text-xs me-1">tune</span> Filter
                            </button>
                        </div>
                    </div>

                    {{-- Filter Section --}}
                    <div class="card-body border-bottom pt-0 bg-light-soft tg-steps-overtime-filter">
                        <div class="collapse @if (request('search') || request('start_at')) show @endif" id="collapse-filter">
                            <form action="{{ route('portal::overtime.submission.index') }}" method="get" class="row g-2 mt-2 pb-3 px-2">
                                <div class="col-md-5">
                                    <label class="text-xxs font-weight-bold mb-1 text-secondary text-uppercase ps-1">Cari Kegiatan</label>
                                    <input class="form-control form-control-sm border-radius-md" type="search" name="search" placeholder="Nama atau deskripsi..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-5">
                                    <label class="text-xxs font-weight-bold mb-1 text-secondary text-uppercase ps-1">Periode</label>
                                    <div class="input-group input-group-sm">
                                        <button type="button" class="btn btn-outline-secondary mb-0 py-1 d-none d-sm-block" data-daterangepicker="true" data-daterangepicker-start="[name='start_at']" data-daterangepicker-end="[name='end_at']">
                                            <span class="material-symbols-rounded text-xs">calendar_month</span>
                                        </button>
                                        <input class="form-control" type="date" name="start_at" value="{{ request('start_at') }}">
                                        <input class="form-control" type="date" name="end_at" value="{{ request('end_at') }}">
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex align-items-end gap-1">
                                    <button type="submit" class="btn btn-primary btn-sm flex-grow-1 mb-0 border-radius-md">
                                        <span class="material-symbols-rounded text-sm">search</span>
                                    </button>
                                    <a class="btn btn-light btn-sm mb-0 border-radius-md" href="{{ route('portal::overtime.submission.index') }}">
                                        <span class="material-symbols-rounded text-sm">restart_alt</span>
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Tabel Section --}}
                    <div class="table-responsive tg-steps-overtime-table">
                        <table class="table align-items-center mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-4">Kegiatan</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Jadwal Lembur</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-secondary opacity-7 text-end pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($overtimes as $overtime)
                                    <tr @class(['opacity-6' => $overtime->trashed()])>
                                        <td>
                                            <div class="d-flex px-3 py-2">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm font-weight-bold">{{ $overtime->name }}</h6>
                                                    <p class="text-xs text-secondary mb-0 text-truncate" style="max-width: 200px;">{{ $overtime->description }}</p>
                                                    <small class="text-xxs text-primary font-weight-bold">{{ $overtime->created_at->format('d M Y') }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="py-2">
                                                @php($items = $overtime->schedules ?? $overtime->dates ?? [])
                                                @foreach (collect($items)->take(2) as $date)
                                                    <div class="badge-schedule text-xs">
                                                        <span class="material-symbols-rounded text-xs me-1">event</span>
                                                        {{ date('d M Y', strtotime($date['d'])) }}
                                                        @isset($date['t_s']) <span class="text-secondary ps-1">({{ $date['t_s'] }} - {{ $date['t_e'] ?? '??' }})</span> @endisset
                                                    </div>
                                                @endforeach
                                                @if (count($items) > 2)
                                                    <span class="text-xxs text-secondary ms-1">+{{ count($items)-2 }} lainnya</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            @include('portal::overtime.components.status', ['overtime' => $overtime])
                                        </td>
                                        <td class="align-middle text-end pe-4">
                                            <div class="dropstart">
                                                <button class="btn btn-link text-secondary mb-0 p-0" data-bs-toggle="dropdown">
                                                    <span class="material-symbols-rounded">more_vert</span>
                                                </button>
                                                <ul class="dropdown-menu shadow border-0 py-2">
                                                    <li><a class="dropdown-item d-flex align-items-center" href="{{ route('portal::overtime.submission.show', ['overtime' => $overtime->id, 'next' => request('next')]) }}">
                                                        <span class="material-symbols-rounded text-sm me-2 text-primary">visibility</span> Detail</a>
                                                    </li>
                                                    @if (isset($overtime->attachment) && Storage::exists($overtime->attachment))
                                                        <li><a class="dropdown-item d-flex align-items-center" href="{{ Storage::url($overtime->attachment) }}" target="_blank">
                                                            <span class="material-symbols-rounded text-sm me-2 text-info">attachment</span> Lampiran</a>
                                                        </li>
                                                    @endif
                                                    @if($overtime->hasApprovables() && !$overtime->trashed())
                                                        <li><a class="dropdown-item d-flex align-items-center" href="javascript:;" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $overtime->id }}">
                                                            <span class="material-symbols-rounded text-sm me-2 text-warning">step_order</span> Lacak</a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- Approval Tracking --}}
                                    @if ($overtime->hasApprovables() && !$overtime->trashed())
                                        <tr class="collapse @if ($overtime->hasAnyApprovableResultIn('PENDING')) show @endif" id="collapse-{{ $overtime->id }}">
                                            <td colspan="4" class="bg-light-soft py-4 px-5">
                                                <div class="timeline timeline-one-side" style="border-left: 2px dashed #dee2e6; margin-left: 14px;">
                                                    @foreach ($overtime->approvables as $approvable)
                                                        <div class="timeline-block mb-3 position-relative" style="padding-left: 30px;">
                                                            <span class="timeline-step position-absolute" style="left: -15px; top: 0;">
                                                                <span class="material-symbols-rounded text-{{ $approvable->result->color() }}" style="font-size: 18px;">
                                                                    {{ $approvable->result->icon() == 'mdi mdi-check' ? 'check_circle' : ($approvable->result->icon() == 'mdi mdi-close' ? 'cancel' : 'hourglass_top') }}
                                                                </span>
                                                            </span>
                                                            <div class="timeline-content">
                                                                <h6 class="text-dark text-xs font-weight-bold mb-0">
                                                                    {{ ucfirst($approvable->type) }} Level {{ $approvable->level }}
                                                                </h6>
                                                                <p class="text-secondary text-xxs mt-1 mb-0">{{ $approvable->userable->getApproverLabel() }}</p>
                                                                @if($approvable->reason) <p class="text-xs italic mb-0 text-muted">"{{ $approvable->reason }}"</p> @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr><td colspan="4" class="text-center py-5">@include('components.notfound')</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer py-3 border-top">
                        {{ $overtimes->appends(request()->all())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('js/vendor/moment.min.js') }}"></script>
    <script src="{{ asset('js/vendor/daterangepicker.js') }}"></script>
    <script src="{{ asset('vendor/excel/excel.min.js') }}"></script>
    @include('portal::overtime.components.excel-script')
@endpush
