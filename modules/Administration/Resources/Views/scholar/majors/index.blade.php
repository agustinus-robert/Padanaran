@extends('layouts.horizontal-layout')

@section('title', 'Jurusan - ')
@section('titleTemplate', config('account.admin.name'))
@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@section('breadcrumb')
    <li class="breadcrumb-item">Kesiswaan</li>
    <li class="breadcrumb-item active">Jurusan</li>
@endsection

@push('nav')
@include('administration::layouts.includes.navbar-administration')
@endpush

@php
$columns = [
    [
        'field' => 'name',
        'label' => 'Jurusan',
        'slot' => fn($item) =>
            '<p class="'.(request('trash') ? 'text-muted' : '').' mb-0">Jurusan '.$item->name.'</p>'.
            ($item->classrooms->take(8)->count()
                ? $item->classrooms->take(8)->map(fn($c) => '<span class="badge '.(request('trash') ? 'badge-sm bg-gradient-secondary' : 'badge-sm bg-gradient-dark').'">'.$c->name.'</span>')->implode(' ')
                : '<span class="text-muted font-italic">Tidak rombel di jurusan ini</span>'
            )
            .
            ($item->classrooms->count() > 8
                ? '<span class="badge badge-sm bg-gradient-secondary">+'.($item->classrooms->count() - 8).' lainnya</span>'
                : '')
    ],
    [
        'field' => 'actions',
        'label' => '',
        'slot' => fn($item) => view('components.partial-actions', [
            'item' => $item,
            'routes' => [
                'show' => 'administration::scholar.majors.show',
                'edit' => 'administration::scholar.majors.edit',
                'destroy' => 'administration::scholar.majors.destroy',
                'restore' => 'administration::scholar.majors.restore',
                'kill' => 'administration::scholar.majors.kill',
            ],
            'useModal' => true,
        ])->render()
    ],
];
@endphp

@section('body-content')
<div class="row container-fluid">
    @include('components.navbar-admin')

    <div class="col-md-8">
        <x-table
            type="material"
            :data="$majors"
            :columns="$columns"
            title="Jurusan"
            searchRoute="{{ route('administration::scholar.majors.index', ['academic' => request('academic')]) }}"
            :trash="request('trash')"
        />
    </div>

    <div class="col-md-4">
        {{-- Tahun Ajaran --}}
        <div class="card mb-3">
            <div class="card-header pb-0 p-3">
                <h6 class="text-black">Tahun Ajaran</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('administration::scholar.majors.index') }}" method="GET">
                    <x-input-group :isRow="true" required>
                        <x-col size="9">
                            <x-select
                                    name="academic"
                                    :value="request('academic', $acsem->id)"
                                    :options="$acsems->map(fn($_a) => [
                                        'value' => $_a->id,
                                        'label' => $_a->full_name
                                    ])"
                                />
                        </x-col>

                         <x-col size="2">
                             <x-btn type="submit" variant="dark">Terapkan</x-btn>
                        </x-col>
                    </x-input-group>
                </form>
            </div>
        </div>

       <div class="card mb-3">
            <div class="card-header pb-0 p-3">
                <h6>Tambah Jurusan</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('administration::scholar.majors.store', ['semester_id' => $acsem->id]) }}" method="POST">
                    @csrf

                    <x-input-group :isRow="true" required>
                        <x-col size="9">
                            <x-input
                                name="name"
                                placeholder="Tambah jurusan tahun ajaran {{ $acsem->full_name }}"
                                :value="request('name')"
                                class="form-control border-dark"
                            />
                        </x-col>

                        <x-col size="2">
                            <x-btn type="submit" variant="dark">Simpan</x-btn>
                        </x-col>
                    </x-input-group>
                </form>
            </div>
        </div>


        {{-- Jumlah Jurusan --}}
        <div class="card mb-3">
            <div class="card-header pb-0 p-3">
                <h6 class="text-black">Jumlah Jurusan</h6>
            </div>
            <div class="card-body">
                <div class="h1 text-muted text-right">
                    <i class="mdi mdi-account-box-multiple-outline float-right"></i>
                </div>
                <div class="text-value">{{ $majors_count }}</div>
                <small class="text-muted text-uppercase font-weight-bold">Total</small>
            </div>
        </div>

        {{-- Lanjutan --}}
        <div class="card">
            <div class="card-header pb-0 p-3">
                <h6 class="text-black">Lanjutan</h6>
            </div>
            <div class="list-group list-group-flush">
                <a class="list-group-item text-black" href="{{ route('administration::scholar.majors.index') }}">
                    <i class="mdi mdi-plus-circle-outline"></i> Tambah jurusan
                </a>
                <a class="list-group-item text-black" href="{{ route('administration::scholar.classrooms.index', ['academic'=>$acsem->id]) }}">
                    <i class="mdi mdi-folder-settings-variant-outline"></i> Kelola rombel
                </a>
                <a class="list-group-item text-black" href="{{ route('administration::scholar.superiors.index', ['academic'=>$acsem->id]) }}">
                    <i class="mdi mdi-file-settings-variant-outline"></i> Kelola unggulan
                </a>
                <a class="list-group-item text-black" href="{{ route('administration::scholar.majors.index', ['trash'=>request('trash',0)?null:1]) }}">
                    <i class="mdi mdi-delete-outline"></i> Tampilkan jurusan yang {{ request('trash',0)?'tidak':'' }} dihapus
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit --}}
<x-regular-modal id="editModal" title="Ubah jurusan">
    <form id="modal-edit-form" method="POST">
        @csrf
        @method('PUT')

        {{-- Hidden untuk ID jurusan --}}
        <input type="hidden" name="id" id="modal-edit-input-id">

        <x-input-group :isRow="true">
            <x-col size="4">
                <x-label value="Tahun Ajaran"></x-label>
            </x-col>

            <x-col size="8">
                <span class="form-control-plaintext">{{ $acsem->full_name }}</span>
            </x-col>
        </x-input-group>

        <x-input-group :isRow="true">
            <x-col size="4">
                <x-label value="Nama Jurusan"></x-label>
            </x-col>

            <x-col size="8">
                <x-input id="modal-edit-input-name" name="name" placeholder="Nama jurusan ..." />
            </x-col>
        </x-input-group>

        <div class="d-flex justify-content-end gap-2">
            <x-btn type="button" variant="secondary" data-bs-dismiss="modal">Tutup</x-btn>
            <x-btn type="submit" variant="success">Simpan</x-btn>
        </div>
    </form>
</x-regular-modal>

@endsection

@push('scripts')
<script>
    const editModal = document.getElementById('editModal');

    editModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        const action = button.getAttribute('data-action');

        const idInput = editModal.querySelector('#modal-edit-input-id');
        idInput.value = id;

        const nameInput = editModal.querySelector('#modal-edit-input-name');
        nameInput.value = name;

        const form = editModal.querySelector('#modal-edit-form');
        form.action = action;
    });
</script>
@endpush

