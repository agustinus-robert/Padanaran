@extends('layouts.horizontal-layout')

@section('title', 'Slip gaji | ')
@section('navtitle', 'Slip gaji')
@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@push('nav')
    @include('core::layouts.includes.navbar-core')
@endpush

@php
    $trashed = false;
    $columns = [
    [
        'label' => 'Nama slip',
        'slot'  => fn($slip) => $slip->name,
    ],
    [
        'label' => 'Aksi',
        'slot'  => function($slip) {
            if($slip->trashed()) {
                return view('components.partial-actions', [
                    'item' => $slip,
                    'routes' => [
                        'restore' => 'core::company.salaries.slips.restore',
                    ],
                    'trashed' => true,
                    'useModal' => false,
                ])->render();
            }

            return view('components.partial-actions', [
                'item' => $slip,
                'routes' => [
                    'edit' => 'core::company.salaries.slips.show',
                    'destroy' => 'core::company.salaries.slips.destroy',
                ],
                'trashed' => false,
                'useModal' => false,
            ])->render();
        },
        'class' => 'text-end',
    ],
];

@endphp

@push('additional-content')
    @php
        $extraMenus = [
            [
                'label' => request('trash') ? 'Lihat slip gaji aktif' : 'Lihat slip gaji dihapus',
                'route' => route('core::company.salaries.slips.index', ['trash' => !request('trash')]),
                'icon' => request('trash') ? 'visibility' : 'delete',
                'class' => request('trash') ? 'text-primary font-weight-bold' : 'text-danger'
            ]
        ];
    @endphp

    <x-sidebar-card 
        title="Menu Lainnya" 
        icon="settings" 
        :items="$extraMenus" 
    />
@endpush

@section('body-content')
    @include('components.navbar-admin')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <x-table
                    :isSearch="true"
                    type="material"
                    :data="$slips"
                    :columns="$columns"
                    title="Daftar Slip Gaji"
                    searchRoute="{{ route('core::company.salaries.slips.index', ['search' => request('search')]) }}"
                    :trash="$trashed"
                    :count="$slips_count"
                    countLabel="Jumlah Selip Gaji"
                />
            </div>
            <div class="col-md-4">
                @can('store', Modules\Core\Models\CompanySalary::class)
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6>Tambah slip baru</h6>
                        </div>
                        <div class="card-body border-top">
                            <form class="form-block" action="{{ route('core::company.salaries.slips.store', ['next' => url()->full()]) }}" method="post"> @csrf
                                {{-- Index urutan --}}
                            <x-input-group :isRow="true">
                                    <x-label value="Index urutan"></x-label>

                                    <x-col size="12">
                                        <div class="input-group">
                                            <x-input
                                                type="number"
                                                name="az"
                                                value="{{ old('az', '') }}"
                                                placeholder="Index urutan"
                                                required
                                                @class(['is-invalid' => $errors->has('az')])
                                            />
                                            <span class="p-2 border text-center" style="width:35px;">#</span>
                                        </div>

                                    </x-col>
                                </x-input-group>

                                <x-input-group :isRow="true">
                                    <x-label value="Nama slip"></x-label>

                                    <x-col size="12">
                                        <x-input
                                            type="text"
                                            name="name"
                                            value="{{ old('name', '') }}"
                                            placeholder="Nama slip ..."
                                            required
                                            @class(['is-invalid' => $errors->has('name')])
                                        />

                                    </x-col>
                                </x-input-group>

                                <div class="mt-4">
                                    <x-btn variant="success"><span class="material-symbols-rounded">check</span> Simpan</x-btn>
                                </div>
                            </form>
                        </div>
                    </div>
                @endcan
            </div>
        </div>
    </div>
@endsection
