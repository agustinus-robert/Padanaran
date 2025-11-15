@extends('portal::layouts.default')

@section('title', 'Cuti | ')

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
   <header id="page-topbar">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box">
                    <a href="index.html" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="{{ asset('skote/images/logo.svg') }}" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ asset('skote/images/logo-dark.png') }}" alt="" height="17">
                        </span>
                    </a>

                    <a href="index.html" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="{{ asset('skote/images/logo-light.svg') }}" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ asset('skote/images/logo-light.png') }}" alt="" height="39">
                        </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm font-size-16 d-lg-none header-item waves-effect waves-light px-3" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                    <i class="fa fa-fw fa-bars"></i>
                </button>

            </div>

            <div class="d-flex">
                @php($user=auth()->user())
                @include('portal::layouts.components.notifications')
                
                @include('layouts.shortcut_menu')

                @include('layouts.nav_name')
                
            </div>
    </header>

    <div class="topnav">
        <div class="container-fluid">
            <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

                <div class="navbar-collapse collapse" id="topnav-menu-content">
                    <ul class="navbar-nav">

                        <li class="nav-item">
                            <a class="nav-link arrow-none" href="{{ route('portal::dashboard-msdm.index') }}" id="topnav-dashboard" role="button">
                                <i class="bx bx-home-circle me-2"></i><span key="t-dashboards">Dashboards</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>

    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <div class="d-flex align-items-center mb-4">
                    <a class="text-decoration-none" href="{{ request('next', route('portal::dashboard-msdm.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                    <div class="ms-4">
                        <h2 class="mb-1">Cuti</h2>
                        <div class="text-muted">Ambil cutimu atau liburmu buat ke pantai atau muncak!</div>
                    </div>
                </div>

                <div class="row">
                @if (Session::has('success'))
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                        <div class="alert alert-success">
                            {{ Session::get('success') }}
                        </div>
                    </div>
                @endif 

                @if (Session::has('danger'))
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                        <div class="alert-danger alert">
                            {{ Session::get('danger') }}
                        </div>
                    </div>
                @endif
                </div>

                <div class="row">
                    <div class="col-xl-4">
                        <div class="card tg-steps-vacation-submission border-0">
                            <div class="card-body py-4 text-center">
                                <div class="my-4">
                                    @if (count($quotas))
                                        <a class="btn btn-soft-danger rounded-circle d-flex justify-content-center align-items-center mx-auto" href="{{ route('portal::vacation.submission.create', ['next' => url()->full()]) }}" style="width: 100px; height: 100px;"><i class="mdi mdi-exit-to-app mdi-48px"></i></a>
                                    @else
                                        <button class="btn btn-soft-secondary disabled rounded-circle d-flex justify-content-center align-items-center mx-auto" style="width: 100px; height: 100px;"><i class="mdi mdi-exit-to-app mdi-48px"></i></button>
                                    @endif
                                </div>
                                <h4 class="mb-1">Pengajuan baru</h4>
                                <p class="text-muted mb-0">Silakan tekan tombol di atas untuk mengajukan cuti/libur hari raya baru</p>
                            </div>
                        </div>
                        <div class="card tg-steps-vacation-quota border-0">
                            <div class="card-body">
                                <i class="mdi mdi-calendar-minus"></i> Sisa kuota cuti dan libur hari raya kamu
                            </div>
                            @forelse($quotas as $quota)
                                <div class="card-body border-top py-4">
                                    <div class="row align-items-center">
                                        <div class="col-8">
                                            <div class="small text-uppercase">{{ $quota->category->name }}</div>
                                            <div class="small text-muted">Berlaku s.d. {{ $quota->end_at->formatLocalized('%d %B %Y') }}</div>
                                        </div>
                                        <div class="col-4">
                                            <div class="h1 mb-0 text-end">{{ $quota->remain }}</div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="card-body border-top text-center">
                                    <img src="{{ asset('img/manypixels/Sad_face_Flatline.svg') }}" style="max-width: 200px;" alt="">
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1">Yang sabar ya ...</h5>
                                        <p class="text-muted">Kayaknya belum ada kuota cuti tahun ini nih, semangat kerjanya ya!</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                        
                        @if (
                            isset($employee->position->position_id) &&
                            in_array(
                                $employee->position->position_id,
                                [
                                    \Modules\Core\Enums\PositionTypeEnum::KEPALASEKOLAH->value,
                                    \Modules\Core\Enums\PositionTypeEnum::HUMAS->value
                                ],
                                true
                            )
                        ) 
                            @if (in_array($employee->position?->position->level->value ?: 0, array_column(config('modules.core.features.services.vacations.approvable_steps', []), 'value')))
                                <div class="list-group mb-4">
                                    <a class="list-group-item list-group-item-action p-4" href="{{ route('portal::vacation.manage.index', ['next' => url()->current()]) }}" style="border-style: dashed;">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-inline-block bg-soft-secondary text-danger me-2 rounded text-center" style="height: 36px; width: 36px;">
                                                <i class="mdi mdi-calendar-check-outline mdi-24px"></i>
                                            </div>
                                            <div class="flex-grow-1">Kelola pengajuan cuti</div>
                                            <i class="mdi mdi-chevron-right-circle-outline"></i>
                                        </div>
                                    </a>
                                    @if (in_array($employee->position?->position->level->value ?: 0, config('modules.core.features.services.vacations.view_quotas', [])))
                                        <a class="list-group-item list-group-item-action p-4" href="{{ route('portal::vacation.quotas.index', ['next' => url()->current()]) }}" style="border-style: dashed;">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="d-inline-block bg-soft-secondary text-danger me-2 rounded text-center" style="height: 36px; width: 36px;">
                                                    <i class="mdi mdi-calendar-minus mdi-24px"></i>
                                                </div>
                                                <div class="flex-grow-1">Lihat sisa kuota cuti/libur hari raya departemen</div>
                                                <i class="mdi mdi-chevron-right-circle-outline"></i>
                                            </div>
                                        </a>
                                    @endif
                                </div>
                            @endif
                        @endif
                    </div>
                    <div class="col-xl-8">
                        <div class="card border-0">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="mdi mdi-calendar-multiselect"></i> Riwayat cuti dan libur hari raya
                                </div>
                                <input type="checkbox" class="btn-check" id="collapse-btn" autocomplete="off" @if (request('search')) checked @endif>
                                <label class="btn btn-outline-secondary text-dark btn-sm rounded px-2 py-1" data-bs-toggle="collapse" data-bs-target="#collapse-filter" for="collapse-btn"><i class="mdi mdi-filter-outline"></i> <span class="d-none d-sm-inline">Filter</span></label>
                            </div>
                            <div class="card-body border-top border-bottom tg-steps-vacation-filter">
                                <form class="form-block row gy-2 gx-2" action="{{ route('portal::vacation.submission.index') }}" method="get">
                                    <div class="col-12 flex-grow-1 my-0">
                                        <div class="@if (request('search')) show @endif collapse" id="collapse-filter">
                                            <input class="form-control" type="search" name="search" placeholder="Cari kategori/deskripsi di sini ..." value="{{ request('search') }}">
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 col-auto">
                                        <div class="input-group">
                                            <div class="input-group-text"><span class="d-inline d-sm-none"><i class="mdi mdi-sort-clock-descending-outline"></i></span><span class="d-none d-sm-inline">Periode</span></div>
                                            <button type="button" class="btn btn-light dropdown-toggle d-none d-sm-block" data-daterangepicker="true" data-daterangepicker-start="[name='start_at']" data-daterangepicker-end="[name='end_at']">Rentang waktu</button>
                                            <input class="form-control" type="date" name="start_at" value="{{ request('start_at') }}">
                                            <input class="form-control" type="date" name="end_at" value="{{ request('end_at') }}">
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <a class="btn btn-light" href="{{ route('portal::vacation.submission.index') }}"><i class="mdi mdi-refresh"></i> <span class="d-sm-none">Reset</span></a>
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-dark"><i class="mdi mdi-magnify"></i> Cari</button>
                                    </div>
                                </form>
                            </div>
                            <div class="table-responsive table-responsive-xl tg-steps-vacation-table">
                                <table class="mb-0 table align-middle">
                                    <thead>
                                        <tr>
                                            <th>Kategori</th>
                                            <th nowrap>Tgl pengajuan</th>
                                            <th nowrap>Tgl cuti/libur hari raya</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($vacations as $vacation)
                                            <tr @if ($vacation->trashed()) class="text-muted" @endif>
                                                <td style="min-width: 200px;" class="py-3">
                                                    <div>{{ $vacation->quota->category->name }}</div>
                                                    <small class="text-muted">{{ $vacation->description }}</small>
                                                </td>
                                                <td class="small">{{ $vacation->created_at->formatLocalized('%d %B %Y') }}</td>
                                                <td style="min-width: 200px;">
                                                    @isset(collect($vacation->dates)->first()['cashable'])
                                                        <span class="badge bg-dark fw-normal user-select-none text-white">{{ collect($vacation->dates)->count() }} dikompensasikan</span>
                                                    @else
                                                        @foreach (collect($vacation->dates)->take(3) as $date)
                                                            <span class="badge bg-soft-secondary text-dark fw-normal user-select-none {{ isset($date['c']) ? 'text-decoration-line-through' : '' }}" @isset($date['f']) data-bs-toggle="tooltip" title="Sebagai freelancer" @endisset>
                                                                @isset($date['f'])
                                                                    <i class="mdi mdi-account-network-outline text-danger"></i>
                                                                @endisset {{ strftime('%d %B %Y', strtotime($date['d'])) }}
                                                            </span>
                                                        @endforeach
                                                        @php($remain = collect($vacation->dates)->count() - 3)
                                                        @if ($remain > 0)
                                                            <span class="badge text-dark fw-normal user-select-none">+{{ $remain }} lainnya</span>
                                                        @endif
                                                    @endisset
                                                </td>
                                                <td nowrap> @include('portal::vacation.components.status', ['vacation' => $vacation]) </td>
                                                <td nowrap class="py-1 text-end">
                                                    @unless ($vacation->trashed())
                                                        @if ($vacation->hasApprovables())
                                                            <span data-bs-toggle="collapse" data-bs-target="#collapse-{{ $vacation->id }}">
                                                                <button class="btn btn-soft-primary btn-sm rounded px-2 py-1" data-bs-toggle="tooltip" title="Status pengajuan"><i class="mdi mdi-progress-clock"></i></button>
                                                            </span>
                                                        @endif
                                                        @if ($vacation->can('revised'))
                                                            <a class="btn btn-soft-warning btn-sm rounded px-2 py-1" data-bs-toggle="tooltip" title="Ubah pengajuan" href="{{ route('portal::vacation.submission.edit', ['vacation' => $vacation->id, 'next' => request('next')]) }}"><i class="mdi mdi-pencil-outline"></i></a>
                                                        @endif
                                                        <div class="dropstart d-inline">
                                                            <button class="btn btn-soft-secondary text-dark rounded px-2 py-1" type="button" data-bs-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></button>
                                                            <ul class="dropdown-menu border-0 shadow">
                                                                <li><a class="dropdown-item" style="cursor: pointer;" href="{{ route('portal::vacation.submission.show', ['vacation' => $vacation->id, 'next' => request('next')]) }}"><i class="mdi mdi-eye-outline me-1"></i> Lihat detail</a></li>
                                                                <li><a class="dropdown-item" style="cursor: pointer;" href="{{ route('portal::vacation.print', ['vacation' => $vacation->id]) }}" target="_blank"><i class="mdi mdi-printer-outline me-1"></i> Cetak dokumen (.pdf)</a></li>
                                                                @if ($vacation->can('deleted'))
                                                                    <li class="dropdown-divider"></li>
                                                                    <li>
                                                                        <form class="dropdown-item form-block form-confirm" action="{{ route('portal::vacation.submission.destroy', ['vacation' => $vacation->id]) }}" method="post"> @csrf @method('delete')
                                                                            <button class="btn btn-link text-danger d-flex align-items-center p-0 text-start">
                                                                                <i class="mdi mdi-trash-can-outline me-2"></i>
                                                                                <div>Batalkan pengajuan <br> <small class="text-muted">Hapus data pengajuan {{ $vacation->hasApprovables() ? 'sebelum disetujui oleh atasan' : '' }}</small></div>
                                                                            </button>
                                                                        </form>
                                                                    </li>
                                                                @endif
                                                                @if ($vacation->can('canceled'))
                                                                    <li class="dropdown-divider"></li>
                                                                    <li>
                                                                        <a class="dropdown-item text-danger d-flex align-items-center" style="cursor: pointer;" href="{{ route('portal::vacation.cancelation.show', ['vacation' => $vacation->id, 'next' => url()->full()]) }}">
                                                                            <i class="mdi mdi-progress-upload me-2"></i>
                                                                            <div>Ajukan pembatalan <br> <small class="text-muted">Ajukan jika Anda membatalkan pengajuan yang telah disetujui</small></div>
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    @endunless
                                                </td>
                                            </tr>
                                            @if ($vacation->hasApprovables() && !$vacation->trashed())
                                                <tr>
                                                    <td class="p-0" colspan="5">
                                                        <div class="@if ($vacation->hasAnyApprovableResultIn('PENDING')) show @endif collapse" id="collapse-{{ $vacation->id }}">
                                                            <table class="table-borderless table-hover table-sm mb-0 table align-middle">
                                                                <thead>
                                                                    <tr class="text-muted small bg-light">
                                                                        <th class="border-bottom fw-normal">Jenis</th>
                                                                        <th class="border-bottom fw-normal" colspan="2">Persetujuan</th>
                                                                        <th class="border-bottom fw-normal">Penanggungjawab</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($vacation->approvables as $approvable)
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
                                {{ $vacations->appends(request()->all())->links() }}
                            </div>
                        </div>
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
