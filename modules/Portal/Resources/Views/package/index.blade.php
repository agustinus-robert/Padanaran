@extends('portal::layouts.index')

@section('title', 'Paket | ')

@section('contents')
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
                @php($user=auth()->user())
                @include('portal::layouts.components.notifications')
                
                @include('layouts.shortcut_menu')

                @include('layouts.nav_name')
                
            </div>
    </header>

    <div class="topnav">
        <div class="container-fluid">
            <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

                <div class="navbar-collapse collapse" id="topnav-menu-content">
                    <ul class="navbar-nav">

                        <li class="nav-item">
                            <a class="nav-link arrow-none" href="{{ route('portal::dashboard.index') }}" id="topnav-dashboard" role="button">
                                <i class="bx bx-home-circle me-2"></i><span key="t-dashboards">Dashboards</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                @if (Session::has('success'))
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                        <div class="alert alert-success">
                            {{ Session::get('success') }}
                        </div>
                    </div>
                @endif

                @if (Session::has('error'))
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                        <div class="alert-danger alert">
                            {{ Session::get('error') }}
                        </div>
                    </div>
                @endif

                <div class="d-flex align-items-center mb-4">
                    <a class="text-decoration-none" href="{{ route('portal::dashboard-msdm.index') }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                    <div class="ms-4">
                        <h2 class="mb-1">Paket Murid</h2>
                        <div class="text-muted">Yuk! Kelola paket murid</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-8 col-sm-12">
                        <div class="card border-0">
                            {{-- <div class="card-body d-flex align-items-center justify-content-between py-2">
                    <div><i class="mdi mdi-calendar-multiselect"></i> Paket </div>
                    <form class="tg-steps-presence-history" action="{{ route('portal::package.manage.index') }}" method="GET">
                        <div class="input-group input-group-sm">
                            <input class="form-control" type="month" name="month" value="{{ $month->format('Y-m') }}">
                            <button class="btn btn-dark"><i class="mdi mdi-eye-outline"></i> <span class="d-none d-sm-inline">Tampilkan</span></button>
                        </div>
                    </form>
                </div> --}}
                            <div class="table-responsive tg-steps-presence-calendar">
                                @php($headings = ['No', 'Nama Paket', 'Siswa', 'Status Paket', ''])
                                <table class="table-bordered calendar mb-0 table text-center">
                                    <thead>
                                        <tr>
                                            @foreach ($headings as $heading)
                                                <th class="text-center">{{ $heading }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php($i = 1)
                                        @forelse ($packages as $package)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $package->name }}</td>
                                                <td>{{ $package->student->user->profile->name }}</td>
                                                <td>{{ $package->status == 1 ? 'Belum Diteima' : 'Sudah Diterima' }}</td>
                                                <td><a href="javascript:void(0)" class="btn btn-soft-info btn-show-package rounded px-2 py-1" data-action="{{ route('portal::package.manage.update', ['manage' => $package->id]) }}" data-id="{{ $package->id }}" data-name="{{ $package->name }}" data-status="{{ $package->status }}" data-student="{{ $package->student_id }}" title="Edit Paket" data-bs-toggle="modal" data-bs-target="#modalEditPackage">
                                                        <i class="mdi mdi-eye-outline"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6">
                                                    @include('components.notfound')
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-sm-12">
                        @if ($packagesCount)
                            <div class="card mb-3 border-0">
                                <div class="card-body d-flex justify-content-between align-items-center flex-row py-4">
                                    <div>
                                        <div class="display-4">{{ $packagesCount }}</div>
                                        <div class="small fw-bold text-secondary text-uppercase">Jumlah paket siswa</div>
                                    </div>
                                    <div><i class="mdi mdi-timer-outline mdi-48px text-danger"></i></div>
                                </div>
                            </div>
                        @endif
                        <a class="btn btn-outline-secondary w-100 d-flex text-dark mb-3 rounded bg-white py-3 text-start" style="border-style: dashed;" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#kelolaPaketModal">
                            <i class="mdi mdi-calendar-multiple-check me-3"></i>
                            <div>Kelola paket <br> <small class="text-muted">Buat Paket disini</small></div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="kelolaPaketModal" tabindex="-1" aria-labelledby="kelolaPaketModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="kelolaPaketModalLabel">Buat Paket Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form method="POST" id="formKelolaPaket" action="{{ route('portal::package.manage.store') }}">
                    @csrf
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="studentSelect" class="form-label">Pilih Siswa</label>
                            <select class="form-select" id="studentSelect" name="student_id">
                                <option selected disabled>-- Pilih Siswa --</option>
                                @if (count($students))
                                    @foreach ($students as $student)
                                        <option value="{{ $student->id }}">{{ $student->user->profile->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="packageName" class="form-label">Nama Paket</label>
                            <input type="text" class="form-control" id="packageName" name="name" placeholder="Contoh: Paket A">
                        </div>

                        <div class="mb-3">
                            <label for="packageStatus" class="form-label">Status Paket</label>
                            <select class="form-select" id="packageStatus" name="status">
                                <option value="1">Belum Diterima</option>
                                <option value="2">Diterima</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" form="formKelolaPaket">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="showPackageModal" tabindex="-1" aria-labelledby="showPackageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="showPackageModalLabel">Detail Paket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div id="showPackageContent">
                        <p>Memuat data...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modalEditPackage" tabindex="-1" aria-labelledby="modalEditPackageLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Paket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form id="formEditPackage" method="POST">
                    <div class="modal-body">
                        @csrf
                        @method('PUT')

                        <input type="hidden" id="modalPackageId" name="id">

                        <div class="mb-3">
                            <label for="modalPackageName" class="form-label">Nama Paket</label>
                            <input type="text" class="form-control" id="modalPackageName" name="name">
                        </div>

                        <div class="mb-3">
                            <label for="modalPackageStudent" class="form-label">Siswa</label>
                            <select class="form-select" id="modalPackageStudent" name="student_id">
                                @if (count($students) > 0)
                                    @foreach ($students as $student)
                                        <option value="{{ $student->id }}">{{ $student->user->profile->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="modalPackageStatus" class="form-label">Status</label>
                            <select class="form-select" id="modalPackageStatus" name="status">
                                <option value="1">Belum Diterima</option>
                                <option value="2">Diterima</option>
                            </select>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-show-package').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const form = document.getElementById('formEditPackage');

                    form.action = this.dataset.action;
                    document.getElementById('modalPackageId').value = this.dataset.id;
                    document.getElementById('modalPackageName').value = this.dataset.name;

                    const status = this.dataset.status?.toString();
                    const student = this.dataset.student?.toString();

                    const statusSelect = document.getElementById('modalPackageStatus');
                    const studentSelect = document.getElementById('modalPackageStudent');

                    statusSelect.querySelectorAll('option').forEach(opt => opt.selected = false);
                    const selectedStatus = statusSelect.querySelector(`option[value="${status}"]`);
                    if (selectedStatus) selectedStatus.selected = true;

                    studentSelect.querySelectorAll('option').forEach(opt => opt.selected = false);
                    const selectedStudent = studentSelect.querySelector(`option[value="${student}"]`);
                    if (selectedStudent) selectedStudent.selected = true;
                });
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        table.calendar>tbody>tr>td:hover {
            background: #fafafa;
        }

        .pulse-soft-danger {
            animation: pulse-soft-danger 1s infinite;
        }

        .pulse-soft-danger:hover {
            animation: none;
        }

        @-webkit-keyframes pulse-soft-danger {
            0% {
                -webkit-box-shadow: 0 0 0 0 rgba(255, 217, 215, .6);
            }

            80% {
                -webkit-box-shadow: 0 0 0 10px rgba(255, 217, 215, 0);
            }

            100% {
                -webkit-box-shadow: 0 0 0 0 rgba(255, 217, 215, 0);
            }
        }

        @keyframes pulse-soft-danger {
            0% {
                -moz-box-shadow: 0 0 0 0 rgba(255, 217, 215, .6);
                box-shadow: 0 0 0 0 rgba(255, 217, 215, .6);
            }

            80% {
                -moz-box-shadow: 0 0 0 10px rgba(255, 217, 215, 0);
                box-shadow: 0 0 0 10px rgba(255, 217, 215, 0);
            }

            100% {
                -moz-box-shadow: 0 0 0 0 rgba(255, 217, 215, 0);
                box-shadow: 0 0 0 0 rgba(255, 217, 215, 0);
            }
        }
    </style>
@endpush
