@extends('layouts.horizontal-layout')

@section('title', 'Data siswa - ')
@section('titleTemplate', config('account.admin.name'))
@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@section('breadcrumb')
    <li class="breadcrumb-item">Kesiswaan</li>
    <li class="breadcrumb-item active">Data siswa</li>
@endsection

@push('nav')
@include('administration::layouts.includes.navbar-administration')
@endpush

@php
$columns = [
    ['field' => 'nis', 'label' => 'NIS'],
    ['field' => 'avatar', 'label' => '', 'slot' => fn($item) => '<img class="rounded-circle" src="'.$item->user->profile->avatar_path.'" height="32" alt="">'],
    ['field' => 'name', 'label' => 'Nama lengkap', 'slot' => fn($item) =>
        ($item->trashed() || $item->user->is(auth()->user()))
        ? $item->user->profile->name
        : '<a href="'.route('administration::scholar.students.show', ['student' => $item->id]).'">'.$item->user->profile->name.'</a>'
    ],
    ['field' => 'sex', 'label' => 'JK', 'slot' => fn($item) => $item->user->profile->sex_name ?: '-'],
    ['field' => 'generation', 'label' => 'Angkatan', 'slot' => fn($item) => $item->generation->year ?: '-'],
    ['field' => 'actions', 'label' => '', 'slot' => fn($item) => view('components.partial-actions', [
        'item' => $item,
        'routes' => [
            // 'show' => 'administration::scholar.students.show',
            'edit' => 'administration::scholar.students.show',
            'destroy' => 'administration::scholar.students.destroy',
            'restore' => 'administration::scholar.students.restore',
            'kill' => 'administration::scholar.students.kill',
        ],
        'hideIfSelf' => true,
        'useModal' => false,
    ])->render()],
];
@endphp


@section('body-content')
    <div class="row container-fluid">
        @include('components.navbar-admin')

        <div class="col-md-8">
            <x-table
                type="material"
                :data="$students"
                :columns="$columns"
                title="Siswa"
                searchRoute="{{ route('administration::scholar.students.index', ['academic' => request('academic')]) }}"
                :trash="request('trash')"
            />
        </div>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header pb-0 p-3">
                    <h6 class="text-black">Jumlah Siswa</h6>
                </div>
                <div class="card-body">
                    <div class="h1 text-muted text-right">
                        <i class="mdi mdi-account-box-multiple-outline float-right"></i>
                    </div>
                    <div class="text-value">{{ $students_count }}</div>
                    <small class="text-muted text-uppercase font-weight-bold">Total</small>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header pb-0 p-3">
                    <h6 class="text-black">Impor data siswa</h6>
                </div>
                <div class="card-body">
                    <form class="form-block form-confirm" action="{{ route('administration::scholar.students.import') }}" method="POST" enctype="multipart/form-data"> @csrf
                        <p>Download template <a href="{{ route('administration::scholar.students.export') }}" target="download">di sini</a></p>
                        <x-input-group :isRow="true" required>
                            <x-col size="8">
                                <x-input-file
                                    name="file"
                                    :error="$errors->first('file')"
                                />
                            </x-col>

                            <x-col size="4">
                                <x-btn type="submit" variant="dark">Impor Data</x-btn>
                            </x-col>
                        </x-input-group>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header pb-0 p-3">
                    <h6 class="text-black">Lanjutan</h6>
                </div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action text-black" href="{{ route('administration::scholar.students.create') }}"><i class="mdi mdi-plus-circle-outline"></i> Tambah siswa</a>
                    <a class="list-group-item list-group-item-action text-black" href="{{ route('administration::scholar.students.index', ['trash' => request('trash', 0) ? null : 1]) }}"><i class="mdi mdi-delete-outline"></i> Tampilkan siswa yang {{ request('trash', 0) ? 'tidak' : '' }} dihapus</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(() => {
            function readURL(input) {
                if (input.files && input.files[0]) {
                    $('[for="file"]').html(input.files[0].name)
                }
            }

            $("#file").change(function(e) {
                readURL(this);
            });
        })
    </script>
@endpush
