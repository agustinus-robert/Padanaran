<ul class="metismenu list-unstyled" id="side-menu">
    <li class="menu-title" key="t-menu">Utama</li>
    <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('core::dashboard') }}"> <i class="mdi mdi-apps"></i> Dasbor </a>
    </li>
    <li class="menu-title" key="t-menu">Perusahaan</li>
    @can('access', \Modules\Core\Models\CompanyRole::class)
        <li class="nav-item">
            <a class="nav-link" href="{{ route('core::company.roles.index') }}"> <i class="mdi mdi-shield-star-outline"></i> Peran </a>
        </li>
    @endcan
    <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('core::company.departments.index') }}"> <i class="mdi mdi-file-tree-outline"></i> Divisi </a>
    </li>
    <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('core::company.positions.index') }}"> <i class="mdi mdi-tag-outline"></i> Jabatan </a>
    </li>
    <li class="nav-main-item">
        <a class="has-arrow waves-effect" href="javascript:;"> <i class="mdi mdi-account-convert-outline"></i> Layanan karyawan</a>
        <ul class="sub-menu mm-collapse">
            <li><a class="nav-link" href="{{ route('core::company.services.leave-student-categories.index') }}">Kategori izin siswa</a></li>
            <li><a class="nav-link" href="{{ route('core::company.services.leave-categories.index') }}">Kategori izin</a></li>
            <li><a class="nav-link" href="{{ route('core::company.services.vacation-categories.index') }}">Kategori cuti</a></li>
            <li><a class="nav-link" href="{{ route('core::company.services.outwork-categories.index') }}">Kategori insentif</a></li>
            {{-- <li><a class="nav-link" href="{{ route('core::company.services.loan-categories.index') }}">Kategori pinjaman</a></li> --}}
        </ul>
    </li>
    <li class="nav-main-item">
        <a class="has-arrow waves-effect" href="javascript:;"> <i class="mdi mdi-account-reactivate"></i> Asuransi</a>
        <ul class="sub-menu mm-collapse">
            <li><a class="nav-link" href="{{ route('core::company.insurances.manages.index') }}">Kelola asuransi</a></li>
        </ul>
    </li>
    <li class="nav-main-item">
        <a class="has-arrow waves-effect" href="javascript:;"> <i class="mdi mdi-note-multiple-outline"></i> Penggajian</a>
        <ul class="sub-menu mm-collapse">
            <li><a class="nav-link" href="{{ route('core::company.salaries.slips.index') }}">Slip gaji</a></li>
            <li><a class="nav-link" href="{{ route('core::company.salaries.categories.index') }}">Kategori gaji</a></li>
            <li><a class="nav-link" href="{{ route('core::company.salaries.components.index') }}">Komponen gaji</a></li>
            <li><a class="nav-link" href="{{ route('core::company.salaries.templates.index') }}">Template slip gaji</a></li>
            <li><a class="nav-link" href="{{ route('core::company.salaries.configs.index') }}">Pengaturan slip gaji</a></li>
        </ul>
    </li>
    @if (false)
        <li class="nav-main-item">
            <a class="has-arrow waves-effect" href="javascript:;"> <i class="mdi mdi-office-building-outline"></i> Aset</a>
            <ul class="sub-menu mm-collapse">
                <li><a class="nav-link" href="{{ route('core::company.assets.buildings.index') }}">Gedung</a></li>
                <li><a class="nav-link" href="{{ route('core::company.assets.rooms.index') }}">Ruang</a></li>
            </ul>
        </li>
    @endif
    <li class="menu-title" key="t-menu">Sistem</li>
    <li class="nav-main-item">
        <a class="has-arrow waves-effect" href="javascript:;"> <i class="mdi mdi-account-group-outline"></i> Pengguna</a>
        <ul class="submenu collapse">
            <li><a class="nav-link" href="{{ route('core::system.users.index') }}">Kelola pengguna </a></li>
            <li><a class="nav-link" href="{{ route('core::system.user-logs.index') }}">Log </a></li>
        </ul>
    </li>
    <li class="nav-main-item">
        <a class="has-arrow waves-effect" href="javascript:;"> <i class="mdi mdi-cog-outline"></i> Pengaturan</a>
        <ul class="submenu collapse">
            <li><a class="nav-link" href="{{ route('core::company.moments.index') }}">Hari libur </a></li>
            @if(isset(auth()->user()->roles()->first()->id) && auth()->user()->roles()->first()->id == 1 )
                <li><a class="nav-link" href="{{ route('core::admin-extra.choose.extra-education') }}">Jenjang Pendidikan </a></li>
            @endif
            <li><a class="nav-link" href="javascript:void(0)">Lainnya </a></li>
        </ul>
    </li>
    <li class="menu-title" key="t-menu">Akun</li>
    <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('account::user.profile') }}"> <i class="mdi mdi-account-outline"></i> Akun saya </a>
    </li>
    <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('account::user.password', ['next' => url()->full()]) }}"> <i class="mdi mdi-lock-open-outline"></i> Ubah sandi </a>
    </li>
</ul>
