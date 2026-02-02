@extends('portal::layouts.index')

@section('title', 'Kegiatan Lainnya | ')

@section('navtitle', 'Insentif')

@include('components.tourguide', [
    'steps' => array_values(
        array_filter(
            [
                [
                    'selector' => '.tg-steps-outwork-submission',
                    'title' => 'Pengajuan kegiatan lainnya',
                    'content' => 'Tekan tombol ini untuk melakukan pengajuan kegiatan lainnya.',
                ],
                [
                    'disabled' => !$approvers->contains($employee->position->id),
                    'selector' => '.tg-steps-outwork-manage',
                    'title' => 'Kelola kegiatan lainnya',
                    'content' => 'Silakan akses menu ini buat mengelola pengajuan kegiatan lainnya karyawan.',
                ],
                [
                    'selector' => '.tg-steps-outwork-filter',
                    'title' => 'Filter riwayat kegiatan lainnya',
                    'content' => 'Gunakan filter ini untuk melihat riwayat kegiatan lainnya pada bulan-bulan sebelumnya.',
                ],
                [
                    'selector' => '.tg-steps-outwork-table',
                    'title' => 'Tabel riwayat kegiatan lainnya',
                    'content' => 'Menampilkan riwayat kegiatan lainnya berdasarkan filter yang diterapkan.',
                ],
            ],
            fn($step) => !($step['disabled'] ?? false))),
])

