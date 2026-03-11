@extends('layouts.horizontal-layout')

@section('title', 'Data perjanjian kerja | ')
@section('navtitle', 'Data perjanjian kerja')

@push('nav')
    @include('hrms::layouts.includes.navbar-hrms')
@endpush

@php
$trashed = false;
$columns = [
    [
        'label' => 'Nomor perjanjian kerja',
        'slot' => fn ($contract) => '
            <i class="mdi mdi-circle '.($contract->is_active ? 'text-success' : 'text-danger').'" style="font-size:11pt;"></i>
            &nbsp; <strong>'.$contract->kd.'</strong><br>
            <small class="text-muted">'.($contract->contract->name ?? '-').'</small>
        ',
    ],

    [
        'label' => 'Karyawan',
        'slot' => fn ($contract) => '
            <div>'.$contract->employee->user->name.'</div>'.
            ($contract->employee->trashed() ? '<small class="text-danger">Karyawan ini sudah dihapus</small>' : '')
        ,
    ],

    [
        'label' => 'Berakhir pada',
        'slot' => fn ($contract) => $contract->end_at?->isoFormat('LLL') ?: '&infin;',
    ],

    [
        'label' => 'Dokumen',
        'slot' => fn ($contract) => '
            <a class="btn btn-link rounded-pill '.($contract->document ? '' : 'disabled').' p-0" '.
            (isset($contract->document) ? 'href="'.$contract->document->url().'" download="'.$contract->document->label.'"' : '').'>
                <small><i class="mdi mdi-file-download-outline"></i> Unduh</small>
            </a>
        ',
        'class' => 'text-center',
    ],

    [
        'label' => 'Aksi',
        'slot' => fn ($contract) => view('components.partial-actions', [
            'item' => $contract,
            'routes' => [
                'show' => 'hrms::employment.contracts.show',
                'edit' => 'hrms::employment.contracts.edit',
                'destroy' => 'hrms::employment.contracts.destroy',
                'restore' => 'hrms::employment.contracts.restore',
            ],
            'trashed' => $contract->trashed(),
            'useModal' => false,
        ])->render(),
        'class' => 'text-end',
    ],
];
@endphp

@php
    $isTrash = request('trash');
    $contractMenus = [
        [
            'label' => 'Lihat perjanjian kerja yang ' . ($isTrash ? 'tidak ' : '') . 'dihapus',
            'route' => route('hrms::employment.contracts.index', ['trash' => !$isTrash]),
            'icon' => $isTrash ? 'visibility' : 'delete_outline',
            'class' => 'text-danger'
        ],
    ];
@endphp


@push('additional-content')
    <x-sidebar-card 
        title="Menu lainnya" 
        icon="more_vert" 
        :items="$contractMenus" 
    />
@endpush


@section('body-content')
    @include('components.navbar-admin')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <x-table
                    :isSearch="true"
                    type="material"
                    :data="$contracts"
                    :columns="$columns"
                    :createCan="['store', Modules\HRMS\Models\EmployeeContract::class]"
                    :createRoute="route('hrms::employment.contracts.create', ['next' => url()->full()])"                
                    title="Daftar Perjanjian Kerja"
                    searchRoute="{{ route('hrms::employment.contracts.index', ['search' => request('search')]) }}"
                    :trash="$trashed"
                    :count="$contracts_count"
                    countLabel="Data Perjanjian Kerja"
                />
            </div>
        </div>
    </div>
@endsection
