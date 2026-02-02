@extends('layouts.dashboarding')

@section('title', 'Izin | ')

@section('navtitle', 'Perizinan')

@include('components.tourguide', [
    'steps' => array_filter([
        ['selector' => '.tg-steps-leave-submission', 'title' => 'Pengajuan izin', 'content' => 'Tekan tombol ini untuk melakukan pengajuan izin.'],
        ['selector' => '.tg-steps-leave-count', 'title' => 'Statistik izin', 'content' => 'Kolom ini menampilkan statistik izin yang telah kamu gunakan di tahun ini.'],
        ['selector' => '.tg-steps-leave-filter', 'title' => 'Filter riwayat izin', 'content' => 'Gunakan filter ini untuk melihat riwayat izin pada bulan-bulan sebelumnya.'],
        ['selector' => '.tg-steps-leave-table', 'title' => 'Tabel riwayat izin', 'content' => 'Menampilkan riwayat izin berdasarkan filter yang diterapkan.'],
    ]),
])

@section('body-content')
    @include('layouts.component.material-nav')

    <style>
        /* CSS Halus untuk Sidebar */
        #sidenav-main {
            transition: z-index 0.3s ease, opacity 0.3s ease;
        }
        .sidenav-low {
            z-index: 1040 !important;
            opacity: 0.6;
        }
        .bg-light-soft {
            background-color: #f8f9fa;
        }

        /* Material Symbols Styling */
        .material-symbols-rounded {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }

        /* Timeline Styling */
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
        {{-- Header Halaman --}}
        <div class="d-flex align-items-center mb-4">
            <a href="{{ request('next', route('portal::dashboard.index')) }}" class="btn btn-link text-dark p-0 me-3">
                <span class="material-symbols-rounded" style="font-size: 32px;">arrow_back_ios_new</span>
            </a>
            <div>
                <h3 class="font-weight-bolder mb-0 text-dark">Manajemen Izin</h3>
                <p class="text-sm mb-0 text-secondary">Pantau dan ajukan izin kerja Anda di sini.</p>
            </div>
        </div>

        <div class="row">
            {{-- Sisi Kiri: Aksi & Statistik --}}
            <div class="col-xl-4">
                {{-- Card Pengajuan Baru --}}
                <div class="card tg-steps-leave-submission border-0 shadow-sm mb-4 overflow-hidden" style="border-radius: 1rem;">
                    <div class="card-body py-5 text-center position-relative">
                        <div class="mb-3">
                            <a class="btn btn-icon-only btn-rounded btn-outline-primary btn-lg d-inline-flex align-items-center justify-content-center bg-white shadow-sm"
                               href="{{ route('portal::leave.submission.create', ['next' => url()->full()]) }}"
                               style="width: 70px; height: 70px; border-width: 2px; position: relative; z-index: 2;">
                                <span class="material-symbols-rounded" style="font-size: 36px;">add</span>
                            </a>
                        </div>
                        <h5 class="font-weight-bolder">Buat Pengajuan Baru</h5>
                        <p class="text-muted text-sm px-4">Ada keperluan mendadak? Ajukan izinmu dengan mudah.</p>
                        <span class="material-symbols-rounded position-absolute text-primary" style="right: -15px; bottom: -15px; font-size: 120px; opacity: 0.05;">edit_document</span>
                    </div>
                </div>

                {{-- Card Statistik --}}
                <div class="card tg-steps-leave-count border-0 shadow-sm mb-4" style="border-radius: 1rem;">
                    <div class="card-body p-3">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <p class="text-xs mb-0 text-uppercase font-weight-bold text-secondary ps-1">Total Izin {{ date('Y') }}</p>
                                <h4 class="font-weight-bolder mb-0 text-dark ps-1">{{ $leaves_this_year_count }} <span class="text-sm font-weight-normal text-secondary">Kali</span></h4>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-primary shadow-primary text-center border-radius-md d-flex align-items-center justify-content-center ms-auto">
                                    <span class="material-symbols-rounded text-white">calendar_month</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Panel Approval (Khusus Jabatan Tertentu) --}}
                @php use Modules\Core\Enums\PositionTypeEnum; @endphp
                @if (isset($employee->position->position_id) && in_array($employee->position->position_id, [PositionTypeEnum::KEPALASEKOLAH->value, PositionTypeEnum::HUMAS->value], true))
                    <div class="card bg-gradient-dark border-0 shadow-sm mb-4" style="border-radius: 1rem;">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="icon icon-shape bg-white shadow text-center border-radius-md me-3 d-flex align-items-center justify-content-center">
                                    <span class="material-symbols-rounded text-dark">verified_user</span>
                                </div>
                                <div>
                                    <h6 class="text-white mb-0 text-sm">Pusat Persetujuan</h6>
                                    <a href="{{ route('portal::leave.manage.index', ['next' => url()->current()]) }}" class="text-white text-xs opacity-8 text-decoration-none">
                                        Periksa pengajuan staf &rarr;
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sisi Kanan: Tabel Riwayat --}}
            <div class="col-xl-8">
                <div class="card border-0 shadow-sm" style="border-radius: 1rem;">
                    {{-- HEADER CARD --}}
                    <div class="card-header pb-2 pt-3 bg-white border-bottom" style="border-radius: 1rem 1rem 0 0;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center ps-2">
                                <div class="icon icon-sm shadow-sm border-radius-md bg-gradient-primary text-center me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                    <span class="material-symbols-rounded text-white" style="font-size: 18px;">history</span>
                                </div>
                                <h6 class="font-weight-bolder mb-0 text-dark">Riwayat Izin</h6>
                            </div>
                            <button class="btn btn-sm btn-outline-primary mb-0 py-1 px-3 d-flex align-items-center border-radius-md" data-bs-toggle="collapse" data-bs-target="#collapse-filter">
                                <span class="material-symbols-rounded text-xs me-1">tune</span> Filter
                            </button>
                        </div>
                    </div>

                    {{-- FILTER --}}
                    <div class="card-body border-bottom pt-0 bg-light-soft tg-steps-leave-filter">
                        <div class="collapse @if (request('search') || request('start_at')) show @endif" id="collapse-filter">
                            <form action="{{ route('portal::leave.submission.index') }}" method="get" class="row g-2 mt-2 pb-3 px-2">
                                <div class="col-md-5">
                                    <label class="text-xxs font-weight-bold mb-1 text-secondary text-uppercase">Kata Kunci</label>
                                    <input class="form-control form-control-sm border-radius-md" type="search" name="search" placeholder="Cari..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-5">
                                    <label class="text-xxs font-weight-bold mb-1 text-secondary text-uppercase">Periode</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control border-radius-md" type="date" name="start_at" value="{{ request('start_at') }}">
                                        <input class="form-control border-radius-md" type="date" name="end_at" value="{{ request('end_at') }}">
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex align-items-end gap-1">
                                    <button type="submit" class="btn btn-primary btn-sm flex-grow-1 mb-0 border-radius-md">
                                        <span class="material-symbols-rounded text-sm">search</span>
                                    </button>
                                    <a class="btn btn-light btn-sm mb-0 border-radius-md" href="{{ route('portal::leave.submission.index') }}">
                                        <span class="material-symbols-rounded text-sm">restart_alt</span>
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- TABEL --}}
                    <div class="table-responsive tg-steps-leave-table">
                        <table class="table align-items-center mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-4">Informasi Izin</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tgl Pengajuan</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-secondary opacity-7 text-end pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($leaves as $leave)
                                    <tr @class(['opacity-6' => $leave->trashed()])>
                                        <td>
                                            <div class="d-flex px-3 py-2">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm font-weight-bold">{{ $leave->category->name }}</h6>
                                                    <p class="text-xs text-secondary mb-0 text-truncate" style="max-width: 250px;">{{ $leave->description }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0 text-dark">{{ $leave->created_at->format('d M Y') }}</p>
                                            <p class="text-xxs text-secondary mb-0">{{ count($leave->dates) }} Hari Izin</p>
                                        </td>
                                        <td class="align-middle text-center">
                                            @include('portal::leave.components.status', ['leave' => $leave])
                                        </td>
                                        <td class="align-middle text-end pe-4">
                                            <div class="dropstart">
                                                <button class="btn btn-link text-secondary mb-0 p-0" data-bs-toggle="dropdown">
                                                    <span class="material-symbols-rounded">more_vert</span>
                                                </button>
                                                <ul class="dropdown-menu shadow border-0 py-2">
                                                    <li><a class="dropdown-item d-flex align-items-center" href="{{ route('portal::leave.submission.show', ['leave' => $leave->id]) }}">
                                                        <span class="material-symbols-rounded text-sm me-2 text-primary">visibility</span> Detail</a>
                                                    </li>
                                                    @if($leave->hasApprovables())
                                                        <li><a class="dropdown-item d-flex align-items-center" href="javascript:;" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $leave->id }}">
                                                            <span class="material-symbols-rounded text-sm me-2 text-info">step_order</span> Lacak</a>
                                                        </li>
                                                    @endif
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item d-flex align-items-center text-danger" href="#">
                                                        <span class="material-symbols-rounded text-sm me-2">delete</span> Batalkan</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- Approval Tracking --}}
                                    @if ($leave->hasApprovables())
                                        <tr class="collapse @if ($leave->hasAnyApprovableResultIn('PENDING')) show @endif" id="collapse-{{ $leave->id }}">
                                            <td colspan="4" class="bg-light-soft py-4 px-5">
                                                <div class="timeline timeline-one-side" style="border-left: 2px dashed #dee2e6; margin-left: 14px;">
                                                    @foreach ($leave->approvables as $approvable)
                                                        <div class="timeline-block mb-3 position-relative" style="padding-left: 30px;">
                                                            <span class="timeline-step position-absolute" style="left: -15px; top: 0;">
                                                                <span class="material-symbols-rounded text-{{ $approvable->result->color() }}" style="font-size: 18px;">
                                                                    {{ $approvable->result->icon() == 'mdi mdi-check' ? 'check_circle' : 'hourglass_top' }}
                                                                </span>
                                                            </span>
                                                            <div class="timeline-content">
                                                                <h6 class="text-dark text-xs font-weight-bold mb-0">
                                                                    {{ ucfirst($approvable->type) }} Level {{ $approvable->level }}
                                                                </h6>
                                                                <p class="text-secondary text-xxs mt-1 mb-0">{{ $approvable->userable->getApproverLabel() }}</p>
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
                        {{ $leaves->appends(request()->all())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).on('show.bs.modal', '.modal', function () {
            $('#sidenav-main').addClass('sidenav-low');
        });
        $(document).on('hidden.bs.modal', '.modal', function () {
            $('#sidenav-main').removeClass('sidenav-low');
        });
    </script>
@endpush
