@extends('layouts.horizontal-layout')

@section('title', 'Rombel - ')
@section('titleTemplate', config('account.admin.name'))
@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@section('breadcrumb')
    <li class="breadcrumb-item">Kesiswaan</li>
    <li class="breadcrumb-item">
        <a href="{{ request('next', route('administration::scholar.classrooms.index')) }}">Rombel</a>
    </li>
    <li class="breadcrumb-item active">Ubah rombel {{ $classroom->name }}</li>
@endsection

@push('nav')
    @include('administration::layouts.includes.navbar-administration')
@endpush

@section('body-content')

@include('components.navbar-admin')

<div class="row container-fluid">

    {{-- LEFT CONTENT --}}
    <div class="col-md-8">
        <div class="card mb-4">

            {{-- HEADER SAMA --}}
            <x-card-header type="{{ config('theme.default') }}">
                Ubah Rombel
            </x-card-header>

            <div class="card-body">
                <form class="form-block"
                      action="{{ route('administration::scholar.classrooms.update', [
                            'classroom' => $classroom->id,
                            'next' => request('next', route('administration::scholar.classrooms.index', [
                                'academic' => request('academic', $classroom->semester_id)
                            ]))
                      ]) }}"
                      method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Tahun ajaran --}}
                    <div class="input-group input-group-outline required row mb-3">
                        <label class="col-md-3 col-form-label">Tahun ajaran</label>
                        <div class="col-md-5">
                            <strong><span class="form-control-plaintext">{{ $acsem->full_name }}</span></strong>
                        </div>
                    </div>

                    {{-- Jenjang kelas --}}
                    <div class="input-group input-group-outline required row mb-3">
                        <label class="col-md-3 col-form-label">Jenjang kelas</label>
                        <div class="col-md-7">
                            <select class="form-control select-2 @error('level_id') is-invalid @enderror"
                                    name="level_id" id="level_id">
                                @foreach (getGrade()->levels as $level)
                                    <option value="{{ $level->id }}"
                                        @selected(old('level_id', $classroom->level_id) == $level->id)>
                                        {{ $level->kd . ' - ' . $level->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('level_id')
                                <small class="invalid-feedback"> {{ $message }} </small>
                            @enderror
                        </div>
                    </div>

                    {{-- Nama rombel --}}
                    <div class="input-group input-group-outline required row mb-3">
                        <label class="col-md-3 col-form-label">Nama rombel</label>
                        <div class="col-md-7">
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   name="name"
                                   id="name"
                                   placeholder="Nama rombel"
                                   value="{{ old('name', $classroom->name) }}">
                            @error('name')
                                <small class="invalid-feedback"> {{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- Ruangan --}}
                    <div class="input-group input-group-outline row mb-3">
                        <label class="col-md-3 col-form-label">Ruangan</label>
                        <div class="col-md-7">
                            <select class="form-control select-2 @error('room_id') is-invalid @enderror"
                                    name="room_id" id="room_id">
                                <option value="">-- Pilih ruang --</option>
                                @foreach ($rooms as $room)
                                    <option value="{{ $room->id }}"
                                        @selected(old('room_id', $classroom->room_id) == $room->id)>
                                        {{ $room->kd . ' - ' . $room->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('room_id')
                                <small class="invalid-feedback"> {{ $message }} </small>
                            @enderror
                        </div>
                    </div>

                    {{-- Jurusan --}}
                    <div class="input-group input-group-outline row mb-3">
                        <label class="col-md-3 col-form-label">Jurusan</label>
                        <div class="col-md-7">
                            <select class="form-control select-2 @error('major_id') is-invalid @enderror"
                                    name="major_id" id="major_id">
                                <option value="">-- Pilih jurusan --</option>
                                @foreach ($acsem->majors as $major)
                                    <option value="{{ $major->id }}"
                                        @selected(old('major_id', $classroom->major_id) == $major->id)>
                                        {{ $major->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('major_id')
                                <small class="invalid-feedback"> {{ $message }} </small>
                            @enderror
                        </div>
                    </div>

                    {{-- Unggulan --}}
                    <div class="input-group input-group-outline row mb-3">
                        <label class="col-md-3 col-form-label">Unggulan</label>
                        <div class="col-md-7">
                            <select class="form-control select-2 @error('superior_id') is-invalid @enderror"
                                    name="superior_id" id="superior_id">
                                <option value="">-- Pilih unggulan --</option>
                                @foreach ($acsem->superiors as $superior)
                                    <option value="{{ $superior->id }}"
                                        @selected(old('superior_id', $classroom->superior_id) == $superior->id)>
                                        {{ $superior->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('superior_id')
                                <small class="invalid-feedback"> {{ $message }} </small>
                            @enderror
                        </div>
                    </div>

                    {{-- Wali kelas --}}
                    <div class="input-group input-group-outline row mb-3">
                        <label class="col-md-3 col-form-label">Wali kelas</label>
                        <div class="col-md-7">
                            <select class="form-control select-2 @error('supervisor_id') is-invalid @enderror"
                                    name="supervisor_id" id="supervisor_id">
                                <option value="">-- Pilih wali kelas --</option>
                                @foreach ($supervisors as $supervisor)
                                    <option value="{{ $supervisor->id }}"
                                        @selected(old('supervisor_id', $classroom->supervisor_id) == $supervisor->id)>
                                        {{ $supervisor->user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('supervisor_id')
                                <small class="invalid-feedback"> {{ $message }} </small>
                            @enderror
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="input-group input-group-outline row mb-0">
                        <div class="col-md-8 offset-md-3">
                            <button class="btn btn-success">Simpan</button>
                            <a class="btn btn-secondary"
                               href="{{ request('next', route('administration::scholar.classrooms.index', ['academic' => $classroom->semester_id])) }}">
                                Kembali
                            </a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- RIGHT CONTENT --}}
    <div class="col-md-4">

        {{-- Tahun ajaran --}}
        <div class="card mb-3">
            <div class="card-header pb-0 p-3">
                <h6 class="text-black">Tahun Ajaran</h6>
            </div>

            <div class="card-body">
                <div class="form-block">
                    <div class="form-group mb-0">

                        <!-- WRAPPER AGAR TIDAK BISA KLIK -->
                        <div class="input-group w-100 pointer-events-none" style="opacity: 1;">

                            <select class="form-control">
                                @foreach ($acsems as $_acsem)
                                    <option value="{{ $_acsem->id }}"
                                        @selected(request('academic', $acsem->id) == $_acsem->id)>
                                        {{ $_acsem->full_name }}
                                    </option>
                                @endforeach
                            </select>

                            <div class="input-group-append">
                                <button class="btn bg-gradient-dark disabled">Tetapkan</button>
                            </div>

                        </div>
                        <!-- END WRAPPER -->

                    </div>
                </div>
            </div>
        </div>


        {{-- Lanjutan --}}
        <div class="card">
            <div class="card-header pb-0 p-3">
                <h6>Lanjutan</h6>
            </div>

            <div class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action text-black"
                   href="{{ route('administration::scholar.majors.index', ['academic' => request('academic')]) }}">
                    <i class="mdi mdi-folder-settings-variant-outline"></i> Kelola jurusan
                </a>

                <a class="list-group-item list-group-item-action text-black"
                   href="{{ route('administration::scholar.superiors.index', ['academic' => request('academic')]) }}">
                    <i class="mdi mdi-file-settings-variant-outline"></i> Kelola unggulan
                </a>
            </div>
        </div>

    </div>

</div>
@endsection
