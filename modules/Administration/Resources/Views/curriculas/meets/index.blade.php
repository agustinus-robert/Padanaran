@extends('layouts.horizontal-layout')

@section('title', 'Pertemuan - ')

@section('navtitle', 'Pertemuan')

@section('breadcrumb')
    <li class="breadcrumb-item">Kurikulum</li>
    <li class="breadcrumb-item active">Pertemuan</li>
@endsection

@push('nav')
@include('administration::layouts.includes.navbar-administration')
@endpush


@php
    $trashed = false;
    $columns = [
        [
            'label' => '',
            'slot' => fn($item) => '
                <div
                    style="width:26pt;height:26pt"
                    class="bg-'.($item->props->color ?? 'secondary').' rounded-circle d-table-cell text-center align-middle">
                    '.$item->teacher->user->name.'
                </div>
            ',
        ],

        [
            'label' => 'Rombel',
            'slot' => fn($item) => '
                <strong>'.$item->classroom->name.'</strong><br>
                '.($item->classroom->major->name ?? '-').' '.$item->classroom->superior->name.'
            ',
        ],

        [
            'label' => 'Mapel',
            'slot' => fn($item) => '
                <strong>'.$item->subject->name.'</strong><br>
                '.$item->plans_count.' pertemuan
            ',
        ],

        [
            'label' => 'Pengajar',
            'slot' => fn($item) => '
                <strong>'.$item->teacher->user->name.'</strong><br>
                NIP. '.$item->teacher->nip.'
            ',
        ],

        ['field' => 'actions', 'label' => 'Aksi', 'slot' => fn($item) => view('components.partial-actions',
        [
        'item' => $item,
        'routes' => [
            'edit' => 'administration::curriculas.meets.edit',
            'destroy' => 'administration::curriculas.meets.destroy',
        ],
            'useModal' => true,
        ])->render()],
    ];
@endphp

@php
    $extraMenus = [
        [
            'label' => 'Tambah Pertemuan',
            'route' => route('administration::curriculas.meets.create', ['academic' => request('academic', $acsem->id)]),
            'icon' => 'add_circle',
            'icon_class' => 'text-success'
        ],
        [
            'label' => 'Kelola Rombel',
            'route' => route('administration::scholar.classrooms.index', ['academic' => request('academic', $acsem->id)]),
            'icon' => 'groups',
            'icon_class' => 'text-primary'
        ],
        [
            'label' => 'Kelola Mapel',
            'route' => route('administration::curriculas.subjects.index', ['academic' => request('academic', $acsem->id)]),
            'icon' => 'menu_book',
            'icon_class' => 'text-info'
        ],
        [
            'label' => 'Data Guru',
            'route' => route('administration::employees.teachers.index'),
            'icon' => 'person_pin',
            'icon_class' => 'text-warning'
        ],
        ['divider' => true],
        [
            'label' => request('trash') ? 'Data Aktif' : 'Tampilkan Sampah',
            'route' => route('administration::curriculas.meets.index', ['trash' => request('trash') ? 0 : 1]),
            'icon' => request('trash') ? 'visibility' : 'delete',
            'class' => request('trash') ? 'bg-light text-primary font-weight-bold' : 'text-danger'
        ]
    ];
@endphp


@push('additional-content')
    <x-sidebar-card title="Tahun Ajaran" icon="calendar_month" type="form">
        <form action="{{ route('administration::curriculas.meets.index') }}" method="GET">
            <div class="d-flex gap-2">
                <div class="flex-grow-1">
                    <select name="academic" class="form-select form-select-sm border ps-2" style="font-size: 12px;">
                        @foreach($acsems as $_acsem)
                            <option value="{{ $_acsem->id }}" @selected(request('academic', $acsem->id) == $_acsem->id)>
                                {{ $_acsem->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-sm bg-gradient-dark mb-0 px-3">Set</button>
            </div>
        </form>
    </x-sidebar-card>

    <x-sidebar-card title="Lanjutan" icon="settings_input_component" :items="$extraMenus" />
@endpush

@section('body-content')
    @include('components.navbar-admin')
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <x-table
                    type="material"
                    :data="$meets"
                    :columns="$columns"
                    title="Pertemuan"
                    :createRoute="route('administration::curriculas.meets.create', ['academic' => request('academic', $acsem->id)])"                
                    searchRoute="{{ route('administration::curriculas.meets.index', ['academic' => request('academic')]) }}"
                    :trash="$trashed"
                    :count="$meets_count"
                    countLabel="Jumlah Pertemuan"
                />
            </div>

        </div>
    </div>
@endsection
