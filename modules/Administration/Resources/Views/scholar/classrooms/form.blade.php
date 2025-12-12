@extends('layouts.horizontal-layout')

@section('title', ($classroom ?? false) ? 'Edit Rombel - ' : 'Tambah Rombel - ')
@section('titleTemplate', config('account.admin.name'))
@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')


@section('breadcrumb')
    <li class="breadcrumb-item">Kesiswaan</li>
    <li class="breadcrumb-item">
        <a href="{{ request('next', route('administration::scholar.classrooms.index')) }}">Rombel</a>
    </li>
    <li class="breadcrumb-item active">
        {{ isset($classroom) ? 'Edit' : 'Tambah' }}
    </li>
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
                {{ isset($classroom) ? 'Edit Rombel' : 'Tambah Rombel' }}
            </x-card-header>

            <div class="card-body">

                <form class="form-block"
                      action="
                        {{ isset($classroom)
                            ? route('administration::scholar.classrooms.update', $classroom->id)
                            : route('administration::scholar.classrooms.store', [
                                'semester_id' => $acsem->id,
                                'next' => request('next', route('administration::scholar.classrooms.index', [
                                    'academic' => request('academic', $acsem->id)
                                ]))
                            ])
                        }}"
                      method="POST">

                    @csrf
                    @if(isset($classroom))
                        @method('PUT')
                    @endif

                    <x-input-group>
                        <x-label for="semester_id" value="Tahun ajaran" col="3" />

                        <x-col size="5">
                            <strong>
                                <span class="form-control-plaintext">{{ $acsem->full_name }}</span>
                            </strong>
                        </x-col>
                    </x-input-group>

                    <x-input-group label="Jenjang kelas" required>
                        <x-select-2
                            name="level_id"
                            :value="old('level_id', $classroom->level_id ?? null)"
                            :options="getGrade()->levels->map(fn($level) => [
                                'value' => $level->id,
                                'label' => $level->kd . ' - ' . $level->name
                            ])"
                        />
                    </x-input-group>

                    <x-input-group label="Nama rombel" required>
                        <x-input
                            name="name"
                            placeholder="Nama rombel"
                            :value="old('name', $classroom->name ?? null)"
                        />
                    </x-input-group>

                    <x-input-group label="Ruangan">
                        <x-select-2
                            name="room_id"
                            :value="old('room_id', $classroom->room_id ?? null)"
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
                            :value="old('major_id', $classroom->major_id ?? null)"
                            placeholder="-- Pilih jurusan --"
                            :options="$acsem->majors->where('grade_id', userGrades())->map(fn($major) => [
                                'value' => $major->id,
                                'label' => $major->name
                            ])"
                        />
                    </x-input-group>

                    <x-input-group label="Unggulan">
                        <x-select-2
                            name="superior_id"
                            :value="old('superior_id', $classroom->superior_id ?? null)"
                            placeholder="-- Pilih unggulan --"
                            :options="$acsem->superiors->where('grade_id', userGrades())->map(fn($s) => [
                                'value' => $s->id,
                                'label' => $s->name
                            ])"
                        />
                    </x-input-group>

                    <x-input-group label="Wali kelas">
                        <x-select-2
                            name="supervisor_id"
                            :value="old('supervisor_id', $classroom->supervisor_id ?? null)"
                            placeholder="-- Pilih wali kelas --"
                            :options="$supervisors->map(fn($spv) => [
                                'value' => $spv->id,
                                'label' => $spv->user->name
                            ])"
                        />
                    </x-input-group>

                    <x-input-group>
                        <x-col size="8" offset="3">
                            <x-btn type="submit" variant="success">
                                {{ isset($classroom) ? 'Update' : 'Simpan' }}
                            </x-btn>

                            <a class="btn btn-secondary"
                               href="{{ request('next', route('administration::scholar.classrooms.index', [
                                   'academic' => $acsem->id
                               ])) }}">
                                Kembali
                            </a>
                        </x-col>
                    </x-input-group>

                </form>

            </div>
        </div>
    </div>

    {{-- ==== SIDEBAR KANAN TETAP ==== --}}
    <div class="col-md-4">

        <div class="card mb-3">
            <div class="card-header pb-0 p-3">
                <h6 class="text-black">Tahun Ajaran</h6>
            </div>

            <div class="card-body">
                <form class="form-block" action="{{ route('administration::scholar.classrooms.create') }}" method="GET">
                    <x-input-group :isRow="true" required>
                        <x-col size="9">
                            <x-select
                                name="academic"
                                :value="request('academic', $acsem->id)"
                                :options="$acsems->map(fn($_a) => [
                                    'value' => $_a->id,
                                    'label' => $_a->full_name
                                ])"
                            />
                        </x-col>

                        <x-col size="2">
                            <x-btn type="submit" variant="dark">Terapkan</x-btn>
                        </x-col>
                    </x-input-group>
                </form>
            </div>
        </div>


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
