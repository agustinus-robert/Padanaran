@extends('layouts.horizontal-layout')

@section('title', 'Referensi - ')
@section('titleTemplate', config('account.admin.name'))
@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@section('navtitle', 'Tagihan')

@section('breadcrumb')
    <li class="breadcrumb-item">Tagihan</li>
    <li class="breadcrumb-item active">Referensi</li>
@endsection

@push('nav')
@include('administration::layouts.includes.navbar-administration')
@endpush

{{-- @push('styles')
<style>
.category-slider {
    position: relative;
    display: flex;
    align-items: center;
    width: 100%;
}

.category-container {
    display: flex;
    overflow-x: auto;
    scroll-behavior: smooth;
    gap: 10px;
    width: 100%;
    padding: 5px 35px; /* ruang kanan kiri buat panah */
}
.category-container::-webkit-scrollbar {
    display: none; /* Chrome, Safari */
}
.category-container {
    -ms-overflow-style: none;  /* IE, Edge lama */
    scrollbar-width: none;     /* Firefox */
}

.shift-btn {
    min-width: 120px;     /* panjang minimum biar kotak persegi panjang */
    padding: 8px 20px;    /* atas-bawah 8px, kiri-kanan 20px */
    white-space: nowrap;  /* teks tidak turun ke bawah */
}

.arrow-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 10;
    background: rgba(255, 255, 255, 0.9);
    border: 1px solid #ddd;
    border-radius: 50%;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 18px;
    line-height: 1;
}
.arrow-left { left: 0; }
.arrow-right { right: 0; }
</style>

@endpush --}}

 {{-- <div class="category-slider">
    <!-- Tombol panah kiri -->
    <button class="arrow-btn arrow-left" onclick="scrollCategory(-200)">
        ‹
    </button>

    <!-- Container scroll -->
    <div id="categoryContainer" class="category-container">
        <button class="btn btn-outline-primary shift-btn">Gelombang 1</button>
        <button class="btn btn-outline-warning shift-btn">Gelombang 2</button>
        <button class="btn btn-outline-success shift-btn">Gelombang 3</button>
    </div>

    <!-- Tombol panah kanan -->
    <button class="arrow-btn arrow-right" onclick="scrollCategory(200)">
        ›
    </button>
</div> --}}
@php
$trashed = false;
$columns = [
    [
        'field' => 'kd',
        'label' => 'Kode',
        'slot' => fn ($item) => e($item->kd),
    ],
    [
        'field' => 'name',
        'label' => 'Nama',
        'slot' => fn ($item) => e($item->name),
    ],
    [
        'field' => 'batch',
        'label' => 'Gelombang',
        'slot' => fn ($item) => e($item->batch->name),
    ],
    [
        'field' => 'payment_category',
        'label' => 'Kategori Pembayaran',
        'slot' => fn ($item) => e($item->payment_category->label()),
    ],
    [
        'field' => 'payment_cycle',
        'label' => 'Siklus Pembayaran',
        'slot' => fn ($item) => e($item->payment_cycle->label()),
    ],
    [
        'field' => 'price',
        'label' => 'Harga',
        'slot' => fn ($item) =>
            'Rp ' . number_format($item->price, 0, ',', '.'),
    ],
    [
        'field' => 'type',
        'label' => 'Kategori',
        'slot' => fn ($item) => e($item->type->name),
    ],
    [
        'field' => 'actions',
        'label' => '',
        'slot' => fn ($item) => view('components.partial-actions', [
            'item' => $item,
            'routes' => [
                // PENTING: edit lewat INDEX + ?edit=id
                'index'   => 'administration::bill.references.index',
                'destroy' => 'administration::bill.references.destroy',
                'restore' => 'administration::bill.references.restore',
                'kill'    => 'administration::bill.references.kill',
            ],
        ])->render(),
    ],
];

$searchDynamic = [
    'semester' => 'semester_id',
    'gelombang' => 'batch_id',
    'kelas' => 'class_id'
];
@endphp

