@extends('layouts.horizontal-layout')

@section('title', 'Pembayaran - ')
@section('titleTemplate', config('account.admin.name'))
@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@section('breadcrumb')
    <li class="breadcrumb-item">Tagihan</li>
    <li class="breadcrumb-item active">Referensi</li>
@endsection

@push('nav')
@include('administration::layouts.includes.navbar-administration')
@endpush

@section('body-content')
    <div class="row container-fluid align-items-stretch">
        @include('components.navbar-admin')

        <div class="col-xl-12">
            @if (session('success'))
                <div id="flash-success" class="alert alert-success mt-4">
                    {!! session('success') !!}
                </div>
            @endif

            @if (request('trash'))
                <div class="alert alert-warning text-danger mb-0 mt-3">
                    <i class="mdi mdi-alert-circle-outline"></i> Menampilkan data yang dihapus
                </div>
            @endif
        </div>
       <div class="col-xl-6 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body d-flex">
                    <!-- Logo di kiri -->
                    <div class="me-3 d-flex align-items-start justify-content-center">
                        <span class="material-symbols-rounded" style="font-size:32px; line-height:1; opacity:0.5;">
                            person_raised_hand
                        </span>
                    </div>

                    <!-- Konten di kanan -->
                    <div class="d-flex flex-column w-100">
                        <h5 class="fs-17 mb-2">
                            <a href="javascript:void(0);" class="text-dark mb-3">
                                Semua pembayaran akan didistribusikan bagi semua murid

                            </a>
                            <p>
                                <small class="text-muted fw-normal">
                                    Jenjang
                                    {{ auth()->user()->employee->education->name }}

                                </small>
                            </p>
                        </h5>

                        <div class="mt-auto hstack gap-2">
                            {{-- <a href="#!" data-bs-toggle="modal" class="btn btn-soft-success w-100">Lihat Murid</a> --}}
                            @if(count($semesterStudent) > 0)
                                <x-btn href="#applyAll" data-bs-toggle="modal" variant="dark">Kelola</x-btn>
                            @else
                                <p class="text-danger">Belum ada siswa di semester ini</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body d-flex">

                    <!-- Logo di kiri -->
                   <div class="me-3 d-flex align-items-start justify-content-center">
                        <span class="material-symbols-rounded" style="font-size:32px; line-height:1; opacity:0.5;">
                            cast_for_education
                        </span>
                    </div>

                    <!-- Konten di kanan -->
                    <div class="d-flex flex-column w-100">
                        <h5 class="fs-17 mb-2">
                            <a href="javascript:void(0);" class="text-dark mb-3">Pembayaran Per Kelas</a>
                            <p>
                                <small class="text-muted fw-normal">

                                    Jenjang {{ auth()->user()->employee->education->name }}
                                    {{-- 0 Jumlah Kelas didistribusikan pembayaran --}}
                                </small>
                            </p>
                        </h5>

                        <div class="mt-auto hstack gap-2">
                            {{-- <a href="#!" data-bs-toggle="modal" class="btn btn-soft-success w-100">Lihat Kelas</a> --}}
                            @if(count($semesterStudent) > 0)
                                <x-btn href="{{ !empty($semesterStudent) ? '#applyClass' : 'javascript:void(0);' }}"
                                data-bs-toggle="modal" variant="dark">Kelola</x-btn>
                            @else
                                <p class="text-danger">Belum ada siswa di semester ini</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- <div class="col-xl-6 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex">
                   <div class="me-3 d-flex align-items-start justify-content-center"
                        style="width:60px; height:60px; border-radius:12px; background:#f5f6fa;">
                        <i class="mdi mdi mdi-account-outline text-danger" style="font-size:32px;"></i>
                    </div>


                    <div class="d-flex flex-column w-100">
                        <h5 class="fs-17 mb-2">
                            <a href="javascript:void(0);" class="text-dark mb-3">Pembayaran Extra Per Murid</a>
                            <p>
                                <small class="text-muted fw-normal">
                                    0 Jumlah komponen extra dipasang
                                </small>
                            </p>
                        </h5>

                        <div class="mt-auto hstack gap-2">

                            <a href="#applyStudent" data-bs-toggle="modal" class="btn btn-soft-primary w-100">Kelola</a>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        {{-- <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header"><i class="mdi mdi-office-building float-left mr-2"></i>Data Referensi Pembayaran</div>
                <div class="card-body">
                    <form action="{{ route('administration::bill.students.index') }}" method="GET">
                        <input type="hidden" name="trash" value="{{ request('trash') }}">
                        <div class="input-group">
                            <input class="form-control" name="search" type="text" value="{{ request('search') }}" placeholder="Cari nama disini ...">
                            <div class="input-group-append">
                                <a class="btn btn-outline-secondary" href="{{ route('administration::bill.students.index') }}"><i class="mdi mdi-refresh"></i></a>
                                <button class="btn btn-primary">Cari</button>
                            </div>
                        </div>
                    </form>

                    @if (session('success'))
                        <div id="flash-success" class="alert alert-success mt-4">
                            {!! session('success') !!}
                        </div>
                    @endif

                    @if (request('trash'))
                        <div class="alert alert-warning text-danger mb-0 mt-3">
                            <i class="mdi mdi-alert-circle-outline"></i> Menampilkan data yang dihapus
                        </div>
                    @endif
                </div>
                <div class="table-responsive">
                    <table class="table-hover border-bottom mb-0 table">
                        <thead class="thead-dark">
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Semester</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{$student->semester->student->user->name}}</td>
                                    <td>{{ $student->semester->semester->name }}</td>
                                    <td>
                                        @if ($student->trashed())
                                            <form class="d-inline form-block form-confirm" action="{{ route('administration::bill.students.restore', ['student' => $student->id]) }}" method="POST"> @csrf @method('PUT')
                                                <button class="btn btn-primary btn-sm" data-toggle="tooltip" title="Pulihkan"><i class="mdi mdi-restore"></i></button>
                                            </form>
                                            <form class="d-inline form-block form-confirm" action="{{ route('administration::bill.students.kill', ['student' => $student->id]) }}" method="POST"> @csrf @method('DELETE')
                                                <button class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus Permanen"><i class="mdi mdi-delete-outline"></i></button>
                                            </form>
                                        @else
                                            <a class="btn btn-info btn-sm" data-toggle="tooltip" title="Kelola Komponen Pembayaran" href="{{ route('administration::bill.students.edit', ['student' => $student->id]) }}"><i class="mdi mdi-eye"></i></a>
                                            <form class="d-inline form-block form-confirm" action="{{ route('administration::bill.students.destroy', ['student' => $student->id]) }}" method="POST"> @csrf @method('DELETE')
                                                <button class="btn btn-danger btn-sm" data-toggle="tooltip" title="Buang"><i class="mdi mdi-delete-outline"></i></button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center"><i>Tidak ada data</i></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <p style="margin-top: 10px; margin-left: 10px;">Jumlah Siswa : {{ $studentCount }}</p>
                </div>
                <div class="card-body">
                    {{ $students->appends(request()->all())->links() }}
                </div>
            </div>
        </div> --}}
        {{-- <div class="col-md-4">

            <div class="card">
                <div class="card-header">
                    <i class="mdi mdi-cogs float-left mr-2"></i>Lanjutan
                </div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('administration::bill.students.create') }}"><i class="mdi mdi-plus-circle-outline"></i> Tambah Pembayaran Siswa</a>
                    <a class="list-group-item list-group-item-action text-danger" href="{{ route('administration::facility.buildings.index', ['trash' => request('trash', 0) ? null : 1]) }}"><i class="mdi mdi-delete-outline"></i> Tampilkan Gedung yang {{ request('trash', 0) ? 'tidak' : '' }} dihapus</a>
                </div>
            </div>
        </div> --}}
    </div>

    <x-regular-modal id="applyAll" title="Pembayaran Keseluruhan Murid">
        <form id="formAll" method="POST" action="{{ route('administration::bill.students.store') }}">
            @csrf
            <input type="hidden" name="status" value="1" />
            <input type="hidden" name="education" value="{{ request()->education }}" />

            {{-- Semester --}}
            <x-input-group :isRow="true">
                <x-col size="6">
                    <x-label value="Masukkan Semester" />
                </x-col>
                <x-col size="6">
                    <x-select id="semester_id" name="semester_id" placeholder="Pilih" :options="[]" required />
                </x-col>
            </x-input-group>

            {{-- Gelombang --}}
            <x-input-group :isRow="true">
                <x-col size="6">
                    <x-label value="Masukkan Gelombang" />
                </x-col>
                <x-col size="6">
                    <x-select id="batch_id" name="batch_id" placeholder="Pilih" :options="[]" required />
                </x-col>
            </x-input-group>

            {{-- Paket Pembayaran --}}
            <x-input-group :isRow="true">
                <x-col size="6">
                    <x-label value="Masukkan Paket Pembayaran" />
                </x-col>
                <x-col size="6">
                    <x-select id="reference_id" name="package" placeholder="Pilih" :options="[]" required />
                </x-col>
            </x-input-group>

            {{-- Tombol Aksi --}}
            <div class="d-flex justify-content-end gap-2 mt-3">
                <x-btn type="button" variant="secondary" data-bs-dismiss="modal">Tutup</x-btn>
                <x-btn type="submit" variant="success">Proses</x-btn>
            </div>
        </form>
    </x-regular-modal>



    <x-regular-modal id="applyClass" title="Pembayaran Per Kelas">
        <form id="formApply" method="POST" action="{{ route('administration::bill.students.store') }}">
            @csrf
            <input type="hidden" name="status" value="2" />
            <input type="hidden" name="education" value="{{ request()->education }}" />

            {{-- Pilih Kelas --}}
            <x-input-group :isRow="true">
                <x-col size="4">
                    <x-label value="Pilih Kelas" />
                </x-col>
                <x-col size="8">
                    <x-select id="class_id" name="class_id" placeholder="Pilih" :options="[]" required />
                </x-col>
            </x-input-group>

            {{-- Pilih Rombel --}}
            <x-input-group :isRow="true">
                <x-col size="4">
                    <x-label value="Pilih Rombel" />
                </x-col>
                <x-col size="8">
                    <x-select id="classroom_id" name="classroom_id" placeholder="Pilih" :options="[]" required />
                </x-col>
            </x-input-group>

            {{-- Masukkan Semester --}}
            <x-input-group :isRow="true">
                <x-col size="4">
                    <x-label value="Masukkan Semester" />
                </x-col>
                <x-col size="8">
                    <x-select id="semester_id" name="semester_id" placeholder="Pilih" :options="[]" required />
                </x-col>
            </x-input-group>

            {{-- Masukkan Gelombang --}}
            <x-input-group :isRow="true">
                <x-col size="4">
                    <x-label value="Masukkan Gelombang" />
                </x-col>
                <x-col size="8">
                    <x-select id="batch_id" name="batch_id" placeholder="Pilih" :options="[]" required />
                </x-col>
            </x-input-group>

            {{-- Masukkan Paket Pembayaran --}}
            <x-input-group :isRow="true">
                <x-col size="4">
                    <x-label value="Masukkan Paket Pembayaran" />
                </x-col>
                <x-col size="8">
                    <x-select id="reference_id" name="package" placeholder="Pilih" :options="[]" required />
                </x-col>
            </x-input-group>

            {{-- Tombol Aksi --}}
            <div class="d-flex justify-content-end gap-2 mt-3">
                <x-btn type="button" variant="secondary" data-bs-dismiss="modal">Tutup</x-btn>
                <x-btn type="submit" variant="success">Proses</x-btn>
            </div>
        </form>
    </x-regular-modal>

