@extends('layouts.horizontal-layout')

@section('title', 'Kegiatan Siswa - ')

@section('navtitle', 'Kegiatan Siswa')

@section('breadcrumb')
    <li class="breadcrumb-item">Pondok</li>
    <li class="breadcrumb-item active">Event</li>
@endsection

@push('nav')
@include('boarding::layouts.includes.navbar-boarding')
@endpush

@php
$trashed = false; 

$columns = [
    [
        'field' => 'name', 
        'label' => 'Nama Kegiatan',
    ],
    [
        'field' => 'type', 
        'label' => 'Tipe', 
        'slot' => fn($item) => $item->type->label()
    ],
    [
        'field' => 'start_date', 
        'label' => 'Tanggal Mulai', 
        'slot' => fn($item) => !empty($item->start_date) 
            ? \Carbon\Carbon::parse($item->start_date)->locale('id')->isoFormat('dddd, D MMMM YYYY') 
            : '-'
    ],
    [
        'field' => 'end_date', 
        'label' => 'Tanggal Akhir', 
        'slot' => fn($item) => !empty($item->end_date) 
            ? \Carbon\Carbon::parse($item->end_date)->locale('id')->isoFormat('dddd, D MMMM YYYY') 
            : '-'
    ],
    [
        'field' => 'in', 
        'label' => 'Jam Mulai', 
        'slot' => fn($item) => \Carbon\Carbon::parse($item->in)->format('H:i')
    ],
    [
        'field' => 'out', 
        'label' => 'Jam Selesai', 
        'slot' => fn($item) => \Carbon\Carbon::parse($item->out)->format('H:i')
    ],
    [
        'field' => 'type_participant', 
        'label' => 'Peserta', 
        'slot' => fn($item) => !empty($item->type_participant) && $item->type_participant == 1 ? 'Per Siswa' : 'Rombel'
    ],
    [
        'field' => 'actions', 
        'label' => '', 
        'slot' => fn($item) => view('components.partial-actions', [
            'item' => $item,
            'trashed' => $item->trashed(),
            'routes' => [
                'edit'    => 'boarding::event.event-reference.edit',
                'destroy' => 'boarding::event.event-reference.destroy',
                'restore' => 'boarding::facility.buildings.restore',
                'kill'    => 'boarding::facility.buildings.kill',
            ],
            'params' => [
                'edit'            => ['event_reference' => $item->id],
                'destroy'         => ['event_reference' => $item->id],
                'restore'         => ['building' => $item->id],
                'kill'            => ['building' => $item->id],
            ]
        ])->render()
    ]
];
@endphp

@section('body-content')
    <div class="row container-fluid">
        @include('components.navbar-admin')

        <div class="col-md-8">
             <x-table
                type="material"
                :data="$boardingEvent"
                :columns="$columns"
                title="Daftar Event"
                searchRoute="{{ route('boarding::event.event-reference.index', ['academic' => request('academic')]) }}"
                :trash="$trashed"
            />
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header"><i class="mdi mdi-office-building float-left mr-2"></i>Kelola Refrensi Event</div>
                <div class="card-body">
                    <form class="form-block" action="{{ isset($editItem) ? route('boarding::event.event-reference.update', ['event_reference' => $editItem->id, 'next' => request()->fullUrl()]) : route('boarding::event.event-reference.store', ['next' => request()->fullUrl()]) }}" method="POST">
                        @csrf
                        @if (isset($editItem))
                            @method('PUT')
                        @endif

                        {{-- Nama Kegiatan --}}
                        <x-input-group label="Nama Kegiatan" required>
                            <x-input type="text" name="name" :value="old('name', $editItem->name ?? '')" required />
                        </x-input-group>

                        {{-- Tipe Kegiatan --}}
                        <x-input-group label="Tipe Kegiatan" required>
                            <x-select name="type" id="type-select" required
                                :options="collect(\Modules\Boarding\Enums\BoardingEventTypeEnum::cases())->map(fn($type) => ['value' => $type->value, 'label' => $type->label()])"
                                :selected="old('type', $editItem->type->value ?? '')"
                            />
                        </x-input-group>

                        {{-- Container Tanggal (Hidden by JS) --}}
                        <div id="date-input-container" style="display: none;">
                            <x-input-group label="Tanggal Mulai">
                                <x-input type="date" name="start_date" id="start-date" :value="old('start_date', $editItem->start_date ?? '')" />
                            </x-input-group>
                            
                            <x-input-group label="Tanggal Selesai">
                                <x-input type="date" name="end_date" id="end-date" :value="old('end_date', $editItem->end_date ?? '')" />
                            </x-input-group>
                        </div>

                        {{-- Jam Mulai --}}
                        <x-input-group label="Jam Mulai Kegiatan" required>
                            <x-input type="time" name="in" 
                                :value="old('in', isset($editItem) ? \Carbon\Carbon::parse($editItem->in)->format('H:i') : '')" 
                                required />
                        </x-input-group>

                        {{-- Jam Selesai --}}
                        <x-input-group label="Jam Selesai Kegiatan" required>
                            <x-input type="time" name="out" 
                                :value="old('out', isset($editItem) ? \Carbon\Carbon::parse($editItem->out)->format('H:i') : '')" 
                                required />
                        </x-input-group>

                        {{-- Peserta --}}
                        <x-input-group label="Peserta Kegiatan" required>
                            <x-select name="type_participant" required
                                :options="[
                                    ['value' => '1', 'label' => 'Per Siswa'],
                                    ['value' => '2', 'label' => 'Rombel']
                                ]"
                                :selected="old('type_participant', $editItem->type_participant ?? '')"
                                placeholder="Pilih Peserta Kegiatan"
                            />
                        </x-input-group>

                        <div class="mt-4">
                            <x-btn type="submit" variant="dark">
                                {{ isset($editItem) ? 'Update' : 'Simpan' }}
                            </x-btn>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <i class="mdi mdi-cogs float-left mr-2"></i>Lanjutan
                </div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action text-danger" href="{{ route('boarding::event.event-reference.index', ['trash' => request('trash', 0) ? null : 1]) }}"><i class="mdi mdi-delete-outline"></i> Tampilkan Asrama Siswa yang {{ request('trash', 0) ? 'tidak' : '' }} dihapus</a>
                </div>
            </div>
        </div>
    </div>
@endsection


<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectType = document.getElementById('type-select');
    const dateContainer = document.getElementById('date-input-container');
    const startDate = document.getElementById('start-date');
    const endDate = document.getElementById('end-date');

    function toggleDateInputs() {
        if (selectType.value == 2) {
            dateContainer.style.display = 'block';
            startDate.setAttribute('required', 'required');
            endDate.setAttribute('required', 'required');
        } else {
            dateContainer.style.display = 'none';
            startDate.removeAttribute('required');
            endDate.removeAttribute('required');
            startDate.value = ''; 
            endDate.value = '';
        }
    }

    toggleDateInputs(); 

    selectType.addEventListener('change', toggleDateInputs);
});
</script>
