@extends('layouts.horizontal-layout')

@section('title', 'Rombel - ')
@section('titleTemplate', config('account.admin.name'))
@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@section('navtitle', 'Kelas')

@section('breadcrumb')
<li class="breadcrumb-item">Kesiswaan</li>
<li class="breadcrumb-item active">Rombel</li>
@endsection

@push('nav')
@include('administration::layouts.includes.navbar-administration')
@endpush

@php
$columns = [
    ['field' => 'level.kd', 'label' => 'Jenjang'],
    ['field' => 'name', 'label' => 'Nama Rombel', 'slot' => fn($item) => $item->name . '<br><small class="text-muted">'.($item->supervisor?->full_name ?? 'Tidak ada wali kelas').'</small>'],
    ['field' => 'major_superior', 'label' => 'Ruang', 'slot' => fn($item) => $item->major->name.' '.$item->superior->name],
    ['field' => 'room.name', 'label' => 'Ruang'],
    ['field' => 'stsems_count', 'label' => 'Jumlah Siswa', 'slot' => fn($item) => $item->stsems_count.' siswa'],
    ['field' => 'actions', 'label' => 'Aksi', 'slot' => fn($item) => view('components.partial-actions',
    [
    'item' => $item,
    'routes' => [
        'show' => 'administration::scholar.classrooms.show',
        'edit' => 'administration::scholar.classrooms.edit',
        'destroy' => 'administration::scholar.classrooms.destroy',
        'restore' => 'administration::scholar.classrooms.restore',
        'kill' => 'administration::scholar.classrooms.kill',
    ]
    ])->render()],
];
@endphp

@php
    $extraMenus = [
        [
            'label' => 'Kelola Jurusan',
            'route' => route('administration::scholar.majors.index', ['academic' => $acsem->id]),
            'icon' => 'category',
            'icon_class' => 'text-info'
        ],
        [
            'label' => 'Kelola Unggulan',
            'route' => route('administration::scholar.superiors.index', ['academic' => $acsem->id]),
            'icon' => 'star_outline',
            'icon_class' => 'text-warning'
        ],
        ['divider' => true],
        [
            'label' => request('trash') ? 'Data Aktif' : 'Tampilkan Sampah',
            'route' => route('administration::scholar.classrooms.index', ['trash' => request('trash') ? 0 : 1]),
            'icon' => request('trash') ? 'visibility' : 'delete',
            'class' => request('trash') ? 'bg-light text-primary font-weight-bold' : 'text-danger'
        ]
    ];
@endphp

@push('additional-content')
   <x-sidebar-card title="Tahun Ajaran" icon="calendar_month" type="form">
        <form action="{{ route('administration::scholar.classrooms.index') }}" method="GET">
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
                :data="$classrooms"
                :columns="$columns"
                title="Rombel"
                :createRoute="route('administration::scholar.classrooms.create', ['academic' => $acsem->id])"                
                searchRoute="{{ route('administration::scholar.classrooms.index', ['academic' => request('academic')]) }}"
                :trash="$trashed"
                :count="$classrooms_count"
                countLabel="Jumlah Rombel"
            />
        </div>
    </div>
</div>
@endsection
