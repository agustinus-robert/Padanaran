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
            '<h5 class="'.(request('trash') ? 'text-muted' : '').' mb-0">Jurusan '.$item->name.'</h5>'.

            // list rombel (badge)
            ($item->classrooms->take(8)->count()
                ? $item->classrooms->take(8)->map(function($c) {
                    return '<span class="badge '.(request('trash') ? 'badge-secondary' : 'badge-dark').'">'.$c->name.'</span>';
                })->implode(' ')
                : '<span class="text-muted font-italic">Tidak rombel di jurusan ini</span>'
            ).

            // jumlah + lainnya
            ($item->classrooms->count() > 8
                ? '<span class="badge badge-secondary">+'.($item->classrooms->count() - 8).' lainnya</span>'
                : '')
    ],

    [
        'field' => 'actions',
        'label' => 'Aksi',
        'slot' => fn($item) => $item->trashed()

            // MODE TRASH (restore + kill)
            ? '
                <form class="d-inline form-confirm"
                    action="'.route('administration::scholar.majors.restore', $item->id).'"
                    method="POST">
                    '.csrf_field().method_field('PUT').'
                    <button class="btn btn-primary btn-sm" title="Pulihkan">
                        <i class="mdi mdi-restore"></i>
                    </button>
                </form>

                <form class="d-inline form-confirm"
                    action="'.route('administration::scholar.majors.kill', $item->id).'"
                    method="POST">
                    '.csrf_field().method_field('DELETE').'
                    <button class="btn btn-danger btn-sm" title="Hapus Permanen">
                        <i class="mdi mdi-delete-forever-outline"></i>
                    </button>
                </form>
            '

            // MODE NORMAL (edit + delete)
            : '
                <button type="button"
                    class="btn btn-warning btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#exampleModal"
                    data-id="'.$item->id.'"
                    data-name="'.$item->name.'"
                    data-action="'.route('administration::scholar.majors.update', $item->id).'">
                    <i class="mdi mdi-pencil"></i>
                </button>

                <form class="d-inline form-confirm"
                    action="'.route('administration::scholar.majors.destroy', $item->id).'"
                    method="POST">
                    '.csrf_field().method_field('DELETE').'
                    <button class="btn btn-danger btn-sm" title="Buang">
                        <i class="mdi mdi-delete-outline"></i>
                    </button>
                </form>
            '
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
                :trash="$trashed"
            />
        </div>
        <div class="col-md-4">
            <div class="card card-body">
                <form class="form-block" action="{{ route('administration::scholar.majors.index') }}" method="GET">
                    <div class="form-group mb-0">
                        <label>Tahun ajaran</label>
                        <div class="input-group w-100">
                            <select name="academic" class="form-control">
                                @foreach ($acsems as $_acsem)
                                    <option value="{{ $_acsem->id }}" @if (request('academic', $acsem->id) == $_acsem->id) selected @endif>{{ $_acsem->full_name }}</option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-primary">Tetapkan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="h1 text-muted mb-4 text-right">
                        <i class="mdi mdi-account-box-multiple-outline float-right"></i>
                    </div>
                    <div class="text-value">{{ $majors_count }}</div>
                    <small class="text-muted text-uppercase font-weight-bold">Jumlah jurusan</small>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <i class="mdi mdi-cogs float-left mr-2"></i>Lanjutan
                </div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('administration::scholar.classrooms.index', ['academic' => request('academic')]) }}"><i class="mdi mdi-account-group-outline"></i> Kelola rombel</a>
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('administration::scholar.superiors.index', ['academic' => request('academic')]) }}"><i class="mdi mdi-file-settings-variant-outline"></i> Kelola unggulan</a>
                    <a class="list-group-item list-group-item-action text-danger" href="{{ route('administration::scholar.majors.index', ['trash' => request('trash', 0) ? null : 1]) }}"><i class="mdi mdi-delete-outline"></i> Tampilkan jurusan yang {{ request('trash', 0) ? 'tidak' : '' }} dihapus</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form class="modal-content form-block" id="modal-edit-form" method="POST"> @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Ubah jurusan</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-2">
                        <label>Tahun ajaran</label>
                        <strong><span class="form-control-plaintext">{{ $acsem->full_name }}</span></strong>
                    </div>
                    <div class="form-group mb-3">
                        <label>Nama jurusan</label>
                        <input id="modal-edit-input-name" class="form-control" type="text" name="name" value="" placeholder="Nama jurusan ...">
                    </div>
                    <div class="form-group mb-0">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        const exampleModal = document.getElementById('exampleModal');

        exampleModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const action = button.getAttribute('data-action')

            const nameInput = exampleModal.querySelector('#modal-edit-input-name');
            nameInput.value = name;
            const form = exampleModal.querySelector('#modal-edit-form');
            form.action = action;
        });
    </script>
@endpush
