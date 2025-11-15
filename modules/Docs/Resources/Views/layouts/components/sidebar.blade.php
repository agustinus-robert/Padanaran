<div class="sidebar bg-dark open border-end text-white" style="z-index: 9999;">
    <div class="sidebar-header">
        <div class="d-flex align-items-center justify-content-center border-bottom text-center" style="height: 80px;">
            <img height="24" src="{{ asset('img/logo/logo-small.svg') }}" alt="">
            <div class="h5 mb-0 ps-2">{{ setting('app_short_name') }}</div>
        </div>
    </div>
    <div class="sidebar-body">
        <div class="sidebar-body-menu">
            @include('x-core::Sidebar.apps')
            <ul class="nav flex-column">
                <li class="divider">Utama</li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('evaluation::home') }}"> <i class="mdi mdi-apps"></i> Dasbor </a>
                </li>
                <li class="divider">Evaluasi</li>
                <li class="nav-item has-submenu">
                    <a class="nav-link" href="javascript:;"> <i class="mdi mdi-comment-quote-outline mdi-24px d-block"></i>Penilaian kinerja</a>
                    <ul class="submenu collapse">
                        <li><a class="nav-link" href="{{ route('evaluation::manage.index') }}">Kelola</a></li>
                        <li><a class="nav-link" href="{{ route('evaluation::participans.index') }}">Peserta</a></li>
                    </ul>
                </li>
                <li class="divider">Rekapitulasi</li>
                <li class="nav-item has-submenu">
                    <a class="nav-link" href="javascript:;"> <i class="mdi mdi-chart-box-outline mdi-24px d-block"></i>Hasil Penilaian</a>
                    <ul class="submenu collapse">
                        <li><a class="nav-link" href="{{ route('evaluation::summary.results.index') }}">Kelola penilaian</a></li>
                        <li><a class="nav-link" href="{{ route('evaluation::summary.result-lists.index') }}">Hasil penilaian</a></li>
                        <li><a class="nav-link" href="{{ route('evaluation::summary.reports.index') }}">Laporan hasil</a></li>
                    </ul>
                </li>
                <li class="divider">Akun</li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('account::home') }}"> <i class="mdi mdi-account-outline"></i> Akun saya </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('account::user.password', ['next' => url()->full()]) }}"> <i class="mdi mdi-lock-open-outline"></i> Ubah sandi </a>
                </li>
                <li class="nav-item">
                    <button class="btn w-100 nav-link text-danger" onclick="signout()"> <i class="mdi mdi-logout text-danger"></i> Keluar </button>
                </li>
            </ul>
        </div>
    </div>
    <div class="sidebar-footer">
        <div class="rounded-3 d-flex align-items-center flex-row p-3" style="background: rgba(200, 200, 200, .1);">
            <div class="rounded-circle me-3" style="width: 48px; height: 48px; background: url('{{ Auth::user()->profile_avatar_path }}') center center no-repeat; background-size: cover;"></div>
            <div class="flex-grow-1">
                <div class="fw-bold mb-0">{{ Str::limit(Auth::user()->name, 15) }}</div>
                <div class="small" style="color: rgba(150, 150, 150, .9)">{{ Str::limit(Auth::user()->email_address, 20) }}</div>
            </div>
        </div>
    </div>
</div>
