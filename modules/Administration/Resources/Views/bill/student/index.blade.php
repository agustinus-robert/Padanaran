@extends('layouts.horizontal-layout')

@section('title', 'Pembayaran - ')
@section('titleTemplate', config('account.admin.name'))
@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@section('navtitle', 'Pembayaran')

@section('breadcrumb')
    <li class="breadcrumb-item">Tagihan</li>
    <li class="breadcrumb-item active">Referensi</li>
@endsection

@push('nav')
@include('administration::layouts.includes.navbar-administration')
@endpush

@section('body-content')
    @include('components.navbar-admin')

    <div class="container-fluid align-items-stretch">
        <div class="row">
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

            <div class="col-xl-6 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body d-flex">

                        <!-- Logo di kiri -->
                    <div class="me-3 d-flex align-items-start justify-content-center">
                            <span class="material-symbols-rounded" style="font-size:32px; line-height:1; opacity:0.5;">
                                account_circle
                            </span>
                        </div>

                        <!-- Konten di kanan -->
                        <div class="d-flex flex-column w-100">
                            <h5 class="fs-17 mb-2">
                                <a href="javascript:void(0);" class="text-dark mb-3">Pembayaran Khusus Murid</a>
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
                                    <x-btn href="{{ !empty($semesterStudent) ? '#applyStudent' : 'javascript:void(0);' }}"
                                    data-bs-toggle="modal" variant="dark">Kelola</x-btn>
                                @else
                                    <p class="text-danger">Belum ada siswa di semester ini</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('administration::bill.student.pay-type.pay-all')
    @include('administration::bill.student.pay-type.pay-class')
    @include('administration::bill.student.pay-type.pay-student')
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
        let studentSelect = $modal.querySelector('#student_id');


        if(!modalId == 'applyStudent'){
            if (!semesterSelect || !batchSelect || !referenceSelect) return;
        }

        if(modalId === 'applyClass' || modalId == 'applyStudent') {
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

        if(!modalId == 'applyStudent'){
            batchSelect.innerHTML     = '<option value="">Pilih</option>';
        }
   
        if(!modalId == 'applyStudent'){
            referenceSelect.innerHTML = '<option value="">Pilih</option>';
        }
    
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

            if(modalId == 'applyStudent'){
                fetch(`{{ route('api::administration.batches') }}`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(item => {
                        let opt = new Option(item.name, item.id);
                        batchSelect.add(opt);
                    });
                });
            }

            if(!modalId == 'applyStudent'){
                batchSelect.innerHTML = '<option value="">Pilih</option>';
            }

            if(!modalId == 'referenceSelect '){
                referenceSelect.innerHTML = '<option value="">Pilih</option>';
            }
            
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

        if(!modalId == 'applyStudent'){
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
    }

    // Saat modal dibuka, panggil ulang initSelects
    ['applyAll', 'applyClass', 'applyStudent'].forEach(modalId => {
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
