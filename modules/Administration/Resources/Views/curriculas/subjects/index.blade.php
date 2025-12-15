@extends('layouts.horizontal-layout')

@section('title', 'Mapel - ')
@section('titleTemplate', config('account.admin.name'))
@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@section('breadcrumb')
	<li class="breadcrumb-item">Kurikulum</li>
	<li class="breadcrumb-item active">Mapel</li>
@endsection

@push('nav')
@include('administration::layouts.includes.navbar-administration')
@endpush

@php
$trashed = false;
$columns = [
    [
        'field' => 'kd',
        'label' => 'Kode',
        'slot' => fn($subject) => $subject->kd,
    ],
    [
        'field' => 'name',
        'label' => 'Nama Mapel',
        'slot' => fn($subject) =>
            '<strong>'.$subject->name.'</strong>',
    ],
    [
        'field' => 'level.kd',
        'label' => 'Jenjang',
        'slot' => fn($subject) =>
            $subject->level->kd ?? '-',
    ],
    [
        'field' => 'category.name',
        'label' => 'Kategori',
        'slot' => fn($subject) =>
            $subject->category->name ?? '-',
    ],
    [
        'field' => 'color',
        'label' => 'Warna',
        'slot' => fn($subject) => '
            <span class="d-inline-block rounded-circle"
                style="
                    width:20px;
                    height:20px;
                    background-color:'.$subject->color_id.';
                    border:1px solid #aaa;
                ">
            </span>',
    ],
    [
        'field' => 'actions',
        'label' => 'Aksi',
        'slot' => fn($subject) => view('components.partial-actions', [
            'item' => $subject,
            'routes' => [
                'edit'    => 'administration::curriculas.subjects.edit',
                'destroy' => 'administration::curriculas.subjects.destroy',
                'restore' => 'administration::curriculas.subjects.restore',
                'kill'    => 'administration::curriculas.subjects.kill',
            ],
        ])->render(),
    ],
];
@endphp


@section('body-content')
<div class="row container-fluid">
    @include('components.navbar-admin')
		<div class="col-md-8">
			<x-table
            type="material"
            :data="$subjects"
            :columns="$columns"
            title="Rombel"
            searchRoute="{{ route('administration::scholar.classrooms.index', ['academic' => request('academic')]) }}"
            :trash="$trashed"
        />
		</div>

		<div class="col-md-4">
			<div class="card mb-3">
                <div class="card-header pb-0 p-3">
                    <h6 class="text-black">Tahun Ajaran</h6>
                </div>

                <div class="card-body">
                    <form class="form-block" action="{{ route('administration::curriculas.subjects.index') }}" method="GET">
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
                    <h6 class="text-black">Jumlah Mapel</h6>
                </div>

				<div class="card-body">
					<div class="h1 text-muted text-right">
						<i class="mdi mdi-account-box-multiple-outline float-right"></i>
					</div>
					<div class="text-value">{{ $subjects_count }}</div>
					<small class="text-muted text-uppercase font-weight-bold">Total</small>
				</div>
			</div>
			<div class="card">
				<div class="card-header pb-0 p-3">
                    <h6 class="text-black">Lanjutan</h6>
                </div>
				<div class="list-group list-group-flush">
					<a class="list-group-item list-group-item-action text-black" href="{{ route('administration::curriculas.subjects.create', ['academic' => request('academic', $acsem->id)]) }}"><i class="mdi mdi-plus-circle-outline"></i> Tambah mapel</a>
					<a class="list-group-item list-group-item-action text-black" href="{{ route('administration::curriculas.subject-categories.index') }}"><i class="mdi mdi-book-outline"></i> Kelola kategori mapel</a>
					<a class="list-group-item list-group-item-action text-black" href="{{ route('administration::curriculas.subjects.index', ['trash' => request('trash', 0) ? null : 1]) }}"><i class="mdi mdi-delete-outline"></i> Tampilkan mapel yang {{ request('trash', 0) ? 'tidak' : '' }} dihapus</a>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
