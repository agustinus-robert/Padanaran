<ul class="metismenu list-unstyled" id="side-menu">
    <li class="menu-title" key="t-menu">Utama</li>
    <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('finance::dashboard') }}"> <i class="mdi mdi-apps"></i> Dasbor </a>
    </li>
    <li class="menu-title" key="t-menu">Layanan karyawan</li>
    <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('finance::benefit.insurances.registrations.index') }}"> <i class="mdi mdi-cash-check"></i> Asuransi</a>
    </li>
    <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('finance::service.overtime.manage.index') }}"> <i class="mdi mdi-sort-clock-descending-outline"></i> Lembur </a>
    </li>
    <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('finance::service.outwork.manage.index') }}"> <i class="mdi mdi-comment-quote-outline"></i> Insentif kegiatan</a>
    </li>
    {{-- <li class="nav-main-item">
        <a class="nav-main-link {{ config('modules.finance.features.loans.state') }}" href="{{ route('finance::service.loans.index') }}"> <i class="mdi mdi-account-cash-outline"></i> Pinjaman </a>
    </li> --}}
    {{-- <li class="menu-title" key="t-menu">Potongan</li>
    <li class="nav-item has-submenu">
        <a class="nav-manu-link" href="javascript:;"> <i class="mdi mdi-scissors-cutting"></i> Potongan</a>
        <ul class="sub-menu mm-collapse">
            <li class="nav-menu-item"><a class="nav-link" href="{{ route('finance::service.deduction.manage.index') }}">Daftar potongan</a></li>
        </ul>
    </li> --}}
    <li class="menu-title" key="t-menu">Rekapitulasi</li>
    <li class="nav-menu-item">
        <a class="nav-menu-link" href="{{ route('finance::summary.outworks.index') }}"> <i class="mdi mdi-comment-quote-outline"></i> Insentif kegiatan </a>
    </li>
    <li class="nav-menu-item">
        <a class="nav-menu-link" href="{{ route('finance::summary.overtimes.index') }}"> <i class="mdi mdi-sort-clock-ascending-outline"></i> Lembur </a>
    </li>
    {{-- <li class="nav-menu-item">
        <a class="nav-menu-link disabled" href="{{ route('finance::summary.deductions.index') }}"> <i class="mdi mdi-scissors-cutting"></i> Potongan </a>
    </li> --}}
    <li class="nav-menu-item">
        <a class="nav-menu-link" href="{{ route('finance::summary.teachings.index') }}"> <i class="mdi mdi-clipboard-edit-outline"></i> Pengajaran </a>
    </li>
    <li class="nav-menu-item">
        <a class="nav-menu-link" href="{{ route('finance::summary.feastday.index') }}"> <i class="fas fa-mosque"></i> TJ Hari Raya </a>
    </li>
    <li class="nav-menu-item">
        <a class="nav-menu-link" href="{{ route('finance::summary.postyear.index') }}"> <i class="mdi mdi-forwardburger"></i> Gaji ke-13 </a>
    </li>
    <li class="menu-title" key="t-menu">Penggajian</li>
    <li class="nav-main-item">
        <a class="has-arrow waves-effect" href="javascript:;"> <i class="mdi mdi-contactless-payment"></i> Penggajian</a>
        <ul class="sub-menu mm-collapse">
            <li><a class="nav-menu-link" href="{{ route('finance::payroll.templates.index') }}">Template gaji</a></li>
            <li><a class="nav-menu-link" href="{{ route('finance::payroll.calculations.index') }}">Penghitungan</a></li>
            <li>
                <a class="nav-menu-link" href="{{ route('finance::tax.income-taxs.index') }}"> PPh 21</a>
            </li>
            <li><a class="nav-menu-link" href="{{ route('finance::payroll.validations.index') }}">Penerbitan</a></li>
        </ul>
    </li>
    <li class="menu-title" key="t-menu">Pelaporan</li>
    <li class="nav-menu-item">
        <a class="nav-menu-link" href="{{ route('finance::report.salaries.index') }}"> <i class="mdi mdi mdi-cash-check"></i> Penggajian </a>
    </li>
    <li class="nav-menu-item">
        <a class="nav-menu-link" href="{{ route('finance::report.overtimes.index') }}"> <i class="mdi mdi-sort-clock-ascending-outline"></i> Lembur </a>
    </li>
    <li class="menu-title" key="t-menu">Pajak</li>
    <li class="nav-menu-item">
        <a class="has-arrow waves-effect" href="javascript:;"> <i class="mdi mdi-currency-usd-off"></i> Perpajakan</a>
        <ul class="sub-menu mm-collapse">
            <li>
                <a class="nav-menu-link" href="{{ route('finance::tax.incomes.index') }}"> Bukti potong PPh 21 </a>
            </li>
            <li>
                <a class="nav-menu-link" href="{{ route('finance::tax.employeetaxs.index') }}"> Informasi wajib pajak </a>
            </li>
            <li>
                <a class="nav-menu-link" href="{{ route('finance::tax.ptkps.index') }}"> Referensi PTKP </a>
            </li>
            <li>
                <a class="nav-menu-link disabled" href="{{ route('finance::tax.templates.index') }}"> Template PPh 21 </a>
            </li>
        </ul>
    </li>
</ul>