@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {

    function initSelects($modal) {
        let modalId = $modal.id;

        let gradeSelect = $modal.querySelector('#class_id');
        let classSelect = $modal.querySelector('#classroom_id');
        let semesterSelect = $modal.querySelector("#semester_id");
        let batchSelect    = $modal.querySelector("#batch_id");
        let referenceSelect= $modal.querySelector("#reference_id");

        if (!semesterSelect || !batchSelect || !referenceSelect) return;

        if(modalId === 'applyClass') {
            if(gradeSelect){
                gradeSelect.innerHTML = '<option value="">Pilih</option>';
            }

            if(classSelect){
                classSelect.innerHTML = '<option value="">Pilih</option>';
            }

            fetch("{{ route('api::administration.grade_class') }}")
                .then(res => res.json())
                .then(data => {
                    data.forEach(item => {
                        let opt = new Option(item.name, item.id);
                        gradeSelect.add(opt);
                    });
                });


            gradeSelect.addEventListener("change", function() {
                let gradeId = this.value;

                if (!gradeId) return;

                fetch(`{{ route('api::administration.classrooms') }}?class_id=${gradeId}`)
                    .then(res => res.json())
                    .then(data => {
                        data.forEach(item => {
                            let opt = new Option(item.name, item.id);
                            classSelect.add(opt);
                        });
                    });
            });
        }

        semesterSelect.innerHTML  = '<option value="">Pilih</option>';
        batchSelect.innerHTML     = '<option value="">Pilih</option>';
        referenceSelect.innerHTML = '<option value="">Pilih</option>';

        fetch("{{ route('api::administration.semesters') }}")
            .then(res => res.json())
            .then(data => {
                data.forEach(item => {
                    let opt = new Option(item.name, item.id);
                    semesterSelect.add(opt);
                });
            });

        semesterSelect.addEventListener("change", function() {
            let semesterId = this.value;
            batchSelect.innerHTML = '<option value="">Pilih</option>';
            referenceSelect.innerHTML = '<option value="">Pilih</option>';

            if (!semesterId) return;

            fetch(`{{ route('api::administration.batches') }}?semester_id=${semesterId}`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(item => {
                        let opt = new Option(item.name, item.id);
                        batchSelect.add(opt);
                    });
                });
        });

        // Event: pilih batch -> ambil reference
        batchSelect.addEventListener("change", function() {
            let batchId = this.value;
            referenceSelect.innerHTML = '<option value="">Pilih</option>';

            if (!batchId) return;

            fetch(`{{ route('api::administration.references_category') }}?batch_id=${batchId}`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(item => {
                        let opt = new Option(item.payment_category_label, item.payment_category);
                        referenceSelect.add(opt);
                    });
                });
        });
    }

    // Saat modal dibuka, panggil ulang initSelects
    ['applyAll', 'applyClass'].forEach(modalId => {
        let modalEl = document.getElementById(modalId);
        modalEl.addEventListener('shown.bs.modal', function() {
            initSelects(modalEl);
        });
    });

    // ['formAll', 'formClass'].forEach(formId => {
    //     let form = document.getElementById(formId);
    //     if (!form) return;

    //     form.addEventListener('submit', function(e) {
    //         e.preventDefault();

    //         let formData = new FormData(form);
    //         let modalEl = form.closest('.modal');
    //         let modalId = modalEl.id;

    //         // Ambil status
    //         let status = formData.get('status');
    //         let education = formData.get('education')
    //         let payload = { status: status, education: education };

    //         if(modalId === 'applyClass') {
    //             payload.grade_id = formData.get('class_id');
    //             payload.class_id = formData.get('classroom_id');
    //             payload.semester_id = formData.get('semester_id');
    //             payload.batch_id = formData.get('batch_id');
    //             payload.package_id = formData.get('package');
    //         } else if(modalId === 'applyAll') {
    //             payload.semester_id = formData.get('semester_id');
    //             payload.batch_id = formData.get('batch_id');
    //             payload.package_id = formData.get('package');
    //         }

    //         fetch("{{ route('administration::bill.students.store') }}", {
    //             method: "POST",
    //             headers: {
    //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    //             },
    //             body: new URLSearchParams(payload)
    //         })
    //         .then(res => res.json())
    //         .then(data => {
    //             console.log('Response:', data);
    //             // tampilkan notifikasi sukses
    //         })
    //         .catch(err => console.error(err));
    //     });
    // });

});
</script>

@endpush
