@extends('layouts.horizontal-layout')

@section('title', 'Pertemuan - ')

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

@section('body-content')
    @include('components.navbar-admin')
    <div class="row container-fluid">
        <div class="col-md-8">
            <x-table
                type="material"
                :data="$meets"
                :columns="$columns"
                title="Pertemuan"
                searchRoute="{{ route('administration::curriculas.meets.index', ['academic' => request('academic')]) }}"
                :trash="$trashed"
            />
        </div>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header pb-0 p-3">
                    <h6 class="text-black">Tahun Ajaran</h6>
                </div>

                <div class="card-body">
                    <form class="form-block" action="{{ route('administration::curriculas.meets.index') }}" method="GET">
                        <x-input-group :isRow="true" required>
                            <x-col size="9">
                                <x-select
                                    name="academic"
                                    class="form-control"
                                    :value="request('academic', $acsem->id)"
                                    :options="$acsems->map(fn($_acsem) => [
                                        'value' => $_acsem->id,
                                        'label' => $_acsem->full_name,
                                    ])"
                                />
                            </x-col>

                            <x-col size="2">
                                <button class="btn bg-gradient-dark">Tetapkan</button>
                            </x-col>
                        </x-input-group>
                    </form>
                </div>
            </div>
            <div class="card mb-3">
                 <div class="card-header pb-0 p-3">
                    <h6 class="text-black">Jumlah pertemuan</h6>
                </div>

                <div class="card-body">
                    <div class="h1 text-muted text-right">
                        <i class="mdi mdi-account-box-multiple-outline float-right"></i>
                    </div>
                    <div class="text-value">{{ $meets_count }}</div>
                    <small class="text-muted text-uppercase font-weight-bold">Total</small>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h6 class="text-black">Lanjutan</h6>
                </div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action text-black" href="{{ route('administration::curriculas.meets.create', ['academic' => request('academic', $acsem->id)]) }}"><i class="mdi mdi-plus-circle-outline"></i> Tambah pertemuan</a>
                    <a class="list-group-item list-group-item-action text-black" href="{{ route('administration::scholar.classrooms.index', ['academic' => request('academic', $acsem->id)]) }}"><i class="mdi mdi-account-group-outline"></i> Kelola rombel</a>
                    <a class="list-group-item list-group-item-action text-black" href="{{ route('administration::curriculas.subjects.index', ['academic' => request('academic', $acsem->id)]) }}"><i class="mdi mdi-book-outline"></i> Kelola mapel</a>
                    <a class="list-group-item list-group-item-action text-black" href="{{ route('administration::employees.teachers.index') }}"><i class="mdi mdi-account-circle-outline"></i> Data guru</a>
                    <a class="list-group-item list-group-item-action text-black" href="{{ route('administration::curriculas.meets.index', ['trash' => request('trash', 0) ? null : 1]) }}"><i class="mdi mdi-delete-outline"></i> Tampilkan pertemuan yang {{ request('trash', 0) ? 'tidak' : '' }} dihapus</a>
                </div>
            </div>
        </div>
    </div>
@endsection