@push('additional-content')
    @php
        $extraMenus = [
            [
                'label' => request('trash') ? 'Tampilkan Referensi Aktif' : 'Tampilkan Referensi Terhapus',
                'route' => route('administration::facility.buildings.index', ['trash' => request('trash', 0) ? 0 : 1]),
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
                    :data="$bills"
                    :isSearch="false"
                    :columns="$columns"
                    title="Referensi Tagihan"
                    :searchDynamic="$searchDynamic"
                    :count="count($bills)"
                    searchRoute="{{ route('administration::bill.references.index', ['academic' => request('academic')]) }}"
                    :trash="$trashed"
                />
            </div>
            <div class="col-md-4">
                <div class="card mb-4 p-0">
                    <div class="card-header">
                        <h6>Kelola Referensi Pembayaran</h6>
                    </div>
                    <div class="card-body">
                        <form class="form-block" action="{{ isset($editBill) ? route('administration::bill.references.update', $editBill->id) : route('administration::bill.references.store') }}" method="POST">
                            @csrf

                            @if(isset($editBill))
                                @method('PUT')
                            @endif

                        {{-- KODE --}}
                        {{-- KODE --}}
                            <x-input-group :isRow="true">
                                <x-label value="Kode" />
                                <x-col size="12">
                                    <x-input
                                        name="kd"
                                        value="{{ old('kd', $editBill->kd ?? '') }}"
                                        autocomplete="off"
                                        required
                                    />
                                </x-col>
                            </x-input-group>

                            {{-- NAMA --}}
                            <x-input-group :isRow="true">
                                <x-label value="Nama" />
                                <x-col size="12">
                                    <x-input
                                        name="name"
                                        value="{{ old('name', $editBill->name ?? '') }}"
                                        autocomplete="off"
                                        required
                                    />
                                </x-col>
                            </x-input-group>

                            {{-- GELOMBANG --}}
                            <x-input-group :isRow="true">
                                <x-label value="Gelombang" />
                                <x-col size="12">
                                    <x-select
                                        name="batch_id"
                                        placeholder="Pilih"
                                        :value="old('batch_id', $editBill->batch_id ?? null)"
                                        :options="$academicBatch->map(fn($batch) => [
                                            'value' => $batch->id,
                                            'label' =>
                                                $batch->semesters->academic->name.' '.
                                                $batch->semesters->name.' - '.
                                                $batch->name
                                        ])"
                                        required
                                    />
                                </x-col>
                            </x-input-group>

                            {{-- PAKET PEMBAYARAN --}}
                            <x-input-group :isRow="true">
                                <x-label value="Paket Pembayaran" />
                                <x-col size="12">
                                    <x-select
                                        name="payment_category"
                                        placeholder="Pilih"
                                        :value="old('payment_category', $editBill->payment_category->value ?? null)"
                                        :options="collect(\Modules\Core\Enums\BillReferencesCategoryEnum::cases())
                                            ->map(fn($p) => [
                                                'value' => $p->value,
                                                'label' => $p->label()
                                            ])"
                                        required
                                    />
                                </x-col>
                            </x-input-group>

                            {{-- SIKLUS PEMBAYARAN --}}
                            <x-input-group :isRow="true">
                                <x-label value="Siklus Pembayaran" />
                                <x-col size="12">
                                    <x-select
                                        name="payment_cycle"
                                        placeholder="Pilih"
                                        :value="old('payment_cycle', $editBill->payment_cycle->value ?? null)"
                                        :options="collect(\Modules\Core\Enums\PaymentCycleEnum::cases())
                                            ->map(fn($c) => [
                                                'value' => $c->value,
                                                'label' => $c->label()
                                            ])"
                                        required
                                    />
                                </x-col>
                            </x-input-group>

                            {{-- TIPE --}}
                            <x-input-group :isRow="true">
                                <x-label value="Tipe" />
                                <x-col size="12">
                                    <x-select
                                        name="type"
                                        placeholder="Pilih"
                                        :value="old('type', $editBill->type->value ?? null)"
                                        :options="collect(\Modules\Core\Enums\BillCategoryEnum::cases())
                                            ->map(fn($t) => [
                                                'value' => $t->value,
                                                'label' => $t->label()
                                            ])"
                                        required
                                    />
                                </x-col>
                            </x-input-group>

                            {{-- HARGA --}}
                            <x-input-group :isRow="true">
                                <x-label value="Harga" />
                                <x-col size="12">
                                    <x-input
                                        type="number"
                                        name="price"
                                        value="{{ old('price', $editBill->price ?? '') }}"
                                        autocomplete="off"
                                        required
                                    />
                                </x-col>
                            </x-input-group>


                            <div class="form-group mb-0">
                                <x-btn variant="success">
                                    {{ isset($editBill) ? 'Update' : 'Simpan' }}
                                </x-btn>
                                @if(isset($editBill))
                                    <a href="{{ route('administration::bill.references.index') }}" class="btn btn-secondary">Batal</a>
                                @endif
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let semesterSelect = document.getElementById("semester_id");
        let batchSelect    = document.getElementById("batch_id");
        let referenceSelect= document.getElementById("reference_id");

        // Ambil daftar semester
        fetch("{{ route('api::administration.semesters') }}")
            .then(res => res.json())
            .then(data => {
                data.forEach(item => {
                    let opt = new Option(item.name, item.id);
                    semesterSelect.add(opt);
                });
            });

        // Event: pilih semester -> ambil batch berdasarkan semester
        semesterSelect.addEventListener("change", function() {
            let semesterId = this.value;
            batchSelect.innerHTML = '<option value="">Pilih</option>';
            referenceSelect.innerHTML = '<option value="">Pilih</option>';

            if (!semesterId) return;

            fetch(`{{ route('api::administration.batches') }}?semester_id=${semesterId}`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(item => {
                        let opt = new Option(item.name, item.id);
                        batchSelect.add(opt);
                    });
                });
        });

        // Event: pilih batch -> ambil reference berdasarkan batch
        batchSelect.addEventListener("change", function() {
        let batchId = this.value;
        referenceSelect.innerHTML = '<option value="">Pilih</option>'; // reset

        if (!batchId) return;

        fetch(`{{ route('api::administration.references') }}?batch_id=${batchId}`)
            .then(res => res.json())
            .then(data => {
                data.forEach(item => {
                    // pakai type_class sebagai value
                    let opt = new Option(item.type_class_label, item.type_class);
                    referenceSelect.add(opt);
                });
            });
     });

    });
</script>
@endpush
