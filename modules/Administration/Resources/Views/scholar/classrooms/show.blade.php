@extends('layouts.horizontal-layout')

@section('title', 'Rombel - ' . $classroom->name)

@section('navtitle', 'Kelas')

@section('breadcrumb')
    <li class="breadcrumb-item">Kesiswaan</li>
    <li class="breadcrumb-item"><a href="{{ request('next', route('administration::scholar.classrooms.index')) }}">Rombel</a></li>
    <li class="breadcrumb-item active">Detail {{ $classroom->name }}</li>
@endsection

@push('style')
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
<style>
    .material-symbols-rounded {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        vertical-align: middle;
        line-height: 1;
    }
    
    .list-group-item {
        border-left: none;
        border-right: none;
        transition: all 0.2s;
    }
    .list-group-item:hover {
        background-color: #f8f9fa;
    }
</style>
@endpush

@push('nav')
    @include('administration::layouts.includes.navbar-administration')
@endpush

@section('body-content')
    @include('components.navbar-admin')

    <div class="container-fluid">
        <div class="mb-4">
            <h3 class="mb-0 font-weight-bold">
                Detail Rombel: <span class="text-primary">{{ $classroom->name }}</span>
            </h3>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="card-title mb-0 d-flex align-items-center font-weight-bold">
                            <span class="material-symbols-rounded text-primary mr-2">group_add</span>
                            Manajemen Siswa - TA {{ $classroom->semester->full_name }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($stsems->isEmpty())
                            <div class="text-center py-5">
                                <span class="material-symbols-rounded text-muted mb-3" style="font-size: 64px;">person_off</span>
                                <h5 class="text-muted">Data siswa tidak tersedia</h5>
                                <p class="text-muted small">Belum ada siswa yang teregistrasi pada semester ini.</p>
                                <a class="btn btn-outline-primary btn-sm mt-2" href="{{ route('administration::scholar.semesters.index') }}">
                                    Registrasi Semester
                                </a>
                            </div>
                        @else
                            <form action="{{ route('administration::scholar.classrooms.students', ['classroom' => $classroom->id]) }}" method="POST"> 
                                @csrf 
                                @method('PUT')
                                
                                <div class="form-group">
                                    <select class="form-control" multiple="multiple" name="stsems[]" id="duallistbox_students">
                                        @foreach ($stsems as $stsem)
                                            <option value="{{ $stsem->id }}" @if ($classroom->stsems->contains('id', $stsem->id)) selected @endif>
                                                {{ $stsem->student->user->profile->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('stsems.0'))
                                        <div class="mt-2 text-danger small d-flex align-items-center">
                                            <span class="material-symbols-rounded mr-1" style="font-size: 18px;">error</span> 
                                            Siswa yang Anda pilih tidak valid
                                        </div>
                                    @endif
                                </div>

                                <hr class="my-4">
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted small d-flex align-items-center">
                                        <span class="material-symbols-rounded mr-1" style="font-size: 18px;">help</span>
                                        Pindahkan nama ke sisi kanan untuk mendaftarkan ke rombel ini
                                    </span>
                                    <div>
                                        <a class="btn btn-light px-4 mr-2" href="{{ request('next', route('administration::scholar.classrooms.index')) }}">Batal</a>
                                        <button type="submit" class="btn btn-primary px-4 d-inline-flex align-items-center">
                                            <span class="material-symbols-rounded mr-2">save</span> 
                                            Simpan Perubahan
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 bg-primary text-white mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-white-50 text-uppercase small font-weight-bold mb-1">Total Siswa</p>
                                <h2 class="mb-0 font-weight-bold">{{ $classroom->stsems->count() }}</h2>
                            </div>
                            <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 54px; height: 54px; background: rgba(255,255,255,0.2)">
                                <span class="material-symbols-rounded" style="font-size: 32px;">groups</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 font-weight-bold d-flex align-items-center text-muted">
                            <span class="material-symbols-rounded mr-2">bolt</span> Akses Cepat
                        </h6>
                    </div>
                    <div class="list-group list-group-flush">
                        <a class="list-group-item list-group-item-action d-flex align-items-center py-3" href="{{ route('administration::scholar.students.index') }}">
                            <span class="material-symbols-rounded mr-3 text-secondary">person_search</span> Data Induk Siswa
                        </a>
                        <a class="list-group-item list-group-item-action d-flex align-items-center py-3" href="{{ route('administration::scholar.semesters.index') }}">
                            <span class="material-symbols-rounded mr-3 text-secondary">calendar_add_on</span> Registrasi Semester
                        </a>
                        <a class="list-group-item list-group-item-action d-flex align-items-center py-3" href="{{ route('administration::scholar.classrooms.index') }}">
                            <span class="material-symbols-rounded mr-3 text-secondary">account_tree</span> Kelola Rombel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-duallistbox.min.css') }}">
    <script src="{{ asset('js/bootstrap-duallistbox.min.js') }}"></script>

    <script>
        $(() => {
            if ($('#duallistbox_students').length > 0) {
                $('#duallistbox_students').bootstrapDualListbox({
                    nonSelectedListLabel: '<div class="mb-2 d-flex align-items-center text-muted small font-weight-bold"><span class="material-symbols-rounded mr-1" style="font-size: 16px;">list_alt</span> TERSEDIA</div>',
                    selectedListLabel: '<div class="mb-2 d-flex align-items-center text-success small font-weight-bold"><span class="material-symbols-rounded mr-1" style="font-size: 16px;">check_circle</span> ANGGOTA ROMBEL</div>',
                    preserveSelectionOnMove: 'moved',
                    moveOnSelect: false,
                    infoText: 'Menampilkan {0}',
                    infoTextEmpty: 'Daftar kosong',
                    filterPlaceHolder: 'Cari nama siswa...',
                    moveAllLabel: 'Pindahkan Semua',
                    removeAllLabel: 'Hapus Semua'
                });
            }
        })
    </script>
@endpush