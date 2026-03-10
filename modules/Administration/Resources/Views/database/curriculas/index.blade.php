@extends('layouts.horizontal-layout')

@section('title', 'Kurikulum - ')
@section('titleTemplate', config('account.admin.name'))
@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@section('navtitle', 'Kurikulum')

@section('breadcrumb')
	<li class="breadcrumb-item">Akademik</li>
	<li class="breadcrumb-item active">Kurikulum</li>
@endsection

@push('nav')
@include('administration::layouts.includes.navbar-administration')
@endpush

@php
$trashed = false;
$columns = [
    ['field' => 'name', 'label' => 'Nama', 'slot' => fn($item) => $item->name],

    ['field' => 'year', 'label' => 'Tahun', 'slot' => fn($item) => $item->year],

    [
        'field' => 'semesters',
        'label' => 'Aktif',
        'slot' => fn($item) =>
            collect($item->semesters)
                ->map(fn($s) => '<span class="badge badge-pill badge-success">'.$s->name.'</span>')
                ->implode(' ')
    ],

    [
        'field' => 'actions',
        'label' => '',
        'slot' => fn($item) => view('components.partial-actions', [
            'item' => $item,
            'routes' => [
                // 'show' => 'administration::database.curriculas.show',
                'destroy' => 'administration::database.curriculas.destroy',
                'restore' => 'administration::database.curriculas.restore',
                'kill' => 'administration::database.curriculas.kill',
            ],
            'canDelete' => !$item->semesters_count
        ])->render()
    ],
];
@endphp


@push('additional-content')
    @php
        $extraMenus = [
            [
                'label' => request('trash') ? 'Tampilkan Kurikulum Aktif' : 'Tampilkan Kurikulum Terhapus',
                'route' => route('administration::database.curriculas.index', ['trash' => request('trash', 0) ? 0 : 1]),
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
                    :data="$curriculas"
                    :columns="$columns"
                    title="Kurikulum"
                    :count="$curriculas_count"
                    searchRoute="{{ route('administration::database.curriculas.index', ['academic' => request('academic')]) }}"
                    :trash="$trashed"
                />
            </div>

            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header pb-0 p-3">
                        <h6 class="text-black">Tambah tahun akademik</h6>
                    </div>

                    <div class="card-body">
                        <form class="form-block" action="{{ route('administration::database.curriculas.store') }}" method="POST"> @csrf
                            <x-input-group :isRow="true">
                                <x-label value="Nama Kurikulum" />

                                <x-col size="12">
                                    <x-input
                                        name="name"
                                        value="{{ old('name') }}"
                                        required
                                        autocomplete="off"
                                        :class="$errors->has('name') ? 'is-invalid' : ''"
                                    />
                                </x-col>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </x-input-group>

                            <div class="row">
                                <div class="col-sm-6 mb-2">
                                    <x-input-group :isRow="true" :isForm="true">
                                        <x-label value="Kode Kurikulum" />
                                        <x-input
                                        name="kd"
                                        value="{{ old('kd') }}"
                                        required
                                        autocomplete="off"
                                        :class="$errors->has('kd') ? 'is-invalid' : ''"
                                    />
                                        @error('kd')
                                            <small class="text-danger"> {{ $message }} </small>
                                        @enderror
                                    </x-input-group>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <x-input-group :isRow="true" :isForm="true">
                                        <x-label value="Tahun" />
                                        <x-input
                                        type="number"
                                        name="year"
                                        value="{{ old('year') }}"
                                        required
                                        autocomplete="off"
                                        :class="$errors->has('year') ? 'is-invalid' : ''"
                                    />

                                        {{-- <input type="number" class="form-control @error('year') is-invalid @enderror" name="year" value="{{ old('year') }}" required autocomplete="off"> --}}
                                        @error('year')
                                            <small class="text-danger"> {{ $message }} </small>
                                        @enderror
                                    </x-input-group>
                                </div>
                            </div>


                            <x-input-group class="mb-0">
                                <x-btn type="submit" class="mt-2" variant="success">
                                    Simpan
                                </x-btn>
                            </x-input-group>
                        </form>
                    </div>
                </div>
            </div>
        </div>
	</div>
@endsection
