<ul class="metismenu list-unstyled" id="side-menu">
    <li class="menu-title" key="t-menu">Utama</li>
    <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('hrms::dashboard') }}"> <i class="mdi mdi-apps"></i> Dasbor </a>
    </li>
    <li class="menu-title" key="t-menu">Karyawan</li>
    <li class="nav-main-item">
        <a class="has-arrow waves-effect" href="javascript:;"> <i class="mdi mdi-account-box-multiple-outline"></i> Data Karyawan</a>
        <ul class="sub-menu mm-collapse">
            <li><a class="nav-main-link" href="{{ route('hrms::employment.employees.create', ['next' => route('hrms::employment.employees.index')]) }}">Tambah guru</a></li>
            <li><a class="nav-link" href="{{ route('hrms::employment.employees.index') }}">Kelola karyawan</a></li>
        </ul>
    </li>
    <li class="nav-main-item">
        <a class="has-arrow waves-effect" href="javascript:;"> <i class="mdi mdi-file-account-outline"></i> Perjanjian kerja</a>
        <ul class="sub-menu mm-collapse">
            <li><a class="nav-main-link" href="{{ route('hrms::employment.contracts.create', ['next' => route('hrms::employment.contracts.index')]) }}">Buat baru</a></li>
            <li><a class="nav-main-link" href="{{ route('hrms::employment.contracts.index') }}">Data perjanjian kerja</a></li>
        </ul>
    </li>
    <li class="nav-main-item">
        <a class="has-arrow waves-effect" href="javascript:;"> <i class="mdi mdi-calendar-alert"></i> Jam Mengajar</a>
        <ul class="sub-menu mm-collapse">
            <li><a class="nav-main-link" href="{{ route('hrms::service.teacher.schedule.index') }}">Kelola</a></li>
        </ul>
    </li>

    <li class="nav-main-item">
        <a class="has-arrow waves-effect" href="javascript:;"> <i class="mdi mdi-calendar-alert"></i> Petugas Piket</a>
        <ul class="sub-menu mm-collapse">
            <li><a class="nav-main-link" href="{{ route('hrms::service.teacher.duty.index') }}">Kelola</a></li>
        </ul>
    </li>

    <li class="menu-title" key="t-menu">Layanan</li>
    <li class="nav-main-item">
        <a class="has-arrow waves-effect" href="javascript:;"> <i class="mdi mdi-calendar-alert"></i> Presensi</a>
        <ul class="sub-menu mm-collapse">
            <li><a class="nav-main-link" href="{{ route('hrms::service.attendance.schedules.index') }}">Jadwal kerja</a></li>
            <li><a class="nav-main-link" href="{{ route('hrms::service.attendance.manage.index') }}">Kelola presensi</a></li>
            <li><a class="nav-main-link" href="{{ route('hrms::service.attendance.scanlogs.index') }}">Daftar scanlog</a></li>
        </ul>
    </li>
    <li class="nav-main-item">
        <a class="has-arrow waves-effect" href="javascript:;"> <i class="mdi mdi-calendar-minus"></i> Cuti</a>
        <ul class="sub-menu mm-collapse">
            <li><a class="nav-main-link" href="{{ route('hrms::service.vacation.quotas.index') }}">Distribusi kuota</a></li>
            <li><a class="nav-main-link" href="{{ route('hrms::service.vacation.manage.index') }}">Kelola cuti</a></li>
        </ul>
    </li>
    <li class="nav-main-item">
        <a class="has-arrow waves-effect" href="javascript:;"> <i class="mdi mdi-calendar-export"></i> Izin</a>
        <ul class="sub-menu mm-collapse">
            <li><a class="nav-main-link" href="{{ route('hrms::service.leave.manage.index') }}">Kelola izin</a></li>
        </ul>
    </li>

    {{-- <li class="nav-main-item">
        <a class="has-arrow waves-effect" href="javascript:;"> <i class="mdi mdi-food-steak"></i> Makan</a>
        <ul class="sub-menu mm-collapse">
            <li><a class="nav-main-link" href="{{ route('hrms::service.meal.scanlogs.index') }}">Presensi makan</a></li>
        </ul>
    </li> --}}
    {{-- <li class="menu-title" key="t-menu">Benefit</li>
    <li class="nav-main-item">
        <a class="has-arrow waves-effect" href="javascript:;"> <i class="mdi mdi-cash-check"></i> Asuransi</a>
        <ul class="sub-menu mm-collapse">
            <li><a class="nav-main-link" href="{{ route('hrms::benefit.insurances.registrations.index') }}">Registrasi</a></li>
        </ul>
    </li> --}}
    <li class="menu-title" key="t-menu">Rekapitulasi</li>
    <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('hrms::summary.attendances.index') }}"> <i class="mdi mdi-calendar-multiple-check"></i> Kehadiran </a>
    </li>
    <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('hrms::summary.teachings.index') }}"> <i class="mdi mdi-school-outline"></i> Pengajaran </a>
    </li>

    <li class="menu-title" key="t-menu">Penggajian</li>
    <li class="nav-main-item">
        <a class="has-arrow waves-effect" href="javascript:;"> <i class="mdi mdi-calendar-alert"></i> Penggajian</a>
        <ul class="sub-menu mm-collapse">
            <li><a class="nav-main-link" href="{{ route('hrms::payroll.approvals.index') }}">Persetujuan</a></li>
        </ul>
    </li>

    <li class="menu-title" key="t-menu">Pelaporan</li>
    <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('hrms::report.employees.index') }}"> <i class="mdi mdi-file-account-outline"></i> Karyawan </a>
    </li>
    <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('hrms::report.attendances.index') }}"> <i class="mdi mdi-file-check-outline"></i> Kehadiran </a>
    </li>
    <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('hrms::report.vacations.index') }}"> <i class="mdi mdi-file-import-outline"></i> Cuti </a>
    </li>
    <li class="nav-main-item">
        <a class="nav-link" href="{{ route('hrms::report.leaves.index') }}"> <i class="mdi mdi-file-clock-outline"></i> Izin </a>
    </li>
    <li class="nav-main-item">
        <a class="nav-link" href="{{ route('hrms::report.salaries.index') }}"> <i class="mdi mdi-account-cash-outline"></i> Penggajian </a>
    </li>
</ul>
