@extends('layouts.horizontal-layout')

@section('title', 'Registrasi semester - ')
@section('titleTemplate', config('account.admin.name'))
@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@section('navtitle', 'Semester')

@section('breadcrumb')
    <li class="breadcrumb-item">Kesiswaan</li>
    <li class="breadcrumb-item active">Registrasi semester</li>
@endsection

@push('nav')
@include('administration::layouts.includes.navbar-administration')
@endpush

@php
    $columns = [
    [
        'field' => 'nis',
        'label' => 'NIS',
        'slot' => fn($item) => $item->student->nis
    ],
    [
        'field' => 'name',
        'label' => 'Nama lengkap',
        'slot' => fn($item) => '<a href="'.route('administration::scholar.students.show', $item->student_id).'">'.$item->student->user->profile->name.'</a>'
    ],
    [
        'field' => 'classroom',
        'label' => 'Rombel',
        'slot' => fn($item) => $item->classroom->name ?? '-',
        'class' => 'text-center'
    ],
    ['field' => 'actions', 'label' => 'Aksi', 'slot' => fn($item) => view('components.partial-actions',
    [
    'item' => $item,
    'routes' => [
        'show' => 'administration::scholar.students.show',
        // 'edit' => 'administration::scholar.students.edit',
        // 'destroy' => 'administration::scholar.students.destroy',
        // 'restore' => 'administration::scholar.students.restore',
        // 'kill' => 'administration::scholar.students.kill',
    ]
    ])->render()],
];

@endphp

@php
    $semesterMenus = [
        [
            'label' => 'Kenaikan Kelas',
            'route' => route('administration::scholar.semesters.promotions', ['acsem' => request('acsem', $acsem->id ?? null)]),
            'icon' => 'unfold_less_double',
            'icon_class' => 'text-primary'
        ],
        [
            'label' => 'Data Induk Siswa',
            'route' => route('administration::scholar.students.index'),
            'icon' => 'badge',
            'icon_class' => 'text-info'
        ],
        ['divider' => true],
        [
            'label' => request('trash') ? 'Data Aktif' : 'Tampilkan Sampah',
            'route' => route('administration::scholar.students.index', ['trash' => request('trash') ? 0 : 1]),
            'icon' => request('trash') ? 'visibility' : 'delete',
            'class' => request('trash') ? 'bg-light text-primary font-weight-bold' : 'text-danger'
        ]
    ];
@endphp

@push('additional-content')
    <x-sidebar-card title="Impor Semester" icon="upload_file" type="form">
        <form class="form-block form-confirm" action="{{ route('administration::scholar.semesters.import') }}" method="POST" enctype="multipart/form-data"> 
            @csrf
            <p class="text-xs mb-2">Template: <a href="{{ route('administration::scholar.semesters.export') }}" class="text-info font-weight-bold">Download di sini</a></p>
            <div class="d-flex gap-2 flex-column">
                <input type="file" name="file" class="form-control form-control-sm border ps-2" required>
                <button type="submit" class="btn btn-sm bg-gradient-dark mb-0">Impor Data</button>
            </div>
        </form>
    </x-sidebar-card>

    <x-sidebar-card title="Menu Lanjutan" icon="settings_input_component" :items="$semesterMenus" />
@endpush


@section('body-content')

@include('components.navbar-admin')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <x-table
                type="material"
                :data="$stsems"
                :columns="$columns"
                :createRoute="route('administration::scholar.semesters.registrations')"
                searchRoute="{{ route('administration::scholar.semesters.index', ['academic' => request('academic')]) }}"
                title="Register Semester"
                :trash="false"
                :count="$stsems_count"
                countLabel="Jumlah Siswa Aktif"
            />
        </div>
    </div>
</div>
@endsection

@push('script')
    <script>
        $(() => {
            $('[name="acsem"]').on('change', (e) => {
                $('#filter-form').submit();
            })

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
