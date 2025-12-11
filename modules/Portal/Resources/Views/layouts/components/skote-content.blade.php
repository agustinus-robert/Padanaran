<div class="page-content">
    <div class="container-fluid">
        @if (Session::has('msg-sukses'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                <div class="alert alert-success">
                    {{ Session::get('msg-sukses') }}
                </div>
            </div>
        @endif

        @if (Session::has('msg-gagal'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                <div class="alert-danger alert">
                    {{ Session::get('msg-gagal') }}
                </div>
            </div>
        @endif
        <!-- start page title -->
        <div class="row">
                                            {{-- @if (isset($user->employee->position->position_id) && ($user->employee->position->position_id == 2 || $user->employee->position->position_id == 1 || $user->employee->position->position_id == 4)) --}}

            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Dashboard MSDM</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Portal</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-xl-4">
                <div class="card overflow-hidden">
                    <div class="bg-primary-subtle">
                        <div class="row">
                            <div class="col-7">
                                <div class="text-primary p-3">
                                    <h5 class="text-primary">Selamat Datang!</h5>
                                    <p>MSDM Padanaran</p>
                                </div>
                            </div>
                            <div class="col-5 align-self-end">
                                <img src="{{ asset('skote/images/profile-img.png') }}" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="avatar-md profile-user-wid mb-4">
                                    <img src="{{ asset('skote/images/users/avatar-1.jpg') }}" alt="" class="img-thumbnail rounded-circle">
                                </div>
                                <h5 class="font-size-15 text-truncate">{{ $user->name }}</h5>
                                <p class="text-muted text-truncate mb-0">{{ $user->email }}</p>
                            </div>

                            <div class="col-sm-8">
                                <div class="pt-4">

                                    {{-- <div class="row">
                                        <div class="col-6">
                                            <h5 class="font-size-15"></h5>
                                            <p class="text-muted mb-0">Cuti</p>
                                        </div>
                                        <div class="col-6">
                                            <h5 class="font-size-15">

                                            </h5>
                                            <p class="text-muted mb-0">Izin</p>

                                        </div>
                                    </div> --}}
                                    <div class="mt-4">
                                        <a href="{{ route('account::user.profile') }}" class="btn btn-primary waves-effect waves-light btn-sm">Kelola Profil <i class="mdi mdi-arrow-right ms-1"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card" style="min-height:200px;">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Kegiatan</h4>

                        <div>
                            <ul class="verti-timeline list-unstyled">
                                <span class="text-muted">Tidak ada kegiatan</span>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">Izin</p>
                                        <h4 class="mb-0">{{ $leaves_today->count() }}</h4>
                                    </div>

                                    <div class="align-self-center flex-shrink-0">
                                        <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                            <span class="avatar-title">
                                                <i class="bx bx-copy-alt font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">Cuti</p>
                                        <h4 class="mb-0">{{ $vacations_today->count() }}</h4>
                                    </div>

                                    <div class="align-self-center flex-shrink-0">
                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                            <span class="avatar-title rounded-circle bg-primary">
                                                <i class="bx bx-archive-in font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                        <div class="col-md-4">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">Absensi</p>
                                        <a class="btn btn-soft-primary btn-sm my-1 rounded px-3" href="{{ route('portal::attendance.presence.index', ['type' => Modules\Core\Enums\WorkLocationEnum::WFO->name, 'position' => 'employee']) }}">Absen Kehadiran</a>

                                    </div>

                                    <div class="align-self-center flex-shrink-0">
                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                            <span class="avatar-title rounded-circle bg-primary">
                                                <i class="mdi mdi-fingerprint font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>

                </div>
                <!-- end row -->


                    <div class="card">
                        <div class="card-body">
                            @if (isset($user->employee->position->position_id) && ($user->employee->position->position_id == 2 || $user->employee->position->position_id == 1 || $user->employee->position->position_id == 4))
                                <canvas id="pie" data-colors='["--bs-success", "--bs-danger"]' class="chartjs-chart"></canvas>
                            @else
                                <div class="alert alert-info">
                                    Anda telah login, silahkan kelola sesuai dengan role anda
                                </div>
                            @endif
                        </div>
                    </div>


            </div>
        </div>
        <!-- end row -->
        @php
            use Modules\Core\Enums\PositionTypeEnum;
        @endphp

        @if (
            isset($user->employee->position->position_id) &&
            !in_array(
                $user->employee->position->position_id,
                [
                    PositionTypeEnum::MURID->value,
                    PositionTypeEnum::KASIRTOKO->value,
                    PositionTypeEnum::SUPPLIER->value,
                ],
                true
            )
        )
            <div class="row">
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Pengelolaan MSDM</h4>

                            <div class="row mt-4">
                                <div class="col-4">
                                    <div class="social-source mt-3 text-center">
                                        <a href="{{ route('portal::schedule-teacher.manages.index') }}">
                                            <div class="avatar-xs mx-auto mb-3">
                                                <span class="avatar-title rounded-circle bg-primary font-size-16">
                                                    <i class="mdi mdi-calendar-account-outline"></i>
                                                </span>
                                            </div>
                                            <h5 class="font-size-15">Jadwal Guru</h5>
                                        </a>
                                    </div>
                                </div>


                                <div class="col-4">
                                    <div class="social-source mt-3 text-center">
                                        {{-- @if ($user->employee->position->position_id == 4)
                                        <a href="{{ route('portal::leave.submission.index') }}">
                                        <div class="avatar-xs mx-auto mb-3">
                                            <span class="avatar-title rounded-circle bg-info font-size-16">
                                                <i class="mdi mdi-account-group"></i>
                                            </span>
                                        </div>
                                        <h5 class="font-size-15">Izin</h5>
                                    </a>
                                    @else --}}
                                        <a href="{{ route('portal::leave.submission.index') }}">
                                            <div class="avatar-xs mx-auto mb-3">
                                                <span class="avatar-title rounded-circle bg-info font-size-16">
                                                    <i class="mdi mdi-account-group"></i>
                                                </span>
                                            </div>
                                            <h5 class="font-size-15">Izin</h5>
                                        </a>
                                        {{-- @endif --}}
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="social-source mt-3 text-center">
                                        <a href="{{ route('portal::package.manage.index') }}">
                                            <div class="avatar-xs mx-auto mb-3">
                                                <span class="avatar-title rounded-circle bg-pink font-size-16">
                                                    <i class="mdi mdi-gift-outline"></i>
                                                </span>
                                            </div>
                                            <h5 class="font-size-15">Kelola Paket</h5>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-4">
                                    <div class="social-source mt-3 text-center">
                                        <a href="{{ route('portal::overtime.submission.index') }}">
                                            <div class="avatar-xs mx-auto mb-3">
                                                <span class="avatar-title rounded-circle bg-warning font-size-16">
                                                    <i class="mdi mdi-clock-time-five-outline"></i>
                                                </span>
                                            </div>
                                            <h5 class="font-size-15">Lembur</h5>
                                        </a>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="social-source mt-3 text-center">
                                        <a href="{{ route('portal::vacation.submission.index') }}">
                                            <div class="avatar-xs mx-auto mb-3">
                                                <span class="avatar-title rounded-circle bg-info font-size-16">
                                                    <i class="mdi mdi-beach"></i>
                                                </span>
                                            </div>
                                            <h5 class="font-size-15">Cuti</h5>

                                        </a>
                                    </div>
                                </div>



                                <div class="col-4">
                                    <div class="social-source mt-3 text-center">
                                        <a href="{{ route('portal::outwork.submission.index') }}">
                                            <div class="avatar-xs mx-auto mb-3">
                                                <span class="avatar-title rounded-circle bg-secondary font-size-16">
                                                    <i class="mdi mdi-calendar"></i>
                                                </span>
                                            </div>
                                            <h5 class="font-size-15">Kegiatan</h5>
                                        </a>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                @php
                    $isScrollable = $leaves_today->count() > 5;
                    $isScrollableVacation = $vacations_today->count() > 5;
                @endphp

                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body" style="min-height:260px;">
                            <h4 class="card-title mb-5">Perizinan Guru</h4>

                            <div class="{{ $isScrollable ? 'scrollable-container' : '' }}">
                                <ul class="verti-timeline list-unstyled">
                                    @forelse($leaves_today as $leave)
                                        @php($dates = collect($leave->dates)->filter(fn($date) => $date['d'] >= date('Y-m-d')))
                                        <li class="event-list">
                                            <div class="event-timeline-dot">
                                                <i class="bx bx-right-arrow-circle font-size-18"></i>
                                            </div>
                                            <div class="d-flex">
                                                <div class="me-3 flex-shrink-0">
                                                    <h5 class="font-size-14">
                                                        {{ $leave->employee->user->name }}
                                                        <i class="bx bx-right-arrow-alt font-size-16 text-primary ms-2 align-middle"></i>
                                                    </h5>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div>
                                                        {{ $leave->category->name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @empty
                                        <span class="text-muted">Tidak ada yang izin hari ini</span>
                                    @endforelse
                                </ul>
                            </div>
                            {{-- <div class="mt-4 text-center"><a href="javascript: void(0);" class="btn btn-primary waves-effect waves-light btn-sm">View More <i class="mdi mdi-arrow-right ms-1"></i></a></div> --}}
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body" style="min-height:260px;">
                            <h4 class="card-title mb-5">Cuti Guru</h4>

                            <div class="{{ $isScrollableVacation ? 'scrollable-container' : '' }}">
                                <ul class="verti-timeline list-unstyled">
                                    @forelse($vacations_today->filter(fn ($vacation) => empty($vacation->dates[0]['cashable'] ?? null)) as $vacation)                                            @php($dates = collect($vacation->dates)->filter(fn($date) => $date['d'] >= date('Y-m-d')))
                                        <li class="event-list">
                                            <div class="event-timeline-dot">
                                                <i class="bx bx-right-arrow-circle font-size-18"></i>
                                            </div>
                                            <div class="d-flex">
                                                <div class="me-3 flex-shrink-0">
                                                    <h5 class="font-size-14">{{ $vacation->quota->employee->user->name }} <i class="bx bx-right-arrow-alt font-size-16 text-primary ms-2 align-middle"></i></h5>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div>
                                                        {{ $vacation->quota->category->name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @empty
                                        <span class="text-muted">Tidak ada yang cuti hari ini</span>
                                    @endforelse
                                </ul>
                            </div>

                            {{-- <div class="mt-4 text-center"><a href="javascript: void(0);" class="btn btn-primary waves-effect waves-light btn-sm">View More <i class="mdi mdi-arrow-right ms-1"></i></a></div> --}}
                        </div>
                    </div>
                </div>
            </div>
        @endif
        {{-- <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Konsultasi Siswa</h4>
                        <div class="table-responsive">
                            <table class="table-nowrap mb-0 table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="align-middle">No</th>
                                        <th class="align-middle">Nama</th>
                                        <th class="align-middle">Kelas</th>
                                        <th class="align-middle">Jam</th>
                                        <th class="align-middle">Ruang</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <!-- end table-responsive -->
                    </div>
                </div>
            </div>
        </div> --}}
        <!-- end row -->

    </div> <!-- container-fluid -->
</div>

<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <script>
                    document.write(new Date().getFullYear())
                </script> © Skote.
            </div>
            <div class="col-sm-6">
                <div class="text-sm-end d-none d-sm-block">
                    Design & Develop by Themesbrand
                </div>
            </div>
        </div>
    </div>
</footer>
