<div class="sidebar bg-dark open border-end text-white" style="z-index: 0;">
    <div class="sidebar-header">
        <div class="d-flex align-items-center justify-content-center border-bottom text-center" style="height: 80px;">
            <img height="24" src="{{ asset('img/logo/logo-icon-bw-32.png') }}" alt="">
            <div class="h5 mb-0 ps-2">P<span class="text-danger">é</span>Mad</div>
        </div>
    </div>
    <div class="sidebar-body">
        <div class="sidebar-body-menu">
            <ul class="nav flex-column">
                <li class="divider">Utama</li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin::index') }}"> <i class="mdi mdi-apps"></i> Dasbor </a>
                </li>
                <li class="divider">Aset Manajemen</li>
                <li class="nav-item has-submenu">
                    <a class="nav-link" href="javascript:;"> <i class="mdi mdi-file-outline"></i>Kelola</a>
                    <ul class="submenu collapse">
                        <li><a class="nav-link" href="{{ route('admin::inventories.items.index') }}">Data aset keseluruhan</a></li>
                        <li><a class="nav-link" href="{{ route('admin::inventories.procurements.index') }}">Pengajuan</a></li>
                        <li><a class="nav-link" href="{{ route('admin::inventories.suppliers.index') }}">Suplier</a></li>
                    </ul>
                </li>

                <li class="nav-item has-submenu">
                    <a class="nav-link" href="javascript:;"> <i class="mdi mdi-domain"></i>Tanah Bangunan</a>
                    <ul class="submenu collapse">
                        <li><a class="nav-link" href="{{ route('admin::inventories.land.index') }}">Master Tanah</a></li>
                        <li><a class="nav-link" href="{{ route('admin::inventories.building.index') }}">Master Bangunan</a></li>
                    </ul>
                </li>

                <li class="nav-item has-submenu">
                    <a class="nav-link" href="javascript:;"> <i class="mdi mdi-office-building-cog-outline"></i>Rincian Bangunan</a>
                    <ul class="submenu collapse">
                        <li><a class="nav-link" href="{{ route('admin::inventories.floor.index') }}">Master Lantai</a></li>
                        <li><a class="nav-link" href="{{ route('admin::inventories.room.index') }}">Master Ruangan</a></li>
                    </ul>
                </li>

                <li class="nav-item has-submenu">
                    <a class="nav-link" href="javascript:;"> <i class="mdi mdi-toolbox-outline"></i>Peralatan Kantor</a>
                    <ul class="submenu collapse">
                        <li><a class="nav-link" href="{{ route('admin::inventories.tool.index') }}">Master Peralatan Kantor</a></li>
                    </ul>
                </li>

                <li class="nav-item has-submenu">
                    <a class="nav-link" href="javascript:;"> <i class="mdi mdi-motorbike"></i>Kendaraan</a>
                    <ul class="submenu collapse">
                        <li><a class="nav-link" href="{{ route('admin::inventories.vehcile.index') }}">Master Kendaraan</a></li>
                    </ul>
                </li>

                <li class="nav-item has-submenu">
                    <a class="nav-link" href="javascript:;"> <i class="mdi mdi-cart-outline"></i>Penjualan</a>
                    <ul class="submenu collapse">
                        <li><a class="nav-link" href="{{ route('admin::inventories.vehcile_sell.index') }}">Penjualan Kendaraan</a></li>
                        <li><a class="nav-link" href="{{ route('admin::inventories.tool_sell.index') }}">Penjualan Peralatan Kantor</a></li>
                        <li><a class="nav-link" href="{{ route('admin::inventories.buildings_lands.index') }}">Penjualan Tanah Bangunan</a></li>
                    </ul>
                </li>

                <li class="nav-item has-submenu">
                    <a class="nav-link" href="javascript:;"> <i class="mdi mdi-transfer"></i>Mutasi</a>
                    <ul class="submenu collapse">
                        <li><a class="nav-link" href="{{ route('admin::inventories.mutation_vehcile.index') }}">Mutasi Kendaraan</a></li>
                        <li><a class="nav-link" href="{{ route('admin::inventories.mutation_tool.index') }}">Mutasi Peralatan</a></li>
                    </ul>
                </li>

                <li class="divider">Transaksi</li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin::inventories.lend_buildings_lands.index') }}"> <i class="mdi mdi-office-building"></i>Sewa Tanah</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin::inventories.lend_vehcile.index') }}"> <i class="mdi mdi-motorbike"></i>Sewa Kendaraan</a>
                </li>


                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin::inventories.lend_tool.index') }}"> <i class="mdi mdi-tools"></i>Sewa Sewa Peralatan</a>
                </li>


                <li class="divider">Sistem</li>
                @can('access', \App\Models\Role::class)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin::system.roles.index') }}"> <i class="mdi mdi-shield-star-outline"></i> Peran </a>
                    </li>
                @endcan
                @can('access', \App\Models\Departement::class)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin::system.departments.index') }}"> <i class="mdi mdi-file-tree-outline"></i> Departemen </a>
                    </li>
                @endcan
                @can('access', \App\Models\Position::class)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin::system.positions.index') }}"> <i class="mdi mdi-tag-outline"></i> Jabatan </a>
                    </li>
                @endcan
                @can('access', \App\Models\Contract::class)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin::system.contracts.index') }}"> <i class="mdi mdi-file-document-multiple-outline"></i> Perjanjian kerja </a>
                    </li>
                @endcan
                @can('access', \Modules\Account\Models\Employee::class)
                    <li class="divider">Karyawan</li>
                    <li class="nav-item has-submenu">
                        <a class="nav-link" href="javascript:;"> <i class="mdi mdi-account-box-multiple-outline"></i> Data karyawan</a>
                        <ul class="submenu collapse">
                            <li class="nav-item disactive ms-0"><a class="nav-link" href="{{ route('admin::employment.employees.create', ['next' => route('admin::employment.employees.index')]) }}">Tambah karyawan</a></li>
                            <li class="nav-item ms-0"><a class="nav-link" href="{{ route('admin::employment.employees.index') }}">Kelola karyawan</a></li>
                        </ul>
                    </li>
                @endcan
                <li class="divider">Akun</li>
                @can('access', \Modules\Account\Models\User::class)
                    <li class="nav-item has-submenu">
                        <a class="nav-link" href="javascript:;"> <i class="mdi mdi-account-group-outline"></i> Pengguna</a>
                        <ul class="submenu collapse">
                            <li><a class="nav-link" href="{{ route('admin::system.users.index') }}">Kelola pengguna </a></li>
                            <li><a class="nav-link" href="{{ route('admin::system.user-logs.index') }}">Log </a></li>
                        </ul>
                    </li>
                @endcan
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
