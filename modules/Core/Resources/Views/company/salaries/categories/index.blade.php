@extends('layouts.horizontal-layout')

@section('title', 'Kategori gaji | ')
@section('navtitle', 'Kategori gaji')
@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@push('nav')
    @include('core::layouts.includes.navbar-core')
@endpush

@php
    $trashed = false;

    $columns = [
        [
            'label' => 'Nama',
            'slot'  => fn($category) => "<strong>{$category->name}</strong>",
        ],
        [
            'label' => 'Index',
            'slot'  => fn($category) => "<span class='text-muted'>#{$category->az}</span>",
        ],
        [
            'label' => 'Kategori',
            'slot'  => fn($category) => $category->slip->name,
        ],
        [
            'label' => 'Aksi',
            'slot'  => function($category) {
                if($category->trashed()) {
                    return view('components.partial-actions', [
                        'item' => $category,
                        'routes' => [
                            'restore' => 'core::company.salaries.categories.restore',
                        ],
                        'trashed' => true,
                        'useModal' => false,
                    ])->render();
                }

                return view('components.partial-actions', [
                    'item' => $category,
                    'routes' => [
                        'edit' => 'core::company.salaries.categories.show',
                        'destroy' => 'core::company.salaries.categories.destroy',
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
                'label' => request('trash') ? 'Lihat kategori aktif' : 'Lihat kategori dihapus',
                'route' => route('core::company.salaries.categories.index', ['trash' => !request('trash')]),
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
                    :data="$categories"
                    :columns="$columns"
                    title="Daftar Kategori Gaji"
                    searchRoute="{{ route('core::company.salaries.categories.index', ['search' => request('search')]) }}"
                    :trash="$trashed"
                    :count="$categories->count()"
                    countLabel="Jumlah Kategori Gaji"
                />
            </div>
            <div class="col-md-4">
                @can('store', Modules\Core\Models\CompanySalarySlipCategory::class)
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6>Tambah kategori gaji baru</h6>
                        </div>

                        <div class="card-body border-top">
                            <form class="form-block" action="{{ route('core::company.salaries.categories.store', ['next' => url()->full()]) }}" method="post"> @csrf
                                {{-- Index urutan --}}
                                <x-input-group :isRow="true" required>
                                    <x-label value="Index urutan" />
                                    <x-col size="12">
                                        <div class="input-group">
                                            <x-input
                                                type="number"
                                                name="az"
                                                :value="old('az')"
                                                required
                                                @class(['is-invalid' => $errors->has('az')])
                                            />
                                            <span class="p-2 border text-center" style="width:35px;">#</span>
                                        </div>
                                    </x-col>
                                </x-input-group>

                                {{-- Pilih slip --}}
                                <x-input-group :isRow="true" required>
                                    <x-label value="Pilih slip" />
                                    <x-col size="12">
                                        <x-select
                                            name="slip_id"
                                            :value="old('slip_id')"
                                            required
                                            :options="$slips->map(fn($slip) => ['value' => $slip->id, 'label' => $slip->name])"
                                            placeholder="-- Pilih --"
                                            @class(['is-invalid' => $errors->has('slip_id')])
                                        />
                                    </x-col>
                                </x-input-group>

                                {{-- Nama kategori --}}
                                <x-input-group :isRow="true" required>
                                    <x-label value="Nama kategori" />
                                    <x-col size="12">
                                        <x-input
                                            type="text"
                                            name="name"
                                            :value="old('name')"
                                            required
                                            @class(['is-invalid' => $errors->has('name')])
                                        />
                                    </x-col>
                                </x-input-group>

                                {{-- Button submit --}}
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
