@extends('layouts.horizontal-layout')

@section('title', 'Rombel - ')
@section('titleTemplate', config('account.admin.name'))
@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@section('breadcrumb')
<li class="breadcrumb-item">Kesiswaan</li>
<li class="breadcrumb-item active">Rombel</li>
@endsection

@push('nav')
@include('administration::layouts.includes.navbar-administration')
@endpush

@php
$columns = [
    ['field' => 'level.kd', 'label' => 'Jenjang'],
    ['field' => 'name', 'label' => 'Nama Rombel', 'slot' => fn($item) => $item->name . '<br><small class="text-muted">'.($item->supervisor?->full_name ?? 'Tidak ada wali kelas').'</small>'],
    ['field' => 'major_superior', 'label' => 'Ruang', 'slot' => fn($item) => $item->major->name.' '.$item->superior->name],
    ['field' => 'room.name', 'label' => 'Ruang'],
    ['field' => 'stsems_count', 'label' => 'Jumlah Siswa', 'slot' => fn($item) => $item->stsems_count.' siswa'],
    ['field' => 'actions', 'label' => 'Aksi', 'slot' => fn($item) => view('components.partial-actions',
    [
    'item' => $item,
    'routes' => [
        'show' => 'administration::scholar.classrooms.show',
        'edit' => 'administration::scholar.classrooms.edit',
        'destroy' => 'administration::scholar.classrooms.destroy',
        'restore' => 'administration::scholar.classrooms.restore',
        'kill' => 'administration::scholar.classrooms.kill',
    ]
    ])->render()],
];
@endphp

@section('body-content')
<div class="row container-fluid">
    @include('components.navbar-admin')

    <div class="col-md-8">
        <x-table
            type="material"
            :data="$classrooms"
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
                <form action="{{ route('administration::scholar.classrooms.index') }}" method="GET">
                    <div class="form-group mb-0">
                        <div class="input-group">
                            <select name="academic" class="form-control">
                                @foreach($acsems as $_acsem)
                                    <option value="{{ $_acsem->id }}" @selected(request('academic',$acsem->id)==$_acsem->id)>
                                        {{ $_acsem->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <button class="btn bg-gradient-dark">Tetapkan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header pb-0 p-3">
                <h6 class="text-black">Jumlah Rombel</h6>
            </div>
            <div class="card-body">
                <div class="h1 text-muted text-right">
                    <i class="mdi mdi-account-box-multiple-outline float-right"></i>
                </div>
                <div class="text-value">{{ $classrooms_count }}</div>
                <small class="text-muted text-uppercase font-weight-bold">Total</small>
            </div>
        </div>

        <div class="card">
            <div class="card-header pb-0 p-3">
                <h6 class="text-black">Lanjutan</h6>
            </div>
            <div class="list-group list-group-flush">
                <a class="list-group-item text-black" href="{{ route('administration::scholar.classrooms.create', ['academic'=>$acsem->id]) }}">
                    <i class="mdi mdi-plus-circle-outline"></i> Tambah rombel
                </a>
                <a class="list-group-item text-black" href="{{ route('administration::scholar.majors.index', ['academic'=>$acsem->id]) }}">
                    <i class="mdi mdi-folder-settings-variant-outline"></i> Kelola jurusan
                </a>
                <a class="list-group-item text-black" href="{{ route('administration::scholar.superiors.index', ['academic'=>$acsem->id]) }}">
                    <i class="mdi mdi-file-settings-variant-outline"></i> Kelola unggulan
                </a>
                <a class="list-group-item text-black" href="{{ route('administration::scholar.classrooms.index', ['trash'=>request('trash',0)?null:1]) }}">
                    <i class="mdi mdi-delete-outline"></i> Tampilkan rombel yang {{ request('trash',0)?'tidak':'' }} dihapus
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
