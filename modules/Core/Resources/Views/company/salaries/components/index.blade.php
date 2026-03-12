@extends('layouts.horizontal-layout')

@section('title', 'Komponen gaji | ')
@section('navtitle', 'Komponen gaji')

@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@push('nav')
    @include('core::layouts.includes.navbar-core')
@endpush

@php
    $trashed = null;
    $columns = [
        [
            'label' => 'Kategori slip',
            'slot'  => fn($component) => "<div>{$component->slip->name}</div><div class='small text-muted'>{$component->category->name}</div>",
            'nowrap' => true,
        ],
        [
            'label' => 'Nama komponen',
            'slot'  => fn($component) => "<strong>{$component->name}</strong>",
        ],
        [
            'label' => 'Operasi',
            'slot'  => fn($component) => $component->operate->badge(),
            'class' => 'text-center',
        ],
        [
            'label' => 'Satuan',
            'slot'  => fn($component) => implode(' - ', array_filter([$component->unit->prefix(), $component->unit->suffix()])),
            'class' => 'text-muted text-center',
        ],
        [
            'label' => 'Aksi',
            'slot'  => function($component) {
                if($component->trashed()) {
                    return view('components.partial-actions', [
                        'item' => $component,
                        'routes' => [
                            'restore' => 'core::company.salaries.components.restore',
                        ],
                        'trashed' => true,
                        'useModal' => false,
                    ])->render();
                }

                return view('components.partial-actions', [
                    'item' => $component,
                    'routes' => [
                        'edit' => 'core::company.salaries.components.show',
                        'destroy' => 'core::company.salaries.components.destroy',
                    ],
                    'trashed' => false,
                    'useModal' => false,
                ])->render();
            },
            'class' => 'text-end',
            'nowrap' => true,
        ],
    ];
@endphp

@php
    $extraMenus = [
        [
            'label' => request('trash') ? 'Lihat komponen aktif' : 'Lihat komponen dihapus',
            'route' => route('core::company.salaries.components.index', ['trash' => !request('trash')]),
            'icon' => request('trash') ? 'visibility' : 'delete',
            'class' => request('trash') ? 'text-primary font-weight-bold' : 'text-danger'
        ]
    ];
@endphp

@push('additional-content')
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
                    :data="$components"
                    :columns="$columns"
                    title="Daftar Komponen Gaji"
                    searchRoute="{{ route('core::company.salaries.components.index', ['search' => request('search')]) }}"
                    :trash="$trashed"
                    :count="$components_count"
                    countLabel="Jumlah komponen Gaji"
                />
            </div>

            <div class="col-md-4">
                @can('store', Modules\Core\Models\CompanySalarySlipComponent::class)
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6>Tambah komponen gaji baru</h6>
                        </div>
                        <div class="card-body border-top">
                            <form class="form-block" action="{{ route('core::company.salaries.components.store', ['next' => url()->full()]) }}" method="post"> @csrf
                            @php
                                    // Slips tetap array atau collection
                                    $slips = collect($slips);

                                    $categoryOptions = $slips->map(fn($slip) => [
                                        'label' => $slip->name,
                                        'children' => collect($slip->categories ?? [])->map(fn($cat) => [
                                            'value' => $cat->id,
                                            'label' => $cat->name,
                                        ])->toArray(),
                                    ])->toArray();

                                    // Units enum/object
                                    $unitOptions = collect($units)->map(fn($unit) => [
                                        'value' => $unit->value,   // akses property enum
                                        'label' => $unit->label() . ' (' . implode(' ', array_filter([$unit->prefix(), $unit->suffix()])) . ')'
                                    ])->toArray();

                                    // Operates enum/object
                                    $operateOptions = collect($operates)->map(fn($op) => [
                                        'value' => $op->value,
                                        'label' => $op->label()
                                    ])->toArray();
                                @endphp



                                {{-- Kategori --}}
                                <x-input-group :isRow="true" required>
                                    <x-label value="Kategori" />
                                    <x-col size="12">
                                        <x-select
                                            name="ctg_id"
                                            :options="$categoryOptions"
                                            placeholder="-- Pilih --"
                                            required
                                            @class(['is-invalid' => $errors->has('ctg_id')])
                                        />
                                    </x-col>
                                </x-input-group>

                                {{-- Satuan --}}
                                <x-input-group :isRow="true" required>
                                    <x-label value="Satuan" />
                                    <x-col size="12">
                                        <x-select
                                            name="unit"
                                            :options="$unitOptions"
                                            placeholder="-- Pilih --"
                                            required
                                            @class(['is-invalid' => $errors->has('unit')])
                                        />
                                    </x-col>
                                </x-input-group>

                                {{-- Jenis operasi --}}
                                <x-input-group :isRow="true">
                                    <x-label value="Jenis operasi" />
                                    <x-col size="12">
                                        <x-select
                                            name="operate"
                                            :options="$operateOptions"
                                            placeholder="-- Pilih --"
                                            @class(['is-invalid' => $errors->has('operate')])
                                        />
                                    </x-col>
                                </x-input-group>

                                {{-- Nama komponen --}}
                                <x-input-group :isRow="true" required>
                                    <x-label value="Nama komponen" />
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


                                <div class="mt-4">
                                    <x-btn class="mt-3" type="submit" variant="success">
                                            <span class="material-symbols-rounded">check</span> Simpan
                                    </x-btn>
                                </div>
                            </form>
                        </div>
                    </div>
                @endcan
            </div>
        </div>
    </div>
@endsection
