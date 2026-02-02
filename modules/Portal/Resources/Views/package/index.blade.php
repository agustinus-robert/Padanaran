@extends('layouts.dashboarding')

@section('title', 'Paket | ')

@section('navtitle', 'Paket')

@section('body-content')
    @include('layouts.component.material-nav')

    <style>
        .material-symbols-rounded {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
        .card-custom {
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
        .table thead th {
            background-color: #f8f9fa;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05rem;
            font-weight: 700;
            border-top: none;
        }
        .status-badge {
            padding: 0.5em 1em;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .btn-action {
            width: 35px;
            height: 35px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }
        .btn-action:hover {
            transform: translateY(-2px);
        }
    </style>

    <div class="container-fluid py-4">
        <div class="page-content">
            <div class="container-fluid">
                {{-- Alerts --}}
                @if (Session::has('success') || Session::has('error'))
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" class="mb-4">
                        <div class="alert {{ Session::has('success') ? 'alert-success' : 'alert-danger' }} border-0 text-white shadow-sm" style="border-radius: 12px;">
                            <div class="d-flex align-items-center">
                                <span class="material-symbols-rounded me-2">{{ Session::has('success') ? 'check_circle' : 'error' }}</span>
                                <span>{{ Session::get('success') ?? Session::get('error') }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Header --}}
                <div class="d-flex align-items-center mb-4">
                    <a href="{{ route('portal::dashboard-msdm.index') }}" class="btn btn-link text-dark p-0 me-3">
                        <span class="material-symbols-rounded" style="font-size: 36px;">arrow_back_ios_new</span>
                    </a>
                    <div>
                        <h3 class="font-weight-bolder mb-0">Manajemen Paket</h3>
                        <p class="text-sm mb-0 text-secondary">Kelola dan pantau pengiriman paket murid.</p>
                    </div>
                </div>

                <div class="row">
                    {{-- Sisi Kiri: Tabel --}}
                    <div class="col-xl-8 col-sm-12">
                        <div class="card card-custom border-0 overflow-hidden">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="ps-4">No</th>
                                            <th>Nama Paket</th>
                                            <th>Siswa</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-end pe-4">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php($i = 1)
                                        @forelse ($packages as $package)
                                            <tr>
                                                <td class="ps-4"><span class="text-xs font-weight-bold">{{ $i++ }}</span></td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <h6 class="mb-0 text-sm">{{ $package->name }}</h6>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bold mb-0 text-dark">{{ $package->student->user->profile->name }}</p>
                                                </td>
                                                <td class="align-middle text-center text-sm">
                                                    @if($package->status == 1)
                                                        <span class="badge bg-soft-info text-info status-badge">Belum Diterima</span>
                                                    @else
                                                        <span class="badge bg-soft-success text-success status-badge">Sudah Diterima</span>
                                                    @endif
                                                </td>
                                                <td class="align-middle text-end pe-4">
                                                    <a href="javascript:void(0)"
                                                       class="btn-action bg-soft-primary text-primary btn-show-package"
                                                       data-action="{{ route('portal::package.manage.update', ['manage' => $package->id]) }}"
                                                       data-id="{{ $package->id }}"
                                                       data-name="{{ $package->name }}"
                                                       data-status="{{ $package->status }}"
                                                       data-student="{{ $package->student_id }}"
                                                       data-bs-toggle="modal"
                                                       data-bs-target="#modalEditPackage">
                                                        <span class="material-symbols-rounded" style="font-size: 18px;">edit_square</span>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-5">
                                                    @include('components.notfound')
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Sisi Kanan: Statistik & Aksi --}}
                    <div class="col-xl-4 col-sm-12">
                        @if ($packagesCount)
                            <div class="card card-custom border-0 mb-3 bg-gradient-dark">
                                <div class="card-body p-4">
                                    <div class="row align-items-center">
                                        <div class="col-8">
                                            <p class="text-white text-xs text-uppercase font-weight-bold opacity-7 mb-0">Total Paket</p>
                                            <h2 class="text-white font-weight-bolder mb-0">{{ $packagesCount }}</h2>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-white shadow text-center border-radius-md d-flex align-items-center justify-content-center ms-auto" style="width: 48px; height: 48px;">
                                                <span class="material-symbols-rounded text-dark">package_2</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <button class="btn btn-white w-100 card-custom border-0 p-4 text-start mb-3 d-flex align-items-center"
                                style="border: 2px dashed #dee2e6 !important;"
                                data-bs-toggle="modal"
                                data-bs-target="#kelolaPaketModal">
                            <div class="icon icon-shape bg-soft-primary text-primary border-radius-md d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                <span class="material-symbols-rounded">add_box</span>
                            </div>
                            <div>
                                <h6 class="mb-0 font-weight-bold text-dark">Buat Paket Baru</h6>
                                <p class="text-xs text-secondary mb-0">Klik untuk kelola paket murid</p>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Create --}}
    <div class="modal fade" id="kelolaPaketModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 1rem;">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="font-weight-bolder">Buat Paket Baru</h5>
                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="formKelolaPaket" action="{{ route('portal::package.manage.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label class="form-control-label text-xs font-weight-bold">Pilih Siswa</label>
                            <select class="form-select border-radius-md" name="student_id" required>
                                <option selected disabled>-- Pilih Siswa --</option>
                                @foreach ($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->user->profile->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-control-label text-xs font-weight-bold">Nama Paket</label>
                            <input type="text" class="form-control border-radius-md" name="name" placeholder="Misal: Paket Bulanan Januari" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-control-label text-xs font-weight-bold">Status Awal</label>
                            <select class="form-select border-radius-md" name="status">
                                <option value="1">Belum Diterima</option>
                                <option value="2">Diterima</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-link text-secondary mb-0" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn bg-gradient-primary border-radius-md px-4 mb-0">Simpan Paket</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div class="modal fade" id="modalEditPackage" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 1rem;">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="font-weight-bolder">Edit Informasi Paket</h5>
                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formEditPackage" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" id="modalPackageId" name="id">
                        <div class="form-group mb-3">
                            <label class="form-control-label text-xs font-weight-bold">Nama Paket</label>
                            <input type="text" class="form-control border-radius-md" id="modalPackageName" name="name" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-control-label text-xs font-weight-bold">Siswa</label>
                            <select class="form-select border-radius-md" id="modalPackageStudent" name="student_id" required>
                                @foreach ($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->user->profile->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-control-label text-xs font-weight-bold">Status Paket</label>
                            <select class="form-select border-radius-md" id="modalPackageStatus" name="status">
                                <option value="1">Belum Diterima</option>
                                <option value="2">Diterima</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-link text-secondary mb-0" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn bg-gradient-primary border-radius-md px-4 mb-0">Update Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- JS Asli tetap dipertahankan logikanya --}}
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
