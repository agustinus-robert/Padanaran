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


@section('body-content')
    @include('components.navbar-admin')

    <div class="row container-fluid">
        <div class="col-md-8">
            <x-table
                :isSearch="true"
                type="material"
                :data="$contracts"
                :columns="$columns"
                title="Daftar Perjanjian Kerja"
                searchRoute="{{ route('hrms::employment.contracts.index', ['search' => request('search')]) }}"
                :trash="$trashed"
            />
        </div>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h6>Jumlah perjanjian kerja aktif</h6>
                </div>

                <div class="card-body">
                    <div class="display-5">{{ $contracts_count }}</div>
                    <div class="small fw-bold text-secondary text-uppercase">Total</div>
                </div>
                <div><i class="mdi mdi-account-group-outline mdi-48px text-light"></i></div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h6>Menu lainnya</h6>
                </div>

                <div class="card-body">
                    <div class="list-group list-group-flush border-top border-light">
                        @can('store', Modules\HRMS\Models\EmployeeContract::class)
                            <a class="list-group-item list-group-item-action" href="{{ route('hrms::employment.contracts.create', ['next' => url()->full()]) }}"><i class="mdi mdi-plus"></i> Tambah perjanjian kerja baru</a>
                        @endcan
                        <a class="list-group-item list-group-item-action text-danger" href="{{ route('hrms::employment.contracts.index', ['trash' => !request('trash')]) }}"><i class="mdi mdi-trash-can-outline"></i> Lihat perjanjian kerja yang {{ request('trash') ? 'tidak' : '' }} dihapus</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
