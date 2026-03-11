@extends('layouts.horizontal-layout')

@section('title', 'Gedung - ')

@section('navtitle', 'Asrama')

@section('breadcrumb')
    <li class="breadcrumb-item">Gedung</li>
    <li class="breadcrumb-item active">Siswa</li>
@endsection

@push('nav')
@include('boarding::layouts.includes.navbar-boarding')
@endpush

@php
$trashed = false; 
$columns = [
    ['field' => 'no', 'label' => 'No', 'slot' => fn($item) => $loop->iteration],
    
    [
        'field' => 'student.user.profile.name', 
        'label' => 'Nama Siswa', 
        'slot' => fn($item) => $item->student->user->profile->name
    ],
    
    [
        'field' => 'ground.name', 
        'label' => 'Gedung', 
        'slot' => fn($item) => $item->ground->name
    ],
    
    [
        'field' => 'actions', 
        'label' => '', 
        'slot' => fn($item) => view('components.partial-actions', [
            'item' => $item,
            'routes' => [
                'edit'    => 'boarding::facility.student.edit',
                'destroy' => 'boarding::facility.student.destroy',
                'restore' => 'boarding::facility.buildings.restore',
                'kill'    => 'boarding::facility.buildings.kill',
            ],
            // Jika component partial-actions mendukung parameter custom untuk route key
            'params' => [
                'edit' => ['student' => $item->id],
                'destroy' => ['student' => $item->id],
                'restore' => ['building' => $item->id],
                'kill' => ['building' => $item->id],
            ]
        ])->render()
    ]
];
@endphp

@push('additional-content')
    <div class="card mb-3">
        <div class="card-header pb-0 p-3">
            <h6 class="mb-0 d-flex align-items-center">
                <i class="material-symbols-rounded me-2">settings</i> Lanjutan
            </h6>
        </div>
        <div class="card-body p-3 pt-0">
            <div class="list-group list-group-flush">
                <a href="{{ route('boarding::facility.student.index', ['trash' => request('trash', 0) ? null : 1]) }}" 
                   class="list-group-item list-group-item-action border-0 d-flex align-items-center px-0 py-2 text-sm {{ request('trash') ? 'text-dark' : 'text-danger' }}">
                    <i class="material-symbols-rounded me-2 {{ request('trash') ? '' : 'text-danger' }}">
                        {{ request('trash') ? 'restore_from_trash' : 'delete_sweep' }}
                    </i>
                    <span>
                        Tampilkan Asrama Siswa yang {{ request('trash') ? 'aktif' : 'telah dihapus' }}
                    </span>
                </a>
            </div>

            @if(request('trash'))
                <hr class="horizontal dark my-2">
                <div class="bg-gray-100 border-radius-lg p-2">
                    <p class="text-xxs text-muted mb-0 italic" style="line-height: 1.4;">
                        <i class="material-symbols-rounded text-xs">info</i> 
                        Anda sedang melihat data di dalam <strong>Tempat Sampah</strong>.
                    </p>
                </div>
            @endif
        </div>
    </div>
@endpush


