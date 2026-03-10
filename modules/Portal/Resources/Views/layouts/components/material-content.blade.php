<div class="container-fluid py-2">

    @if (session('danger'))
        <div class="alert alert-danger text-white">
            {{ session('danger') }}
        </div>
    @endif

    <div class="row">
    <div class="ms-3">
        <h3 class="mb-0 h4 font-weight-bolder">Dashboard</h3>
        <p class="mb-4">
        Informasi dashboard anda
        </p>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
        <div class="card-header p-2 ps-3">
            <div class="d-flex justify-content-between">
            <div>
                <p class="text-sm mb-0 text-capitalize">Izin</p>
                <h4 class="mb-0">1</h4>
            </div>
            <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                <i class="material-symbols-rounded opacity-10">weekend</i>
            </div>
            </div>
        </div>
        <hr class="dark horizontal my-0">
        <div class="card-footer p-2 ps-3">
            <p class="mb-0 text-sm"><span class="text-danger font-weight-bolder">Hari </span>ini</p>
        </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
        <div class="card-header p-2 ps-3">
            <div class="d-flex justify-content-between">
            <div>
                <p class="text-sm mb-0 text-capitalize">Cuti</p>
                <h4 class="mb-0">2</h4>
            </div>
            <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                <i class="material-symbols-rounded opacity-10">person</i>
            </div>
            </div>
        </div>
        <hr class="dark horizontal my-0">
        <div class="card-footer p-2 ps-3">
            <p class="mb-0 text-sm"><span class="text-info font-weight-bolder">Hari </span>ini</p>
        </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
        <div class="card-header p-2 ps-3">
            <div class="d-flex justify-content-between">
            <div>
                <p class="text-sm mb-0 text-capitalize">Mapel</p>
                <h4 class="mb-0">0</h4>
            </div>
            <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                <i class="material-symbols-rounded opacity-10">leaderboard</i>
            </div>
            </div>
        </div>
        <hr class="dark horizontal my-0">
        <div class="card-footer p-2 ps-3">
            <p class="mb-0 text-sm"><span class="text-warning font-weight-bolder">Hari </span>ini</p>
        </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card">
        <div class="card-header p-2 ps-3">
            <div class="d-flex justify-content-between">
            <div>
                <p class="text-sm mb-0 text-capitalize">Absensi</p>
                <h4 class="mb-0">Button</h4>
            </div>
            <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                <i class="material-symbols-rounded opacity-10">weekend</i>
            </div>
            </div>
        </div>
        <hr class="dark horizontal my-0">
        <div class="card-footer p-2 ps-3">
            <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">Sudah </span>absen</p>
        </div>
        </div>
    </div>
    </div>
    <div class="row">
    <div class="col-lg-8 col-md-8 mt-4 mb-4">
        <div class="card">
        <div class="card-body">
            <h6 class="mb-0 ">Cuti & Izin</h6>
            <p class="text-sm ">Grafik Perizinan hari ini</p>
            <div class="pe-2">
            <div class="chart">
                <canvas id="chart-bars" class="chart-canvas" height="170" style="display: block; box-sizing: border-box; height: 170px; width: 311px;" width="311"></canvas>
            </div>
            </div>
            <hr class="dark horizontal">
            <div class="d-flex ">
            <i class="material-symbols-rounded text-sm my-auto me-1">schedule</i>
            <p class="mb-0 text-sm">Terakhir Diupdate pada 00:00 </p>
            </div>
        </div>
        </div>
    </div>

    <div class="col-lg-4 mt-4 mb-3">
        <div class="card">
        <div class="card-body">
            <h6 class="mb-0 ">Kegiatan</h6>
            <p class="text-sm ">Daftar lengkap Kegiatan Hari ini</p>
            <div class="pe-2">
            <div class="chart">
                <canvas id="chart-line-tasks" class="chart-canvas" height="170" style="display: block; box-sizing: border-box; height: 170px; width: 311px;" width="311"></canvas>
            </div>
            </div>
            <hr class="dark horizontal">
            <div class="d-flex ">
            <i class="material-symbols-rounded text-sm my-auto me-1">schedule</i>
            <p class="mb-0 text-sm">Terakhir Diupdate pada 00:00</p>
            </div>
        </div>
        </div>
    </div>
    </div>
    <div class="row mb-4">
    <div class="col-lg-8 col-md-6 mb-md-0 mb-4">
        <div class="card">
            <div class="card-header pb-0">
                <div class="row">
                <div class="col-lg-6 col-7">
                    <h6>Perizinan</h6>
                    <p class="text-sm mb-0">
                    <i class="fa fa-check text-info" aria-hidden="true"></i>
                    <span class="font-weight-bold">Cuti & Izin</span> hari ini
                    </p>
                </div>
                <div class="col-lg-6 col-5 my-auto text-end">
                    <div class="dropdown float-lg-end pe-4">
                    <a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-ellipsis-v text-secondary"></i>
                    </a>
                    <ul class="dropdown-menu px-2 py-3 ms-sm-n4 ms-n5" aria-labelledby="dropdownTable">
                        <li><a class="dropdown-item border-radius-md" href="javascript:;">Action</a></li>
                        <li><a class="dropdown-item border-radius-md" href="javascript:;">Another action</a></li>
                        <li><a class="dropdown-item border-radius-md" href="javascript:;">Something else here</a></li>
                    </ul>
                    </div>
                </div>
                </div>
            </div>

            <div class="card-body px-0 pb-2" style="height:430px;">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table">
                            <thead>
                                <th>Nama</th>
                                <th>Keterangan</th>
                            </thead>

                            <tbody>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                    <div>
                                        <img src="{{asset('material/img/small-logos/logo-xd.svg')}}" class="avatar avatar-sm me-3" alt="xd">
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="mb-0 text-sm">Mulyono sutarman</h6>
                                    </div>
                                    </div>
                                </td>
                                <td>Sakit</td>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <table class="table">
                            <thead>
                                <th>Nama</th>
                                <th>Keterangan</th>
                            </thead>

                            <tbody>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                    <div>
                                        <img src="{{asset('material/img/small-logos/logo-xd.svg')}}" class="avatar avatar-sm me-3" alt="xd">
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="mb-0 text-sm">Yustina Margarita</h6>
                                    </div>
                                    </div>
                                </td>
                                <td>Cuti</td>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="card h-100">
            <div class="card-header pb-0">
                <h6>Pengelolaan MSDM</h6>
                <p class="text-sm">
                <i class="fa fa-arrow-up text-success" aria-hidden="true"></i>
                <span>Daftar menu pengelolaan MSDM</span>
                </p>
            </div>
            <div class="card-body p-3">
    <div class="row g-3">

        <!-- Menu 1 -->
        <div class="col-md-6 col-6">
            <div class="d-flex flex-column align-items-center text-center p-3 border rounded">
                <a href="{{ route('portal::schedule-teacher.manages.index') }}">
                    <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg mb-2">
                        <i class="material-symbols-rounded opacity-10">home</i>
                    </div>
                    <div>Jadwal Guru</div>
                </a>
            </div>
        </div>

        <!-- Menu 2 -->
        <div class="col-md-6 col-6">
            <div class="d-flex flex-column align-items-center text-center p-3 border rounded">
                <a href="{{ route('portal::leave.submission.index') }}">
                    <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg mb-2">
                        <i class="material-symbols-rounded opacity-10">person</i>
                    </div>
                    <div>Izin</div>
                </a>
            </div>
        </div>

        <!-- Menu 3 -->
        <div class="col-md-6 col-6">
            <div class="d-flex flex-column align-items-center text-center p-3 border rounded">
                <a href="{{ route('portal::package.manage.index') }}">
                    <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg mb-2">
                        <i class="material-symbols-rounded opacity-10">settings</i>
                    </div>
                    <div>Kelola Paket</div>
                </a>
            </div>
        </div>

        <!-- Menu 4 -->
        <div class="col-md-6 col-6">
            <div class="d-flex flex-column align-items-center text-center p-3 border rounded">
                <a href="{{ route('portal::overtime.submission.index') }}">
                    <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg mb-2">
                        <i class="material-symbols-rounded opacity-10">inventory_2</i>
                    </div>
                    <div>Lembur</div>
                </a>
            </div>
        </div>

        <!-- Menu 5 -->
        <div class="col-md-6 col-6">
            <div class="d-flex flex-column align-items-center text-center p-3 border rounded">
                <a href="{{ route('portal::vacation.submission.index') }}">
                    <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg mb-2">
                        <i class="material-symbols-rounded opacity-10">trending_up</i>
                    </div>
                    <div>Cuti</div>
                </a>
            </div>
        </div>

        <!-- Menu 6 -->
        <div class="col-md-6 col-6">
            <div class="d-flex flex-column align-items-center text-center p-3 border rounded">
                <a href="{{ route('portal::outwork.submission.index') }}">
                    <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg mb-2">
                        <i class="material-symbols-rounded opacity-10">folder</i>
                    </div>
                    <div>Kegiatan</div>
                </a>
            </div>
        </div>

    </div>
