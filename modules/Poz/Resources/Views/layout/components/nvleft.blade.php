<ul class="metismenu list-unstyled" id="side-menu">
    <li class="nav-main-item">
        <a class="nav-main-link active" href="{{ route('poz::dashboard', request()->query()) }}">
            <i class="nav-main-link-icon bx bxs-dashboard"></i>
            <span class="nav-main-link-name">Dashboard</span>
        </a>
    </li>

    <li class="menu-title" key="t-menu">Referensi</li>
    <li class="nav-main-item">
        <a class="has-arrow waves-effect" href="javascript:void(0)">
            <i class="nav-main-link-icon bx bx-box"></i>
            <span class="nav-main-link-name">Master Data</span>
        </a>
        <ul class="sub-menu mm-collapse">
            <li>
                <a href="{{ route('poz::master.brand.index', request()->query()) }}" class="nav-main-link">
                    <span class="nav-main-link-name">Brand</span>
                </a>
            </li>
            <li>
                <a href="{{ route('poz::master.category.index', request()->query()) }}" class="nav-main-link">
                    <span class="nav-main-link-name">Kategori</span>
                </a>
            </li>
            <li>
                <a href="{{ route('poz::master.unit.index', request()->query()) }}" class="nav-main-link">
                    <span class="nav-main-link-name">Unit</span>
                </a>
            </li>
            <li>
                <a href="{{ route('poz::master.tax.index', request()->query()) }}" class="nav-main-link">
                    <span class="nav-main-link-name">Pajak</span>
                </a>
            </li>
            <li>
                <a href="{{ route('poz::master.supplier.index', request()->query()) }}" class="nav-main-link">
                    <span class="nav-main-link-name">Supplier</span>
                </a>
            </li>
        </ul>
    </li>

    <li class="menu-title" key="t-menu">Penjadwalan</li>
    <li class="nav-main-item">
        <a class="nav-main-link active" href="{{ route('poz::schedule.supplier_schedule.index', request()->query()) }}">
            <i class="nav-main-link-icon bx bx-calendar-event"></i>
            <span class="nav-main-link-name">Jadwal Supplier</span>
        </a>
    </li>

    <li class="menu-title" key="t-menu">Transaksi</li>
    
    <li class="nav-main-item">
        <a class="nav-main-link active" href="{{ route('poz::transaction.qutation.index', request()->query()) }}">
            <i class="nav-main-link-icon mdi mdi-quora"></i>
            <span class="nav-main-link-name">Penawaran</span>
        </a>
    </li>

    <li class="nav-main-item">
        <a class="has-arrow waves-effect" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="javascript:void(0)">
            <i class="nav-main-link-icon bx bx-add-to-queue"></i>
            <span class="nav-main-link-name">Adjustment</span>
        </a>
        <ul class="sub-menu mm-collapse">
            <li class="nav-main-item">
                <a href="{{ route('poz::transaction.adjustment.index', request()->query()) }}" class="nav-main-link">
                    <span class="nav-main-link-name">Kelola</span>
                </a>
            </li>
        </ul>
    </li>


    <li class="nav-main-item">
        <a class="has-arrow waves-effect" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="javascript:void(0)">
            <i class="nav-main-link-icon bx bxs-archive"></i>
            <span class="nav-main-link-name">Product</span>
        </a>
        <ul class="sub-menu mm-collapse">
            <li class="nav-main-item">
                <a href="{{ route('poz::transaction.product.index', request()->query()) }}" class="nav-main-link">
                    <span class="nav-main-link-name">Kelola</span>
                </a>
            </li>
        </ul>
    </li>

    <li class="nav-main-item">
        <a class="has-arrow waves-effect" aria-expanded="false" href="javascript:void(0)">
            <i class="nav-main-link-icon bx bxs-cart"></i>
            <span class="nav-main-link-name">Penjualan</span>
        </a>
        <ul class="sub-menu mm-collapse">
            <li class="nav-main-item">
                <a href="{{ route('poz::transaction.pos-sale.index', request()->query()) }}" class="nav-main-link">
                    <span class="nav-main-link-name">Penjualan POS</span>
                </a>
            </li>
            {{-- <li class="nav-main-item">
                <a href="{{ route('poz::transaction.sale.index', request()->query()) }}" class="nav-main-link">
                    <span class="nav-main-link-name">Penjualan Reguler</span>
                </a>
            </li> --}}
        </ul>
    </li>

    {{-- <li class="nav-main-item">
        <a class="has-arrow waves-effect" href="javascript:void(0)">
            <i class="nav-main-link-icon bx bxs-store"></i>
            <span class="nav-main-link-name">Pembelian</span>
        </a>
        <ul class="nav-main-submenu">
            <li class="nav-main-item">
                <a href="{{ route('poz::transaction.purchase.index', request()->query()) }}" class="nav-main-link">
                    <span class="nav-main-link-name">Kelola</span>
                </a>
            </li>
        </ul>
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

    {{-- <li class="nav-main-item">
        <a class="has-arrow waves-effect" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="javascript:void(0)">
            <i class="nav-main-link-icon bx bx-arrow-back"></i>
            <span class="nav-main-link-name">Pengembalian</span>
        </a>
        <ul class="nav-main-submenu">
            <li class="nav-main-item">
                <a href="{{ route('poz::transaction.return.index', request()->query()) }}" class="nav-main-link">
                    <span class="nav-main-link-name">Kelola</span>
                </a>
            </li>
        </ul>
    </li> --}}

    <li class="menu-title" key="t-menu">Laporan</li>
    <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('poz::reporting.product_reporting.index', request()->query()) }}">
            <i class="nav-main-link-icon bx bxs-report"></i>
            <span class="nav-main-link-name">Reporting</span>
        </a>
    </li>
    <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('poz::reporting.product_supplier_reporting.index', request()->query()) }}">
            <i class="nav-main-link-icon bx bx-file"></i>
            <span class="nav-main-link-name">Supplier Shift</span>
        </a>
    </li>
</ul>
