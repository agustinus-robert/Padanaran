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

@push('additional-content')
    <x-sidebar-card title="Tahun Ajaran" icon="calendar_month" type="form">
        <form action="{{ route('administration::scholar.classrooms.create') }}" method="GET">
            <div class="d-flex gap-2">
                <div class="flex-grow-1">
                    <select name="academic" class="form-select form-select-sm border ps-2" style="font-size: 12px;">
                        @foreach($acsems as $_a)
                            <option value="{{ $_a->id }}" @selected(request('academic', $acsem->id) == $_a->id)>
                                {{ $_a->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-sm bg-gradient-dark mb-0 px-3">Terapkan</button>
            </div>
        </form>
    </x-sidebar-card>

    @php
        $extraMenus = [
            [
                'label' => 'Kelola Jurusan',
                'route' => route('administration::scholar.majors.index', ['academic' => request('academic')]),
                'icon' => 'folder_managed',
                'icon_class' => 'text-info'
            ],
            [
                'label' => 'Kelola Unggulan',
                'route' => route('administration::scholar.superiors.index', ['academic' => request('academic')]),
                'icon' => 'settings_suggest',
                'icon_class' => 'text-warning'
            ],
        ];
    @endphp

    <x-sidebar-card title="Lanjutan" icon="settings_input_component" :items="$extraMenus" />
@endpush

@section('body-content')

@include('components.navbar-admin')

<div class="container-fluid">

    <div class="row justify-content-center">
        <div class="col-md-10">
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
                                :options="$acsem->majors->map(fn($major) => [
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
                                :options="$acsem->superiors->map(fn($s) => [
                                    'value' => $s->id,
                                    'label' => $s->name
                                ])"
                            />
                        </x-input-group>

                        <x-input-group label="Wali kelas">
                            <x-select-2
                                name="supervisor_id"
                                :value="old('supervisor_id') ?? $classroom->supervisor_id ?? $classroom->supervisor_id ?? null"                                
                                placeholder="-- Pilih wali kelas --"
                                :options="$supervisors->map(fn($spv) => [
                                    'value' => (string) $spv->id, 
                                    'label' => $spv->user->name
                                ])"
                            />
                        </x-input-group>

                        <x-input-group>
                            <x-col size="12" offset="3">
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
    </div>

    {{-- ==== SIDEBAR KANAN TETAP ==== --}}

</div>
@endsection
