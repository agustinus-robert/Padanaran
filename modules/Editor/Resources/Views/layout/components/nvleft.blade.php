<ul class="nav-main">
    <li class="nav-main-item">
        <a class="nav-main-link active" href="{{ route('poz::dashboard', request()->query()) }}">
            <i class="nav-main-link-icon fa fa-location-arrow"></i>
            <span class="nav-main-link-name">Dashboard</span>
        </a>
    </li>

    <li class="nav-main-heading">Referensi</li>
    <li class="nav-main-item">
        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
            <i class="nav-main-link-icon fa fa-file"></i>
            <span class="nav-main-link-name">Master Data</span>
        </a>
        <ul class="nav-main-submenu">
            <li class="nav-main-item">
                <a href="{{ route('poz::master.brand.index', request()->query()) }}" class="nav-main-link">
                    <span class="nav-main-link-name">Brand</span>
                </a>
            </li>
            <li class="nav-main-item">
                <a href="{{ route('poz::master.category.index', request()->query()) }}" class="nav-main-link">
                    <span class="nav-main-link-name">Kategori</span>
                </a>
            </li>
            <li class="nav-main-item">
                <a href="{{ route('poz::master.unit.index', request()->query()) }}" class="nav-main-link">
                    <span class="nav-main-link-name">Unit</span>
                </a>
            </li>
            <li class="nav-main-item">
                <a href="{{ route('poz::master.tax.index', request()->query()) }}" class="nav-main-link">
                    <span class="nav-main-link-name">Pajak</span>
                </a>
            </li>
        </ul>
    </li>



    <li class="nav-main-heading">Transaksi</li>
    <li class="nav-main-item">
        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
            <i class="nav-main-link-icon fas fa-box-open"></i>
            <span class="nav-main-link-name">Product</span>
        </a>
        <ul class="nav-main-submenu">
            <li class="nav-main-item">
                <a href="{{ route('poz::transaction.product.index', request()->query()) }}" class="nav-main-link">
                    <span class="nav-main-link-name">Kelola</span>
                </a>
            </li>
        </ul>
    </li>

    <li class="nav-main-item">
        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
            <i class="nav-main-link-icon fa fa-shopping-cart"></i>
            <span class="nav-main-link-name">Penjualan</span>
        </a>
        <ul class="nav-main-submenu">
            <li class="nav-main-item">
                <a href="{{ route('poz::transaction.pos-sale.index', request()->query()) }}" class="nav-main-link">
                    <span class="nav-main-link-name">Penjualan POS</span>
                </a>
            </li>
            <li class="nav-main-item">
                <a href="{{ route('poz::transaction.sale.index', request()->query()) }}" class="nav-main-link">
                    <span class="nav-main-link-name">Penjualan Reguler</span>
                </a>
            </li>
        </ul>
    </li>

    <li class="nav-main-item">
        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
            <i class="nav-main-link-icon far fa-clipboard"></i>
            <span class="nav-main-link-name">Pembelian</span>
        </a>
        <ul class="nav-main-submenu">
            <li class="nav-main-item">
                <a href="{{ route('poz::transaction.purchase.index', request()->query()) }}" class="nav-main-link">
                    <span class="nav-main-link-name">Kelola</span>
                </a>
            </li>
        </ul>
    </li>

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

    <li class="nav-main-item">
        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="javascript:void(0)">
            <i class="nav-main-link-icon fa fa-undo"></i>
            <span class="nav-main-link-name">Pengembalian</span>
        </a>
        <ul class="nav-main-submenu">
            <li class="nav-main-item">
                <a href="{{ route('poz::transaction.return.index', request()->query()) }}" class="nav-main-link">
                    <span class="nav-main-link-name">Kelola</span>
                </a>
            </li>
        </ul>
    </li>

    <li class="nav-main-heading">Laporan</li>
    <li class="nav-main-item">
        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
            <i class="nav-main-link-icon fa fa-file"></i>
            <span class="nav-main-link-name">Reporting</span>
        </a>
        <ul class="nav-main-submenu">
            <li class="nav-main-item">
                <a href="" class="nav-main-link">
                    <span class="nav-main-link-name">Harian</span>
                </a>
            </li>
            <li class="nav-main-item">
                <a href="" class="nav-main-link">
                    <span class="nav-main-link-name">Bulanan</span>
                </a>
            </li>
            <li class="nav-main-item">
                <a href="" class="nav-main-link">
                    <span class="nav-main-link-name">Tahunan</span>
                </a>
            </li>
        </ul>
    </li>

    <li class="nav-main-heading">Konfigurasi</li>
    <li class="nav-main-item">
        <a class="nav-main-link active" href="javascript:void(0)">
            <i class="nav-main-link-icon fa fa-gear"></i>
            <span class="nav-main-link-name">Site Configuration</span>
        </a>
    </li>
</ul>
