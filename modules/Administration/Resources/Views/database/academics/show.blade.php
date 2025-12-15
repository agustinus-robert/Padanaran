@extends('layouts.horizontal-layout')

@section('title', 'Tahun ajaran - ')

@section('breadcrumb')
	<li class="breadcrumb-item">Akademik</li>
	<li class="breadcrumb-item"><a href="{{ route('administration::database.academics.index') }}">Tahun ajaran</a></li>
	<li class="breadcrumb-item active">Lihat detail</li>
@endsection

@push('nav')
@include('administration::layouts.includes.navbar-administration')
@endpush

@php
$trashed = false;
$columns = [
    ['field' => 'name', 'label' => 'Nama Semester'],
    ['field' => 'status', 'label' => 'Status', 'slot' => fn($item) => $item->open
        ? '<span class="badge badge-success badge-pill px-2">Aktif</span>'
        : '<span class="badge badge-danger badge-pill px-2">Nonaktif</span>'
    ],
    ['field' => 'classrooms_count', 'label' => 'Jumlah Rombel', 'slot' => fn($item) => $item->classrooms_count . ' rombel'],
    ['field' => 'actions', 'label' => 'Aksi', 'slot' => function($item) use ($academic) {
        if ($item->trashed()) {
            return view('components.partial-actions', [
                'item' => $item,
                'routes' => [
                    'restore' => route('administration::database.academics.semesters.restore', ['academic' => $academic->id, 'semester' => $item->id]),
                    'kill' => route('administration::database.academics.semesters.kill', ['academic' => $academic->id, 'semester' => $item->id]),
                ],
                'trashed' => true,
            ])->render();
        } else {
            return view('components.partial-actions', [
                'item' => $item,
                'routes' => [
                    'update' => route('administration::database.academics.semesters.update', ['academic' => $academic->id, 'semester' => $item->id]),
                    'destroy' => route('administration::database.academics.semesters.destroy', ['academic' => $academic->id, 'semester' => $item->id]),
                ],
                'trashed' => false,
                'extra' => fn($item) => view('components.toggle-status', [
                    'item' => $item,
                    'route' => route(
                        'administration::database.academics.semesters.update',
                        [
                            'academic' => optional($item->academic)->id ?? $item->academic_id,
                            'semester' => $item->id
                        ]
                    )
                ])->render(),



            ])->render();
        }
    }]
];
@endphp


@section('body-content')
	<div class="row container-fluid">
        @include('components.navbar-admin')
		<div class="col-md-8">

             <x-table
                type="material"
                :data="$semesters"
                :columns="$columns"
                title="Data semester"
                searchRoute="{{ route('administration::database.academics.semesters.index', ['academic' => request('academic')]) }}"
                :trash="$trashed"
            />

			<div class="card">
				<div class="card-header">
					<h6>Tambah semester</h6>
				</div>
				<div class="card-body">
					<form class="form-block form-confirm" action="{{ route('administration::database.academics.semesters.store', ['academic' => $academic->id]) }}" method="POST"> @csrf

                        <div class="form-group mb-2">
                             <x-input-group :isRow="true">
                                <x-label value="Nama semester" />

                                <x-col size="12">
                                    <x-input
                                        name="name"
                                        required
                                        autocomplete="off"
                                        :value="old('name', $academic->name)"
                                    />

                                    @error('name')
                                    <small class="text-danger"> {{ $message }} </small>
                                @enderror
                                </x-col>
                            </x-input-group>
						</div>
						<div class="form-group mb-2">
							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input" id="open" name="open" value="1" @if(old('open', 1) && !request('trash')) checked @endif @if(request('trash')) disabled @endif>
								<label class="custom-control-label" for="open"><span id="open-text">Aktifkan</span> semester ini</label>
							</div>
						</div>
						<button type="submit" class="btn btn-success"  @if(request('trash')) disabled @endif>Tambah semester</button>
						<a class="btn btn-secondary" href="{{ route('administration::database.academics.index') }}">Kembali</a>
					</form>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card mb-3">
				<div class="card-header pb-0 p-3">
                    <h6>Ubah Tahun Akademik</h6>
                </div>
				<div class="card-body">
					<form class="form-block" action="{{ route('administration::database.academics.update', ['academic' => $academic->id]) }}" method="POST"> @csrf @method('PUT')

                        <x-input-group :isRow="true">
                            <x-label value="Nama Tahun" />

                                <x-col size="12">
                                    <x-input
                                        name="name"
                                        required
                                        autocomplete="off"
                                        :value="old('name', $academic->name)"
                                    />

                                    @error('name')
                                    <small class="text-danger"> {{ $message }} </small>
                                @enderror
                                </x-col>
                            </x-input-group>

						<x-input-group :isRow="true">
                            <x-label value="Tahun" />

                            <x-col size="12">
                                <x-input
                                    name="year"
                                    required
                                    autocomplete="off"
                                    :value="old('year', $academic->year)"
                                />

                                @error('year')
                                    <small class="text-danger"> {{ $message }} </small>
                                @enderror
                            </x-col>
						</x-input-group>
						<x-input-group class="form-group mb-0">
                            <x-btn type="submit" variant="success">Simpan</x-btn>
						</x-input-group>
					</form>
				</div>
			</div>
			<div class="card">
				<div class="card-header pb-0 p-3">
                    <h6>Lanjutan</h6>
                </div>
				<div class="list-group list-group-flush">
					<a class="list-group-item list-group-item-action text-black" href="{{ route('administration::database.academics.show', ['academic' => $academic->id, 'trash' => request('trash', 0) ? null : 1]) }}"><i class="mdi mdi-delete-outline"></i> Tampilkan semester yang {{ request('trash', 0) ? 'tidak' : '' }} dihapus</a>
				</div>
			</div>
		</div>
	</div>
@endsection


@push('script')
	<script>
		$('#open').on('change', (e) => {
		    $('#open-text').text($(e.target).is(':checked') ? 'Aktifkan' : 'Nonaktifkan')
		});
	</script>
@endpush