@section('contents')
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
        .badge-date {
            background-color: #f1f3f5;
            color: #495057;
            font-weight: 500;
            border-radius: 6px;
            padding: 4px 8px;
            display: inline-block;
            margin-bottom: 2px;
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
    </style>

    <div class="container-fluid py-4">
        {{-- Header --}}
        <div class="d-flex align-items-center mb-4 ps-2">
            <a href="{{ request('next', route('portal::dashboard-msdm.index')) }}" class="btn btn-link text-dark p-0 me-3">
                <span class="material-symbols-rounded" style="font-size: 32px;">arrow_back_ios_new</span>
            </a>
            <div>
                <h3 class="font-weight-bolder mb-0 text-dark">Insentif Kegiatan</h3>
                <p class="text-sm mb-0 text-secondary">Catat kegiatan tambahanmu dan pantau proses insentifnya.</p>
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
                <div class="card card-soft tg-steps-outwork-submission mb-4 overflow-hidden">
                    <div class="card-body py-4 text-center position-relative">
                        <div class="my-3">
                            <a class="btn btn-icon-only btn-rounded btn-outline-primary btn-lg d-inline-flex align-items-center justify-content-center bg-white shadow-sm"
                               href="{{ route('portal::outwork.submission.create', ['next' => url()->full()]) }}"
                               style="width: 70px; height: 70px; border-width: 2px;">
                                <span class="material-symbols-rounded text-primary" style="font-size: 36px;">add_task</span>
                            </a>
                        </div>
                        <h5 class="font-weight-bolder">Pengajuan Baru</h5>
                        <p class="text-muted text-sm px-3">Ada kegiatan di luar tugas utama? Laporkan di sini untuk mendapatkan insentif.</p>
                        <span class="material-symbols-rounded position-absolute text-primary" style="right: -15px; bottom: -15px; font-size: 100px; opacity: 0.05;">payments</span>
                    </div>
                </div>

                {{-- Admin Actions --}}
                @if (isset($employee->position->position_id) && in_array($employee->position->position_id, [Modules\Core\Enums\PositionTypeEnum::KEPALASEKOLAH->value, Modules\Core\Enums\PositionTypeEnum::HUMAS->value], true))
                    @if ($approvers->contains($employee->position->id))
                        <div class="tg-steps-outwork-manage list-group mb-4">
                            <a href="{{ route('portal::outwork.manage.index', ['next' => url()->current()]) }}" class="list-group-item list-group-item-action card-soft border-0 mb-3 py-3 d-flex align-items-center">
                                <div class="icon icon-shape bg-soft-info text-info border-radius-md me-3 d-flex align-items-center justify-content-center">
                                    <span class="material-symbols-rounded">fact_check</span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 text-sm font-weight-bold">Kelola Insentif</h6>
                                    <p class="text-xs text-secondary mb-0">Verifikasi kegiatan karyawan</p>
                                </div>
                                <span class="material-symbols-rounded text-secondary">chevron_right</span>
                            </a>
                        </div>
                    @endif
                @endif
            </div>

            {{-- Sisi Kanan: Tabel Riwayat --}}
            <div class="col-xl-8">
                <div class="card card-soft">
                    <div class="card-header pb-2 pt-3 bg-white border-bottom">
                        <div class="d-flex align-items-center justify-content-between px-2">
                            <div class="d-flex align-items-center">
                                <div class="icon icon-sm shadow-sm border-radius-md bg-gradient-primary text-center me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                    <span class="material-symbols-rounded text-white" style="font-size: 18px;">history</span>
                                </div>
                                <h6 class="font-weight-bolder mb-0 text-dark">Riwayat Kegiatan</h6>
                            </div>
                            <button class="btn btn-sm btn-outline-primary mb-0 py-1 px-3 d-flex align-items-center border-radius-md" data-bs-toggle="collapse" data-bs-target="#collapse-filter">
                                <span class="material-symbols-rounded text-xs me-1">tune</span> Filter
                            </button>
                        </div>
                    </div>

                    {{-- Filter --}}
                    <div class="card-body border-bottom pt-0 bg-light-soft tg-steps-outwork-filter">
                        <div class="collapse @if (request('search')) show @endif" id="collapse-filter">
                            <form action="{{ route('portal::outwork.submission.index') }}" method="get" class="row g-2 mt-2 pb-3 px-2">
                                <div class="col-md-5">
                                    <label class="text-xxs font-weight-bold mb-1 text-secondary text-uppercase ps-1">Cari Kegiatan</label>
                                    <input class="form-control form-control-sm border-radius-md" type="search" name="search" placeholder="Nama kegiatan..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-5">
                                    <label class="text-xxs font-weight-bold mb-1 text-secondary text-uppercase ps-1">Periode</label>
                                    <div class="input-group input-group-sm">
                                        <button type="button" class="btn btn-outline-secondary mb-0 py-1 d-none d-sm-block shadow-none" data-daterangepicker="true" data-daterangepicker-start="[name='start_at']" data-daterangepicker-end="[name='end_at']">
                                            <span class="material-symbols-rounded text-xs">calendar_month</span>
                                        </button>
                                        <input class="form-control" type="date" name="start_at" value="{{ request('start_at') }}">
                                        <input class="form-control" type="date" name="end_at" value="{{ request('end_at') }}">
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex align-items-end gap-1">
                                    <button type="submit" class="btn btn-dark btn-sm flex-grow-1 mb-0 border-radius-md">
                                        <span class="material-symbols-rounded text-sm">search</span>
                                    </button>
                                    <a class="btn btn-light btn-sm mb-0 border-radius-md" href="{{ route('portal::outwork.submission.index') }}">
                                        <span class="material-symbols-rounded text-sm">restart_alt</span>
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Tabel --}}
                    <div class="table-responsive tg-steps-outwork-table">
                        <table class="table align-items-center mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-4">Kegiatan & Kategori</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Waktu Pelaksanaan</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Lampiran</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-secondary opacity-7 pe-4"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($outworks as $outwork)
                                    <tr @class(['opacity-6' => $outwork->trashed()])>
                                        <td>
                                            <div class="d-flex px-3 py-2">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm font-weight-bold text-dark">{{ $outwork->name }}</h6>
                                                    <p class="text-xs text-secondary mb-0 text-truncate" style="max-width: 200px;">
                                                        {{ $outwork->category->name }}
                                                    </p>
                                                    <small class="text-xxs text-primary font-weight-bold">Diajukan: {{ $outwork->created_at->format('d/m/Y') }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="py-2">
                                                @foreach ($outwork->dates->take(2) as $date)
                                                    <div class="badge-date text-xs d-block mb-1" style="max-width: fit-content;">
                                                        <span class="material-symbols-rounded text-xs me-1">schedule</span>
                                                        {{ date('d M Y', strtotime($date['d'])) }}
                                                        @isset($date['t_s']) <span class="text-xxs opacity-7">({{ $date['t_s'] }} - {{ $date['t_e'] ?? 'Selesai' }})</span> @endisset
                                                    </div>
                                                @endforeach
                                                @php($remain = $outwork->dates->count() - 2)
                                                @if ($remain > 0)
                                                    <span class="text-xxs text-secondary ms-1">+{{ $remain }} hari lainnya</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            @if (isset($outwork->attachment) && Storage::exists($outwork->attachment))
                                                <a href="{{ Storage::url($outwork->attachment) }}" target="_blank" class="btn btn-link text-info p-0 mb-0">
                                                    <span class="material-symbols-rounded">attachment</span>
                                                </a>
                                            @else
                                                <span class="text-secondary text-xs">-</span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            @include('portal::outwork.components.status', ['outwork' => $outwork])
                                        </td>
                                        <td class="align-middle text-end pe-4">
                                            <div class="dropstart">
                                                <button class="btn btn-link text-secondary mb-0 p-0" data-bs-toggle="dropdown">
                                                    <span class="material-symbols-rounded">more_vert</span>
                                                </button>
                                                <ul class="dropdown-menu shadow border-0 py-2">
                                                    <li><a class="dropdown-item d-flex align-items-center" href="{{ route('portal::outwork.submission.show', ['outwork' => $outwork->id, 'next' => request('next')]) }}">
                                                        <span class="material-symbols-rounded text-sm me-2 text-primary">visibility</span> Detail</a>
                                                    </li>
                                                    @if($outwork->hasApprovables() && !$outwork->trashed())
                                                        <li><a class="dropdown-item d-flex align-items-center" href="javascript:;" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $outwork->id }}">
                                                            <span class="material-symbols-rounded text-sm me-2 text-warning">track_changes</span> Lacak Status</a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- Approval Timeline --}}
                                    @if ($outwork->hasApprovables() && !$outwork->trashed())
                                        <tr class="collapse @if ($outwork->hasAnyApprovableResultIn('PENDING')) show @endif" id="collapse-{{ $outwork->id }}">
                                            <td colspan="5" class="bg-light-soft py-4 px-5">
                                                <div class="timeline timeline-one-side" style="border-left: 2px dashed #dee2e6; margin-left: 14px;">
                                                    @foreach ($outwork->approvables as $approvable)
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
                                                                <p class="text-secondary text-xxs mt-1 mb-0">{{ $approvable->userable ? $approvable->userable->getApproverLabel() : 'Menunggu Sistem' }}</p>
                                                                @if($approvable->reason) <p class="text-xs italic mb-0 text-muted mt-1">"{{ $approvable->reason }}"</p> @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr><td colspan="5" class="text-center py-5">@include('components.notfound')</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer py-3 border-top bg-white">
                        {{ $outworks->appends(request()->all())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/vendor/moment.min.js') }}"></script>
    <script src="{{ asset('js/vendor/daterangepicker.js') }}"></script>
@endpush
