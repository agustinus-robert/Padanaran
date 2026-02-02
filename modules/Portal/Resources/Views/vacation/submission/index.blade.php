@extends('portal::layouts.default')

@section('title', 'Cuti | ')

@section('navtitle', 'Cuti')

@include('components.tourguide', [
    'steps' => array_filter([
        [
            'selector' => '.tg-steps-vacation-submission',
            'title' => 'Pengajuan cuti',
            'content' => 'Tekan tombol ini untuk melakukan pengajuan cuti.',
        ],
        [
            'selector' => '.tg-steps-vacation-quota',
            'title' => 'Kuota cuti',
            'content' => 'Kolom ini menampilkan daftar jatah cuti yang Anda miliki di tahun yang sudah ditentukan.',
        ],
        [
            'selector' => '.tg-steps-vacation-filter',
            'title' => 'Filter riwayat cuti',
            'content' => 'Gunakan filter ini untuk melihat riwayat cuti pada bulan-bulan sebelumnya.',
        ],
        [
            'selector' => '.tg-steps-vacation-table',
            'title' => 'Tabel riwayat cuti',
            'content' => 'Menampilkan riwayat cuti berdasarkan filter yang diterapkan.',
        ],
    ]),
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
                <h3 class="font-weight-bolder mb-0 text-dark">Cuti & Libur Hari Raya</h3>
                <p class="text-sm mb-0 text-secondary">Kelola jatah cutimu dan pantau riwayat pengajuan di sini.</p>
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
            {{-- Sisi Kiri: Aksi & Kuota --}}
            <div class="col-xl-4">
                {{-- Card Pengajuan --}}
                <div class="card card-soft tg-steps-vacation-submission mb-4 overflow-hidden">
                    <div class="card-body py-4 text-center position-relative">
                        <div class="my-3">
                            @if (count($quotas))
                                <a class="btn btn-icon-only btn-rounded btn-outline-danger btn-lg d-inline-flex align-items-center justify-content-center bg-white shadow-sm"
                                   href="{{ route('portal::vacation.submission.create', ['next' => url()->full()]) }}"
                                   style="width: 70px; height: 70px; border-width: 2px;">
                                    <span class="material-symbols-rounded text-danger" style="font-size: 36px;">flight_takeoff</span>
                                </a>
                            @else
                                <button class="btn btn-icon-only btn-rounded btn-soft-secondary btn-lg disabled" style="width: 70px; height: 70px;">
                                    <span class="material-symbols-rounded" style="font-size: 36px;">block</span>
                                </button>
                            @endif
                        </div>
                        <h5 class="font-weight-bolder">Ajukan Cuti Baru</h5>
                        <p class="text-muted text-sm px-3">Butuh waktu istirahat? Klik tombol di atas untuk mulai mengajukan.</p>
                        <span class="material-symbols-rounded position-absolute text-danger" style="right: -15px; bottom: -15px; font-size: 100px; opacity: 0.05;">beach_access</span>
                    </div>
                </div>

                {{-- Card Kuota --}}
                <div class="card card-soft tg-steps-vacation-quota mb-4">
                    <div class="card-header bg-transparent pb-0">
                        <h6 class="font-weight-bold mb-0 text-dark d-flex align-items-center">
                            <span class="material-symbols-rounded me-2 text-danger">event_available</span> Sisa Jatah Cuti
                        </h6>
                    </div>
                    <div class="card-body px-0 pb-0">
                        @forelse($quotas as $quota)
                            <div class="p-3 border-bottom mx-3">
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        <p class="text-xs text-uppercase font-weight-bold mb-0 text-secondary">{{ $quota->category->name }}</p>
                                        <p class="text-xxs text-muted mb-0">Hingga {{ $quota->end_at->format('d M Y') }}</p>
                                    </div>
                                    <div class="col-4 text-end">
                                        <h3 class="font-weight-bolder mb-0 text-dark">{{ $quota->remain }}</h3>
                                        <p class="text-xxs text-uppercase mb-0 text-secondary">Hari</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="card-body text-center py-4">
                                <img src="{{ asset('img/manypixels/Sad_face_Flatline.svg') }}" style="max-width: 120px;" alt="">
                                <p class="text-sm text-muted mt-3">Belum ada jatah cuti tersedia.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Admin Actions --}}
                @if (isset($employee->position->position_id) && in_array($employee->position->position_id, [\Modules\Core\Enums\PositionTypeEnum::KEPALASEKOLAH->value, \Modules\Core\Enums\PositionTypeEnum::HUMAS->value], true))
                    <div class="list-group mb-4">
                        @if (in_array($employee->position?->position->level->value ?: 0, array_column(config('modules.core.features.services.vacations.approvable_steps', []), 'value')))
                            <a href="{{ route('portal::vacation.manage.index', ['next' => url()->current()]) }}" class="list-group-item list-group-item-action card-soft border-0 mb-3 py-3 d-flex align-items-center">
                                <div class="icon icon-shape bg-soft-danger text-danger border-radius-md me-3 d-flex align-items-center justify-content-center">
                                    <span class="material-symbols-rounded">verified_user</span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 text-sm font-weight-bold">Kelola Pengajuan</h6>
                                    <p class="text-xs text-secondary mb-0">Verifikasi cuti karyawan</p>
                                </div>
                                <span class="material-symbols-rounded text-secondary">chevron_right</span>
                            </a>
                        @endif

                        @if (in_array($employee->position?->position->level->value ?: 0, config('modules.core.features.services.vacations.view_quotas', [])))
                            <a href="{{ route('portal::vacation.quotas.index', ['next' => url()->current()]) }}" class="list-group-item list-group-item-action card-soft border-0 mb-3 py-3 d-flex align-items-center">
                                <div class="icon icon-shape bg-soft-info text-info border-radius-md me-3 d-flex align-items-center justify-content-center">
                                    <span class="material-symbols-rounded">group</span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 text-sm font-weight-bold">Kuota Departemen</h6>
                                    <p class="text-xs text-secondary mb-0">Pantau sisa cuti tim</p>
                                </div>
                                <span class="material-symbols-rounded text-secondary">chevron_right</span>
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Sisi Kanan: Tabel Riwayat --}}
            <div class="col-xl-8">
                <div class="card card-soft">
                    <div class="card-header pb-2 pt-3 bg-white border-bottom">
                        <div class="d-flex align-items-center justify-content-between px-2">
                            <div class="d-flex align-items-center">
                                <div class="icon icon-sm shadow-sm border-radius-md bg-gradient-danger text-center me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                    <span class="material-symbols-rounded text-white" style="font-size: 18px;">history</span>
                                </div>
                                <h6 class="font-weight-bolder mb-0 text-dark">Riwayat Pengajuan</h6>
                            </div>
                            <button class="btn btn-sm btn-outline-danger mb-0 py-1 px-3 d-flex align-items-center border-radius-md" data-bs-toggle="collapse" data-bs-target="#collapse-filter">
                                <span class="material-symbols-rounded text-xs me-1">tune</span> Filter
                            </button>
                        </div>
                    </div>

                    {{-- Filter --}}
                    <div class="card-body border-bottom pt-0 bg-light-soft tg-steps-vacation-filter">
                        <div class="collapse @if (request('search')) show @endif" id="collapse-filter">
                            <form action="{{ route('portal::vacation.submission.index') }}" method="get" class="row g-2 mt-2 pb-3 px-2">
                                <div class="col-md-5">
                                    <label class="text-xxs font-weight-bold mb-1 text-secondary text-uppercase ps-1">Cari Kategori/Alasan</label>
                                    <input class="form-control form-control-sm border-radius-md" type="search" name="search" placeholder="Cari..." value="{{ request('search') }}">
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
                                    <a class="btn btn-light btn-sm mb-0 border-radius-md" href="{{ route('portal::vacation.submission.index') }}">
                                        <span class="material-symbols-rounded text-sm">restart_alt</span>
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Tabel --}}
                    <div class="table-responsive tg-steps-vacation-table">
                        <table class="table align-items-center mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-4">Kategori & Keterangan</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tgl Libur</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-secondary opacity-7 text-end pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($vacations as $vacation)
                                    <tr @class(['opacity-6' => $vacation->trashed()])>
                                        <td>
                                            <div class="d-flex px-3 py-2">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm font-weight-bold text-dark">{{ $vacation->quota->category->name }}</h6>
                                                    <p class="text-xs text-secondary mb-0 text-truncate" style="max-width: 200px;">{{ $vacation->description }}</p>
                                                    <small class="text-xxs text-primary font-weight-bold">Diajukan: {{ $vacation->created_at->format('d/m/Y') }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="py-2">
                                                @isset(collect($vacation->dates)->first()['cashable'])
                                                    <span class="badge bg-dark text-xxs border-radius-md">{{ collect($vacation->dates)->count() }} Hari Dikompensasikan</span>
                                                @else
                                                    @foreach (collect($vacation->dates)->take(2) as $date)
                                                        <div class="badge-date text-xs">
                                                            <span class="material-symbols-rounded text-xs me-1">calendar_today</span>
                                                            {{ date('d M Y', strtotime($date['d'])) }}
                                                        </div>
                                                    @endforeach
                                                    @php($remain = collect($vacation->dates)->count() - 2)
                                                    @if ($remain > 0)
                                                        <span class="text-xxs text-secondary ms-1">+{{ $remain }} hari lagi</span>
                                                    @endif
                                                @endisset
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            @include('portal::vacation.components.status', ['vacation' => $vacation])
                                        </td>
                                        <td class="align-middle text-end pe-4">
                                            <div class="dropstart">
                                                <button class="btn btn-link text-secondary mb-0 p-0" data-bs-toggle="dropdown">
                                                    <span class="material-symbols-rounded">more_vert</span>
                                                </button>
                                                <ul class="dropdown-menu shadow border-0 py-2">
                                                    <li><a class="dropdown-item d-flex align-items-center" href="{{ route('portal::vacation.submission.show', ['vacation' => $vacation->id, 'next' => request('next')]) }}">
                                                        <span class="material-symbols-rounded text-sm me-2 text-primary">visibility</span> Detail</a>
                                                    </li>
                                                    <li><a class="dropdown-item d-flex align-items-center" href="{{ route('portal::vacation.print', ['vacation' => $vacation->id]) }}" target="_blank">
                                                        <span class="material-symbols-rounded text-sm me-2 text-dark">print</span> Cetak PDF</a>
                                                    </li>
                                                    @if($vacation->hasApprovables() && !$vacation->trashed())
                                                        <li><a class="dropdown-item d-flex align-items-center" href="javascript:;" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $vacation->id }}">
                                                            <span class="material-symbols-rounded text-sm me-2 text-warning">track_changes</span> Lacak Status</a>
                                                        </li>
                                                    @endif
                                                    @if ($vacation->can('revised'))
                                                        <li><a class="dropdown-item d-flex align-items-center" href="{{ route('portal::vacation.submission.edit', ['vacation' => $vacation->id, 'next' => request('next')]) }}">
                                                            <span class="material-symbols-rounded text-sm me-2 text-info">edit_square</span> Ubah</a>
                                                        </li>
                                                    @endif

                                                    @if ($vacation->can('deleted'))
                                                        <li class="dropdown-divider"></li>
                                                        <li>
                                                            <form class="form-confirm" action="{{ route('portal::vacation.submission.destroy', ['vacation' => $vacation->id]) }}" method="post">
                                                                @csrf @method('delete')
                                                                <button type="submit" class="dropdown-item text-danger d-flex align-items-center">
                                                                    <span class="material-symbols-rounded text-sm me-2">delete</span> Batalkan Pengajuan
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif

                                                    @if ($vacation->can('canceled'))
                                                        <li class="dropdown-divider"></li>
                                                        <li><a class="dropdown-item text-danger d-flex align-items-center" href="{{ route('portal::vacation.cancelation.show', ['vacation' => $vacation->id, 'next' => url()->full()]) }}">
                                                            <span class="material-symbols-rounded text-sm me-2">cancel</span> Ajukan Pembatalan</a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- Approval Timeline --}}
                                    @if ($vacation->hasApprovables() && !$vacation->trashed())
                                        <tr class="collapse @if ($vacation->hasAnyApprovableResultIn('PENDING')) show @endif" id="collapse-{{ $vacation->id }}">
                                            <td colspan="4" class="bg-light-soft py-4 px-5">
                                                <div class="timeline timeline-one-side" style="border-left: 2px dashed #dee2e6; margin-left: 14px;">
                                                    @foreach ($vacation->approvables as $approvable)
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
                                                                @if($approvable->reason) <p class="text-xs italic mb-0 text-muted mt-1">"{{ $approvable->reason }}"</p> @endif
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

                    <div class="card-footer py-3 border-top bg-white">
                        {{ $vacations->appends(request()->all())->links() }}
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
