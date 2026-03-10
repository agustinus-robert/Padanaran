@extends('layouts.horizontal-layout')

@section('title', 'Mapel - ')
@section('titleTemplate', config('account.admin.name'))
@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@section('navtitle', 'Mapel')

@section('breadcrumb')
	<li class="breadcrumb-item">Kurikulum</li>
	<li class="breadcrumb-item active">Mapel</li>
@endsection

@push('nav')
@include('administration::layouts.includes.navbar-administration')
@endpush

@php
$trashed = false;
$columns = [
    [
        'field' => 'kd',
        'label' => 'Kode',
        'slot' => fn($subject) => $subject->kd,
    ],
    [
        'field' => 'name',
        'label' => 'Nama Mapel',
        'slot' => fn($subject) =>
            '<strong>'.$subject->name.'</strong>',
    ],
    [
        'field' => 'level.kd',
        'label' => 'Jenjang',
        'slot' => fn($subject) =>
            $subject->level->kd ?? '-',
    ],
    [
        'field' => 'category.name',
        'label' => 'Kategori',
        'slot' => fn($subject) =>
            $subject->category->name ?? '-',
    ],
    [
        'field' => 'color',
        'label' => 'Warna',
        'slot' => fn($subject) => '
            <span class="d-inline-block rounded-circle"
                style="
                    width:20px;
                    height:20px;
                    background-color:'.$subject->color_id.';
                    border:1px solid #aaa;
                ">
            </span>',
    ],
    [
        'field' => 'actions',
        'label' => 'Aksi',
        'slot' => fn($subject) => view('components.partial-actions', [
            'item' => $subject,
            'routes' => [
                'edit'    => 'administration::curriculas.subjects.edit',
                'destroy' => 'administration::curriculas.subjects.destroy',
                'restore' => 'administration::curriculas.subjects.restore',
                'kill'    => 'administration::curriculas.subjects.kill',
            ],
        ])->render(),
    ],
];
@endphp

@php
    $extraMenus = [
        [
            'label' => 'Kelola Kategori',
            'route' => route('administration::curriculas.subject-categories.index'),
            'icon' => 'category',
            'icon_class' => 'text-info'
        ],
        ['divider' => true],
        [
            'label' => request('trash') ? 'Data Aktif' : 'Tampilkan Sampah',
            'route' => route('administration::curriculas.subjects.index', ['trash' => request('trash') ? 0 : 1]),
            'icon' => request('trash') ? 'visibility' : 'delete',
            'class' => request('trash') ? 'bg-light text-primary font-weight-bold' : 'text-danger'
        ]
    ];
@endphp

@push('additional-content')
    <x-sidebar-card title="Tahun Ajaran" icon="calendar_month" type="form">
        <form action="{{ route('administration::curriculas.subjects.index') }}" method="GET">
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
            :data="$subjects"
            :columns="$columns"
            title="Mata Pelajaran"
            :createRoute="route('administration::curriculas.subjects.create', ['academic' => request('academic', $acsem->id)])"
            searchRoute="{{ route('administration::scholar.classrooms.index', ['academic' => request('academic')]) }}"
            :trash="$trashed"
            :count="$subjects_count"
            countLabel="Jumlah Mapel"
        />
		</div>
	</div>
</div>
@endsection
