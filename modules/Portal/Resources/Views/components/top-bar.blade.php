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

            <div class="dropdown d-none d-lg-inline-block ms-1">
                <button type="button" class="btn header-item noti-icon waves-effect" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="bx bx-customize"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <div class="px-lg-2">
                        <div class="row g-0">
                            <div class="col">
                                <a class="dropdown-icon-item" href="{{ route('core::dashboard') }}">
                                    <i class="bx bxs-wrench" style='font-size:30px;'></i>
                                    <span>Setting</span>
                                </a>
                            </div>
                            {{-- <div class="col">
                                <a class="dropdown-icon-item" href="{{ route('portal::dashboard.index') }}">
                                    <i class="bx bx-cart-alt" style='font-size:30px;'></i>
                                    <span>Dashboard POS</span>
                                </a>
                            </div> --}}

                            <div class="col">
                                <a class="dropdown-icon-item" href="{{ route('administration::dashboard') }}">
                                    <i class="bx bxs-buildings" style='font-size:30px;'></i>
                                    <span>Tata Usaha</span>
                                </a>
                            </div>
                        </div>

                        <div class="row no-gutters">
                            <div class="col">
                                <a class="dropdown-icon-item" href="{{ route('teacher::home') }}">
                                    <i class="bx bxs-book-content" style='font-size:30px;'></i>
                                    <span>Guru</span>
                                </a>
                            </div>

                            <div class="col">
                                <a class="dropdown-icon-item" href="{{ route('academic::home') }}">
                                    <i class="bx bxs-chalkboard" style='font-size:30px;'></i>
                                    <span>Akademik</span>
                                </a>
                            </div>
                            <div class="col">
                                <a class="dropdown-icon-item" href="{{ route('counseling::home') }}">
                                    <i class="bx bx-user-voice" style='font-size:30px;'></i>
                                    <span>Konseling</span>
                                </a>
                            </div>
                        </div>

                        <div class="row no-gutters">
                            <div class="col">
                                <a class="dropdown-icon-item" href="{{ route('hrms::dashboard') }}">
                                    <i class="bx bxs-user-pin" style='font-size:30px;'></i>
                                    <span>HRMS</span>
                                </a>
                            </div>
                            <div class="col">
                                <a class="dropdown-icon-item" href="{{ route('portal::dashboard-msdm.index') }}">
                                    <i class="bx bxs-user-pin" style='font-size:30px;'></i>
                                    <span>Dashboard MSDM</span>
                                </a>
                            </div>
                            <div class="col">
                                <a class="dropdown-icon-item" href="{{ route('finance::dashboard') }}">
                                    <i class="bx bx-money" style='font-size:30px;'></i>
                                    <span>Finance</span>
                                </a>
                            </div>
                        </div>

                        <div class="row no-gutters">
                            <div class="col">
                                <a class="dropdown-icon-item" href="{{ route('boarding::dashboard') }}">
                                    <i class="bx bx-money" style='font-size:30px;'></i>
                                    <span>Pondok</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('layouts.nav_name')
        </div>
</header>
