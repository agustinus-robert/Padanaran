@extends('layouts.horizontal-layout')

@section('title', 'Unggulan - ')
@section('titleTemplate', config('account.admin.name'))
@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@section('breadcrumb')
    <li class="breadcrumb-item">Kesiswaan</li>
    <li class="breadcrumb-item active">Unggulan</li>
@endsection

@push('nav')
@include('administration::layouts.includes.navbar-administration')
@endpush

@php
$columns = [
    [
        'field' => 'name',
        'label' => 'Unggulan',
        'slot' => fn($item) =>
            '<p class="'.($item->trashed() ? 'text-muted' : '').' mb-0">Unggulan '.$item->name.'</p>'.
            ($item->classrooms->take(8)->count()
                ? $item->classrooms->take(8)->map(fn($c) => '<span class="badge '.($item->trashed() ? 'badge-sm bg-gradient-secondary' : 'badge-sm bg-gradient-dark').'">'.$c->name.'</span>')->implode(' ')
                : '<span class="text-muted font-italic">Tidak rombel di unggulan ini</span>'
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
                'edit' => 'administration::scholar.superiors.update',
                'destroy' => 'administration::scholar.superiors.destroy',
                'restore' => 'administration::scholar.superiors.restore',
                'kill' => 'administration::scholar.superiors.kill',
            ],
            'useModal' => true,
        ])->render()
    ],
];
@endphp

@section('body-content')
<div class="row container-fluid mb-2">
    @include('components.navbar-admin')

    <div class="col-md-8">
        <x-table
            type="material"
            :data="$superiors"
            :columns="$columns"
            title="Unggulan"
            searchRoute="{{ route('administration::scholar.superiors.index', ['academic' => request('academic')]) }}"
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
                <form action="{{ route('administration::scholar.superiors.index') }}" method="GET">
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
                        <x-col size="3">
                            <x-btn type="submit" variant="dark">Terapkan</x-btn>
                        </x-col>
                    </x-input-group>
                </form>
            </div>
        </div>

        {{-- Tambah Unggulan --}}
        <div class="card mb-3">
            <div class="card-header pb-0 p-3">
                <h6>Tambah Unggulan</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('administration::scholar.superiors.store', ['semester_id' => $acsem->id]) }}" method="POST">
                    @csrf
                    <x-input-group :isRow="true" required>
                        <x-col size="9">
                            <x-input
                                name="name"
                                placeholder="Tambah unggulan tahun ajaran {{ $acsem->full_name }}"
                                :value="request('name')"
                            />
                        </x-col>
                        <x-col size="3">
                            <x-btn type="submit" variant="dark">Simpan</x-btn>
                        </x-col>
                    </x-input-group>
                </form>
            </div>
        </div>

        {{-- Jumlah Unggulan --}}
        <div class="card mb-3">
            <div class="card-header pb-0 p-3">
                <h6 class="text-black">Jumlah Unggulan</h6>
            </div>
            <div class="card-body text-right">
                <div class="h1 text-muted mb-2"><i class="mdi mdi-account-box-multiple-outline"></i></div>
                <div class="text-value">{{ $superiors_count }}</div>
                <small class="text-muted text-uppercase font-weight-bold">Total</small>
            </div>
        </div>

        {{-- Lanjutan --}}
        <div class="card">
            <div class="card-header pb-0 p-3">
                <h6 class="text-black">Lanjutan</h6>
            </div>
            <div class="list-group list-group-flush">
                <a class="list-group-item text-black" href="{{ route('administration::scholar.classrooms.index', ['academic'=>$acsem->id]) }}">
                    <i class="mdi mdi-account-group-outline"></i> Kelola rombel
                </a>
                <a class="list-group-item text-black" href="{{ route('administration::scholar.majors.index', ['academic'=>$acsem->id]) }}">
                    <i class="mdi mdi-file-settings-variant-outline"></i> Kelola jurusan
                </a>
                <a class="list-group-item text-black" href="{{ route('administration::scholar.superiors.index', ['trash'=>request('trash',0)?null:1]) }}">
                    <i class="mdi mdi-delete-outline"></i> Tampilkan unggulan yang {{ request('trash',0)?'tidak':'' }} dihapus
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit --}}
<x-regular-modal id="editModal" title="Ubah Unggulan">
    <form id="modal-edit-form" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" name="id" id="modal-edit-input-id">

        <x-input-group :isRow="true">
            <x-col size="4"><x-label value="Tahun Ajaran" /></x-col>
            <x-col size="8"><span class="form-control-plaintext">{{ $acsem->full_name }}</span></x-col>
        </x-input-group>

        <x-input-group :isRow="true">
            <x-col size="4"><x-label value="Nama Unggulan" /></x-col>
            <x-col size="8"><x-input id="modal-edit-input-name" name="name" placeholder="Nama unggulan ..." /></x-col>
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

    editModal.querySelector('#modal-edit-input-id').value = id;
    editModal.querySelector('#modal-edit-input-name').value = name;
    editModal.querySelector('#modal-edit-form').action = action;
});
</script>
@endpush
