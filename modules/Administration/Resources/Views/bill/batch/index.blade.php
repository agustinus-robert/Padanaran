@extends('layouts.horizontal-layout')

@section('title', 'Tagihan - ')
@section('titleTemplate', config('account.admin.name'))
@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@section('navtitle', 'Gelombang')

@section('breadcrumb')
    <li class="breadcrumb-item">Tagihan</li>
    <li class="breadcrumb-item active">Referensi</li>
@endsection

@push('nav')
@include('administration::layouts.includes.navbar-administration')
@endpush

@php
$trashed = false;
$columns = [
    [
        'field' => 'name',
        'label' => 'Gelombang',
        'slot' => fn ($item) => e($item->name),
    ],
    [
        'field' => 'semester',
        'label' => 'Semester',
        'slot' => fn ($item) =>
            e($item->semesters->name).' - '.e($item->semesters->academic->name),
    ],
    // [
    //     'field' => 'actions',
    //     'label' => '',
    //     'slot' => fn ($item) => $item->trashed()
    //         ? view('components.partial-actions', [
    //             'item' => $item,
    //             'routes' => [
    //                 'restore' => 'administration::bill.batchs.restore',
    //                 'kill'    => 'administration::bill.batchs.kill',
    //             ],
    //         ])->render()
    //         : view('components.partial-actions', [
    //             'item' => $item,
    //             'routes' => [
    //                 'edit'    => 'administration::bill.batchs.index',
    //                 'destroy' => 'administration::bill.batchs.destroy',
    //             ],
    //             'editParams' => ['edit' => $item->id],
    //         ])->render(),
    // ],
    ['field' => 'actions', 'label' => 'Aksi', 'slot' => fn($item) => view('components.partial-actions',
        [
        'item' => $item,
        'routes' => [
            'index'   => 'administration::bill.batchs.index',
            'destroy' => 'administration::bill.batchs.destroy',
        ],
            'useModal' => false,
        ])->render()],
];
@endphp

@push('additional-content')
    @php
        $extraMenus = [
            [
                'label' => request('trash') ? 'Tampilkan Gelombang Aktif' : 'Tampilkan Gelombang Terhapus',
                'route' => route('administration::bill.batchs.index', ['trash' => request('trash', 0) ? 0 : 1]),
                'icon' => request('trash') ? 'visibility' : 'delete',
                'class' => request('trash') ? 'bg-light text-primary font-weight-bold' : 'text-danger'
            ]
        ];
    @endphp

    <x-sidebar-card title="Lanjutan" icon="settings" :items="$extraMenus" />
@endpush

@section('body-content')
    @include('components.navbar-admin')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <x-table
                    type="material"
                    :data="$billsBatch"
                    :columns="$columns"
                    :count="count($billsBatch)"
                    title="Gelombang Pendaftaran"
                    searchRoute="{{ route('administration::bill.batchs.index', ['academic' => request('academic')]) }}"
                    :trash="$trashed"
                />

            </div>

            <div class="col-md-4">
                <div class="card p-0 mb-4">
                    <div class="card-header">
                        <h6>Kelola Gelombang Pendaftaran</h6>
                    </div>
                    <div class="card-body">
                        <form class="form-block" action="{{ isset($editBillBatch) ? route('administration::bill.batchs.update', $editBillBatch->id) : route('administration::bill.batchs.store') }}" method="POST">
                            @csrf

                            @if(isset($editBillBatch))
                                @method('PUT')
                            @endif

                            <x-input-group :isRow="true">
                                <x-label value="Nama"></x-label>

                                <x-col size="12">
                                    <x-input value="{{ old('name', $editBillBatch->name ?? '') }}"  name="name" placeholder="Nama Kategori mapel ..." />
                                </x-col>
                            </x-input-group>

                            {{-- <div class="form-group mb-3">
                                <label>Nama</label>
                                <input type="text" class="form-control" name="name"
                                    value="{{ old('name', $editBillBatch->name ?? '') }}" required autocomplete="off">
                            </div> --}}

                            <x-input-group :isRow="true">
                                <x-label value="Semester"></x-label>

                                <x-col size="12">
                                    <x-select
                                        name="semester_id"
                                        placeholder="Pilih"
                                        :value="old('semester_id', $editBillBatch->semester_id ?? null)"
                                        :options="$academicSemester->map(fn($s) => [
                                            'value' => $s->id,
                                            'label' => $s->name.' - '.$s->academic->name
                                        ])"
                                        required
                                    />
                                </x-col>
                            </x-input-group>

                            <div class="form-group mb-0">
                                <x-btn variant="success">{{ isset($editBillBatch) ? 'Update' : 'Simpan' }}</x-btn>

                                @if(isset($editBillBatch))
                                    <a href="{{ route('administration::bill.batchs.index') }}" class="btn btn-secondary">Batal</a>
                                @endif
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
