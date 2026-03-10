@extends('layouts.horizontal-layout')

@section('title', 'Tahun ajaran - ')
@section('titleTemplate', config('account.admin.name'))
@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@section('navtitle', 'Akademik')

@section('breadcrumb')
	<li class="breadcrumb-item">Akademik</li>
	<li class="breadcrumb-item active">Tahun ajaran</li>
@endsection

@push('nav')
@include('administration::layouts.includes.navbar-administration')
@endpush

@php
$trashed = false;
$columns = [
    ['field' => 'name', 'label' => 'Nama', 'slot' => fn($item) => $item->name],
    ['field' => 'year', 'label' => 'Tahun', 'slot' => fn($item) => $item->year],
    ['field' => 'semesters', 'label' => 'Aktif', 'slot' => fn($item) => collect($item->semesters)->map(fn($s) => '<span class="badge badge-pill badge-success">'.$s->name.'</span>')->implode(' ')],
    ['field' => 'actions', 'label' => '', 'slot' => fn($item) => view('components.partial-actions', [
        'item' => $item,
        'routes' => [
            'show' => 'administration::database.academics.show',
            'destroy' => 'administration::database.academics.destroy',
            'restore' => 'administration::database.academics.restore',
            'kill' => 'administration::database.academics.kill',
        ],
        'canDelete' => !$item->semesters_count
    ])->render()],
];
@endphp

@push('additional-content')
    @php
        $extraMenus = [
            [
                'label' => request('trash') ? 'Tampilkan Data Aktif' : 'Tampilkan Data Terhapus',
                'route' => route('administration::database.academics.index', ['trash' => request('trash', 0) ? 0 : 1]),
                'icon' => request('trash') ? 'visibility' : 'delete',
                'class' => request('trash') ? 'bg-light text-primary font-weight-bold' : 'text-danger'
            ]
        ];
    @endphp

    <x-sidebar-card title="Lanjutan" icon="settings" :items="$extraMenus" />
@endpush

@section('body-content')
    @include('components.navbar-admin')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <x-table
                    type="material"
                    :data="$academics"
                    :columns="$columns"
                    title="Tahun Ajaran"
                    :count="$academics_count"
                    searchRoute="{{ route('administration::database.academics.index', ['academic' => request('academic')]) }}"
                    :trash="$trashed"
                />

            </div>

            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="text-black">Tambah tahun akademik</h6>
                    </div>
                    <div class="card-body">
                        <form class="form-block" action="{{ route('administration::database.academics.store') }}" method="POST"> @csrf
                            <x-input-group :isRow="true">
                                <x-label value="Tahun Akademik" />

                                <x-col size="12">
                                    <x-input
                                        name="name"
                                        value="{{ old('name') }}"
                                        required
                                        autocomplete="off"
                                        :class="$errors->has('name') ? 'is-invalid' : ''"
                                    />
                                </x-col>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </x-input-group>

                            <x-input-group :isRow="true">
                                <x-label value="Tahun" />

                                <x-col size="12">
                                    <x-input
                                        name="year"
                                        value="{{ old('year') }}"
                                        required
                                        autocomplete="off"
                                        :class="$errors->has('year') ? 'is-invalid' : ''"
                                    />
                                </x-col>
                                @error('year')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </x-input-group>

                            <x-input-group class="mb-0">
                                <x-btn type="submit" class="mt-2" variant="success">
                                    Simpan
                                </x-btn>
                            </x-input-group>



                        </form>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="text-black">Sinkronisasi Data Semester</h6>
                    </div>
                    <div class="card-body">
                        <form id="formSync">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold">Data Semester Dari</label>
                                <select class="form-select p-2 border border-secondary-subtle select-2" id="old_semester_id">
                                    <option value="">Pilih Semester Asal</option>
                                    {{-- @foreach($academic_smt_data as $val)  {{ $val->id }} {{$val->name}} {{$val->academic->name}} --}}

                                        <option value="77">Semester sekarang</option>
                                    {{-- @endforeach --}}
                                </select>
                            </div>

                            <div class="mb-5">
                                <label class="form-label fw-bold">Data Semester Ke</label>
                                <select class="form-select p-2 border border-secondary-subtle select-2" id="new_semester_id">
                                    <option value="">Pilih Semester Tujuan</option>
                                    @foreach($academic_smt_data as $val)
                                        <option value="{{ $val->id }}">{{$val->name}} {{$val->academic->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <x-btn type="button" variant="dark" onclick="doSync()">
                                    <span class="material-symbols-rounded mr-3">sync_alt</span> Sync
                                </x-btn>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
	</div>
@endsection

<script>
    function doSync() {
    const oldSmt = $('#old_semester_id').val();
    const newSmt = $('#new_semester_id').val();

    if (!oldSmt || !newSmt) {
        notyf.error("Pilih kedua semester terlebih dahulu!");
        return;
    }

    Swal.fire({
        title: 'Konfirmasi',
        text: "Jalankan sinkronisasi data?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Jalankan!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('administration::database.academics.sync-smt') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    old_semester_id: oldSmt,
                    target_semester_id: newSmt
                },
                beforeSend: function(xhr) {
                    if (window.Echo && 
                        window.Echo.connector && 
                        typeof window.Echo.socketId === "function") {
                        
                        try {
                            const sId = window.Echo.socketId();
                            if (sId) {
                                xhr.setRequestHeader('X-Socket-ID', sId);
                            }
                        } catch (e) {
                            console.warn("Socket ID belum siap, lanjut tanpa header.");
                        }
                    }
                },
                success: function(response) {
                    notyf.success(response.message);
                },
                error: function(xhr) {
                    notyf.error("Gagal koneksi ke server.");
                }
            });
        }
    });
}
</script>
