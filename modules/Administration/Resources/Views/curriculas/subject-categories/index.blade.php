@extends('layouts.horizontal-layout')

@section('title', 'Kategori mapel - ')

@section('navtitle', 'Kategori Mapel')

@section('breadcrumb')
    <li class="breadcrumb-item">Kurikulum</li>
    <li class="breadcrumb-item active">Kategori mapel</li>
@endsection

@push('nav')
@include('administration::layouts.includes.navbar-administration')
@endpush

@php
$trashed = false;
$columns = [
    [
        'label' => 'Nama kategori',
        'slot' => fn($item) => e($item->name),
    ],
    ['field' => 'actions', 'label' => 'Aksi', 'slot' => fn($item) => view('components.partial-actions',
    [
    'item' => $item,
    'routes' => [
        'edit' => 'administration::curriculas.subject-categories.edit',
        'destroy' => 'administration::curriculas.subject-categories.destroy',
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
            :data="$categories"
            :columns="$columns"
            title="Kategori Mata Pelajaran"
            searchRoute="{{ route('administration::curriculas.subject-categories.index', ['academic' => request('academic')]) }}"
            :trash="$trashed"
        />
        </div>

        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header pb-0 p-3">
                    <h6 class="text-black">Jumlah kategori mapel</h6>
                </div>

                <div class="card-body">
                    <div class="h1 text-muted text-right">
                        <i class="mdi mdi-account-box-multiple-outline float-right"></i>
                    </div>
                    <div class="text-value">{{ $categories_count }}</div>
                    <small class="text-muted text-uppercase font-weight-bold">Total</small>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h6 class="text-black">Lanjutan</h6>
                </div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action text-black" href="{{ route('administration::curriculas.subjects.index') }}"><i class="mdi mdi-book-outline"></i> Kelola mapel</a>
                </div>
            </div>
        </div>
    </div>

    <x-regular-modal id="editModal" title="Ubah kategori">
        <form id="modal-edit-form" method="POST"> @csrf @method('PUT')
            <x-input-group :isRow="true">
                <x-col size="4">
                    <x-label value="Nama kategori"></x-label>
                </x-col>

                <x-col size="8">
                    <x-input id="modal-edit-input-name" name="name" placeholder="Nama Kategori mapel ..." />
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
            const button = event.relatedTarget; // Tombol yang diklik
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const action = button.getAttribute('data-action')
            // Set nilai input di dalam modal
            const nameInput = editModal.querySelector('#modal-edit-input-name');
            nameInput.value = name;

            // (Opsional) Set action form jika butuh
            const form = editModal.querySelector('#modal-edit-form');
            form.action = action; // Ganti dengan route yang sesuai
        });
    </script>
@endpush
