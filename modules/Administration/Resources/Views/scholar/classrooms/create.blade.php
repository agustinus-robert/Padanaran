@extends('layouts.horizontal-layout')

@section('title', 'Rombel - ')
@section('titleTemplate', config('account.admin.name'))
@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@section('navtitle', 'Kelas')


@section('breadcrumb')
    <li class="breadcrumb-item">Kesiswaan</li>
    <li class="breadcrumb-item"><a href="{{ request('next', route('administration::scholar.classrooms.index')) }}">Rombel</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@push('nav')
    @include('administration::layouts.includes.navbar-administration')
@endpush

@section('body-content')

@include('components.navbar-admin')

<div class="row container-fluid">

    <div class="col-md-8">
        <div class="card mb-4">
            <x-card-header type="{{ config('theme.default') }}">
                Tambah Rombel
            </x-card-header>


            <div class="card-body">

                <form class="form-block"
                    action="{{ route('administration::scholar.classrooms.store', [
                            'semester_id' => $acsem->id,
                            'next'        => request('next', route('administration::scholar.classrooms.index', [
                                'academic' => request('academic', $acsem->id)
                            ]))
                    ]) }}"
                    method="POST">
                    @csrf

                    <x-input-group>
                        <x-label for="semester_id" value="Tahun ajaran" col="3" />

                        <x-col size="5">
                            <strong><span class="form-control-plaintext">{{ $acsem->full_name }}</span></strong>
                        </x-col>
                    </x-input-group>


                    <x-input-group label="Jenjang kelas" required>
                        <x-select-2
                            name="level_id"
                            :value="old('level_id')"
                            :options="getGrade()->levels->map(fn($level) => [
                                'value' => $level->id,
                                'label' => $level->kd . ' - ' . $level->name
                            ])"
                        />
                    </x-input-group>


                    <x-input-group label="Nama rombel" required>
                        <input type="text"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="Nama rombel"
                            value="{{ old('name') }}">
                        @error('name')
                            <small class="invalid-feedback">{{ $message }}</small>
                        @enderror
                    </x-input-group>


                    <x-input-group label="Ruangan">
                        <x-select-2
                            name="room_id"
                            :value="old('room_id')"
                            placeholder="-- Pilih ruang --"
                            :options="$rooms->map(fn($room) => [
                                'value' => $room->id,
                                'label' => $room->kd . ' - ' . $room->name
                            ])"
                        />
                    </x-input-group>


                    <x-input-group label="Jurusan">
                        <x-select-2
                            name="major_id"
                            :value="old('major_id')"
                            placeholder="-- Pilih jurusan --"
                            :options="$acsem->majors->map(fn($major) => [
                                'value' => $major->id,
                                'label' => $major->name
                            ])"
                        />
                    </x-input-group>


                    <x-input-group label="Unggulan">
                        <x-select-2
                            name="superior_id"
                            :value="old('superior_id')"
                            placeholder="-- Pilih unggulan --"
                            :options="$acsem->superiors->map(fn($s) => [
                                'value' => $s->id,
                                'label' => $s->name
                            ])"
                        />
                    </x-input-group>


                    <x-input-group label="Wali kelas">
                        <x-select-2
                            name="supervisor_id"
                            :value="old('supervisor_id')"
                            placeholder="-- Pilih wali kelas --"
                            :options="$supervisors->map(fn($spv) => [
                                'value' => $spv->id,
                                'label' => $spv->user->name
                            ])"
                        />
                    </x-input-group>


                    <x-input-group>
                        <x-col size="8" offset="3">
                            <x-btn type="submit" variant="success">Simpan</x-btn>
                            <a class="btn btn-secondary"
                            href="{{ request('next', route('administration::scholar.classrooms.index', ['academic' => $acsem->id])) }}">
                                Kembali
                            </a>
                        </x-col>
                    </x-input-group>

                </form>

            </div>
        </div>
    </div>
    <div class="col-md-4">


        <div class="card mb-3">
            <div class="card-header pb-0 p-3">
                <h6 class="text-black">Tahun Ajaran</h6>
            </div>

            <div class="card-body">
                <form class="form-block" action="{{ route('administration::scholar.classrooms.create') }}" method="GET">
                    <div class="form-group mb-0">
                        <div class="input-group w-100">
                            <x-select
                                name="academic"
                                :value="request('academic', $acsem->id)"
                                :options="$acsems->map(fn($_a) => [
                                    'value' => $_a->id,
                                    'label' => $_a->full_name
                                ])"
                            />

                            <div class="input-group-append">
                                <x-btn>Tetapkan</x-btn>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header pb-0 p-3">
                <h6>Lanjutan</h6>
            </div>
            <div class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action text-black" href="{{ route('administration::scholar.majors.index', ['academic' => request('academic')]) }}"><i class="mdi mdi-folder-settings-variant-outline"></i> Kelola jurusan</a>
                <a class="list-group-item list-group-item-action text-black" href="{{ route('administration::scholar.superiors.index', ['academic' => request('academic')]) }}"><i class="mdi mdi-file-settings-variant-outline"></i> Kelola unggulan</a>
            </div>
        </div>
    </div>
</div>
@endsection