@section('body-content')
    @include('components.navbar-admin')
 
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <x-table
                    type="material"
                    :data="$boardingFacilityStdn"
                    :columns="$columns"
                    title="Ruangan"
                    searchRoute="{{ route('administration::facility.buildings.index', ['academic' => request('academic')]) }}"
                    :trash="$trashed"
                />
            </div>
       
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header pb-0 p-3">
                        <h6 class="text-black">
                            <i class="mdi mdi-office-building float-left mr-2"></i>
                            {{ isset($editItem) ? 'Ubah' : 'Tambah' }} Asrama Siswa
                        </h6>
                    </div>
                    <div class="card-body">
                        <form class="form-block" 
                            action="{{ isset($editItem) ? route('boarding::facility.student.update', ['student' => $editItem->id, 'next' => request()->fullUrl()]) : route('boarding::facility.student.store', ['next' => request()->fullUrl()]) }}" 
                            method="POST">
                            @csrf
                            @if (isset($editItem))
                                @method('PUT')
                            @endif

                            {{-- Hidden inputs untuk state JS (dependent dropdown) --}}
                            <input type="hidden" id="selected-room-id" value="{{ $editItem->room_id ?? '' }}">
                            <input type="hidden" id="selected-building-id" value="{{ $editItem->building_id ?? '' }}">

                            {{-- Select Gedung --}}
                            <x-input-group :isRow="true">
                                <x-label value="Gedung" />
                                <x-col size="12">
                                    <x-select
                                        name="building_id"
                                        id="building-select"
                                        placeholder="Pilih Gedung"
                                        required
                                        class="select-2"
                                        :options="$buildings->map(fn($b) => [
                                            'value' => $b->id,
                                            'label' => $b->name
                                        ])"
                                        :value="$editItem->building_id ?? old('building_id')"
                                    />
                                </x-col>
                            </x-input-group>

                            {{-- Select Ruang (Biasanya diisi via AJAX berdasarkan Gedung) --}}
                            <x-input-group :isRow="true">
                                <x-label value="Ruang" />
                                <x-col size="12">
                                    <x-select
                                        name="room_id"
                                        id="room-select"
                                        placeholder="Pilih Ruang"
                                        required
                                        class="select-2"
                                        :options="[]" 
                                    />
                                </x-col>
                            </x-input-group>

                            {{-- Select Pengasuh --}}
                            <x-input-group :isRow="true">
                                <x-label value="Pengasuh" />
                                <x-col size="12">
                                    <x-select
                                        name="empl_id"
                                        id="empl-select"
                                        placeholder="Pilih Pengasuh"
                                        required
                                        class="select-2"
                                        :options="$empBoarding->map(fn($e) => [
                                            'value' => $e->id,
                                            'label' => $e->user->name
                                        ])"
                                        :value="$editItem->empl_id ?? old('empl_id')"
                                    />
                                </x-col>
                            </x-input-group>

                            {{-- Select Siswa --}}
                            <x-input-group :isRow="true">
                                <x-label value="Siswa" />
                                <x-col size="12">
                                    <x-select
                                        name="student_id"
                                        placeholder="Pilih Siswa"
                                        required
                                        class="select-2"
                                        :options="$students->map(fn($s) => [
                                            'value' => $s->id,
                                            'label' => $s->user->profile->name
                                        ])"
                                        :value="$editItem->student_id ?? old('student_id')"
                                    />
                                </x-col>
                            </x-input-group>

                            <x-input-group class="mb-0">
                                <x-btn class="mt-2" type="submit" variant="{{ isset($editItem) ? 'warning' : 'success' }}">
                                    {{ isset($editItem) ? 'Update' : 'Simpan' }}
                                </x-btn>
                                @if(isset($editItem))
                                    <a href="{{ route('boarding::facility.student.index') }}" class="btn btn-light mt-2">Batal</a>
                                @endif
                            </x-input-group>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                const buildingId = $('#selected-building-id').val();
                const selectedRoomId = $('#selected-room-id').val();

                $('#building-select').on('change', function() {
                    let buildingId = $(this).val();

                    $('#room-select').empty().append('<option value="">Loading...</option>');

                    if (buildingId) {
                        let url = `{{ route('boarding::building-rooms', ['building_id' => 'BUILDING_ID_PLACEHOLDER']) }}`;
                        url = url.replace('BUILDING_ID_PLACEHOLDER', buildingId);

                        $.ajax({
                            url: url,
                            type: 'GET',
                            success: function(data) {
                                $('#room-select').empty().append('<option value="">Pilih Ruang</option>');

                                $.each(data, function(key, value) {
                                    const selected = value.id == selectedRoomId ? 'selected' : '';
                                    $('#room-select').append(`<option value="${value.id}" ${selected}>${value.name}</option>`);
                                });

                                $('#room-select').val(selectedRoomId).trigger('change');
                            }
                        });
                    } else {
                        $('#room-select').empty().append('<option value="">Pilih Ruang</option>');
                    }
                });

                if (buildingId) {
                    $('#building-select').val(buildingId).trigger('change');
                }
            });
        </script>
    @endpush
@endsection
