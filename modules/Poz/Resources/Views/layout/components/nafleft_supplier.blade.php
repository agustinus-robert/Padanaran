<ul class="metismenu list-unstyled" id="side-menu">
    <li class="nav-main-item">
        <a class="nav-main-link active" href="{{ route('poz::dashboard', request()->query()) }}">
            <i class="nav-main-link-icon bx bxs-dashboard"></i>
            <span class="nav-main-link-name">Dashboard</span>
        </a>
    </li>

    <li class="menu-title" key="t-menu">Pengelolaan</li>

    {{-- <li class="nav-main-item">
        <a href="{{ route('poz::supplierz.adjustment.index') }}" class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
            <i class="nav-main-link-icon bx bx-basket"></i>
            <span class="nav-main-link-name">Penawaran Produk</span>
        </a>
    </li> --}}

    {{-- <li class="nav-main-item">
        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
            <i class="nav-main-link-icon  bx bx-message-alt"></i>
            <span class="nav-main-link-name">Hubungi Admin</span>
        </a>
    </li> --}}
    
    <li class="nav-main-item">
        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="{{ route('poz::supplierz.quotation.index') }}">
            <i class="nav-main-link-icon mdi mdi-quora"></i>
            <span class="nav-main-link-name">Penawaran</span>
        </a>
    </li>

    <li class="nav-main-item">
        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="{{ route('poz::supplierz.adjustment.index') }}">
            <i class="nav-main-link-icon bx bxs-store"></i>
            <span class="nav-main-link-name">Kelola Stock</span>
        </a>
    </li>

    <li class="menu-title" key="t-menu">Laporan</li>

    <li class="nav-main-item">
        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="{{ route('poz::supplierz.reporting.product_supplier_reporting.index') }}">
            <i class="nav-main-link-icon  bx bxs-report"></i>
            <span class="nav-main-link-name">Laporan Shift</span>
        </a>
    </li>

    <li class="nav-main-item">
        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="{{ route('poz::supplierz.reporting.product_reporting.index') }}">
            <i class="nav-main-link-icon bx bxs-shopping-bag-alt"></i>
            <span class="nav-main-link-name">Laporan Produk Rusak</span>
        </a>
    </li>

    {{-- <li class="nav-main-item">
        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
            <i class="nav-main-link-icon bx bxs-news "></i>
            <span class="nav-main-link-name">Laporan Penawaran Produk</span>
        </a>
    </li> --}}

    {{-- <li class="nav-main-item">
        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
            <i class="nav-main-link-icon fa-solid fa-quote-left"></i>
            <span class="nav-main-link-name">Penawaran</span>
        </a>
        <ul class="nav-main-submenu">
            <li class="nav-main-item">
                <a href="javascript:void(0)" class="nav-main-link">
                    <span class="nav-main-link-name">Kelola</span>
                </a>
            </li>
        </ul>
    </li> --}}

    {{-- <li class="nav-main-item">
        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
            <i class="nav-main-link-icon fa fa-exchange"></i>
            <span class="nav-main-link-name">Transfer</span>
        </a>
        <ul class="nav-main-submenu">
            <li class="nav-main-item">
                <a href="{{ route('poz::transaction.transfer.index', request()->query()) }}" class="nav-main-link">
                    <span class="nav-main-link-name">Kelola</span>
                </a>
            </li>
        </ul>
    </li> --}}
</ul>
