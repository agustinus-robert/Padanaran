@extends('layouts.horizontal-layout')

@section('title', 'Data siswa - ')
@section('titleTemplate', config('account.admin.name'))
@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@section('breadcrumb')
    <li class="breadcrumb-item">Kesiswaan</li>
    <li class="breadcrumb-item active">Data siswa</li>
@endsection

@push('nav')
@include('administration::layouts.includes.navbar-administration')
@endpush

@php
$columns = [
    ['field' => 'nis', 'label' => 'NIS'],
    ['field' => 'avatar', 'label' => '', 'slot' => fn($item) => '<img class="rounded-circle" src="'.$item->user->profile->avatar_path.'" height="32" alt="">'],
    ['field' => 'name', 'label' => 'Nama lengkap', 'slot' => fn($item) =>
        ($item->trashed() || $item->user->is(auth()->user()))
        ? $item->user->profile->name
        : '<a href="'.route('administration::scholar.students.show', ['student' => $item->id]).'">'.$item->user->profile->name.'</a>'
    ],
    ['field' => 'sex', 'label' => 'JK', 'slot' => fn($item) => $item->user->profile->sex_name ?: '-'],
    ['field' => 'generation', 'label' => 'Angkatan', 'slot' => fn($item) => $item->generation->year ?: '-'],
    ['field' => 'actions', 'label' => '', 'slot' => fn($item) => view('components.partial-actions', [
        'item' => $item,
        'routes' => [
            // 'show' => 'administration::scholar.students.show',
            'edit' => 'administration::scholar.students.show',
            'destroy' => 'administration::scholar.students.destroy',
            'restore' => 'administration::scholar.students.restore',
            'kill' => 'administration::scholar.students.kill',
        ],
        'hideIfSelf' => true,
        'useModal' => false,
    ])->render()],
];
@endphp

@php
    $extraMenus = [
        [
            'label' => request('trash') ? 'Data Aktif' : 'Tampilkan Sampah',
            'route' => route('administration::scholar.students.index', ['trash' => request('trash') ? 0 : 1]),
            'icon' => request('trash') ? 'visibility' : 'delete',
            'class' => request('trash') ? 'bg-light text-primary font-weight-bold' : 'text-danger'
        ]
    ];
@endphp

@push('additional-content')
    <x-sidebar-card title="Impor Data" icon="upload_file" type="form">
        <form class="form-block form-confirm" action="{{ route('administration::scholar.students.import') }}" method="POST" enctype="multipart/form-data"> 
            @csrf
            <p class="text-xs mb-2">Template: <a href="{{ route('administration::scholar.students.export') }}" target="download" class="text-info font-weight-bold">Download</a></p>
            <div class="d-flex gap-2 flex-column">
                <input type="file" name="file" class="form-control form-control-sm border ps-2" required>
                <button type="submit" class="btn btn-sm bg-gradient-dark mb-0">Impor Data</button>
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
                    :data="$students"
                    :columns="$columns"
                    title="Siswa"
                    :createRoute="route('administration::scholar.students.create')"                
                    searchRoute="{{ route('administration::scholar.students.index', ['academic' => request('academic')]) }}"
                    :trash="request('trash')"
                    :count="$students_count"
                    countLabel="Jumlah Siswa"
                />
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(() => {
            function readURL(input) {
                if (input.files && input.files[0]) {
                    $('[for="file"]').html(input.files[0].name)
                }
            }

            $("#file").change(function(e) {
                readURL(this);
            });
        })
    </script>
@endpush