</div>
        </div>
    </div>
    </div>
    <footer class="footer py-4  ">
    <div class="container-fluid">
        <div class="row align-items-center justify-content-lg-between">
        <div class="col-lg-6 mb-lg-0 mb-4">
            <div class="copyright text-center text-sm text-muted text-lg-start">
            © <script>
                document.write(new Date().getFullYear())
            </script>,
            made by
            <a href="javascript:void(0)" class="font-weight-bold" target="_blank">Backend2</a>
            for a better web.
            </div>
        </div>
        <div class="col-lg-6">
            {{-- <p>Copyright By Digi <b>Pemad</b></p> --}}
            {{-- <ul class="nav nav-footer justify-content-center justify-content-lg-end">
            <li class="nav-item">
                <a href="https://www.creative-tim.com" class="nav-link text-muted" target="_blank">Creative Tim</a>
            </li>
            <li class="nav-item">
                <a href="https://www.creative-tim.com/presentation" class="nav-link text-muted" target="_blank">About Us</a>
            </li>
            <li class="nav-item">
                <a href="https://www.creative-tim.com/blog" class="nav-link text-muted" target="_blank">Blog</a>
            </li>
            <li class="nav-item">
                <a href="https://www.creative-tim.com/license" class="nav-link pe-0 text-muted" target="_blank">License</a>
            </li>
            </ul> --}}
        </div>
        </div>
    </div>
    </footer>
</div>
