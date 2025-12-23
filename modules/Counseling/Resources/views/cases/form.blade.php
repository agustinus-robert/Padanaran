@extends('layouts.horizontal-layout')

@php
    $caseId = data_get($case ?? null, 'id');
    $isEdit = !empty($caseId);
@endphp

@section('title', ($isEdit ? 'Ubah kasus' : 'Input kasus baru') . ' - ')

@push('nav')
    @include('counseling::layouts.includes.navbar-counseling')
@endpush

@section('body-content')

@include('components.navbar-admin')

<div class="row container-fluid">
    <div class="col-md-7 col-lg-8">
        <div class="card mb-4">
            <x-card-header type="{{ config('theme.default') }}">
                {{ $isEdit ? 'Ubah kasus' : 'Input kasus baru' }}
            </x-card-header>

            <div class="card-body">
                @if(session('success'))
                    <div id="flash-success" class="alert alert-success mt-4">
                        {!! session('success') !!}
                    </div>
                @endif

                <form action="{{ $isEdit
                    ? route('counseling::cases.update', ['case' => $caseId, 'next' => request('next', route('counseling::cases.index'))])
                    : route('counseling::cases.store', ['next' => request('next')])
                }}" method="POST">
                    @csrf
                    @if($isEdit)
                        @method('PUT')
                    @endif

                    <x-input-group label="Nama siswa" :isRow="false">
                        @if($isEdit)
                            <x-input
                                :value="$case->semester->classroom->name . ' - ' . $case->semester->student->full_name"
                                readonly
                                disabled
                            />
                        @else
                            <x-select
                                name="smt_id[]"
                                :options="collect($classrooms)->flatMap(function($semesters, $classroom) {
                                    return $semesters->map(function($semester) use ($classroom) {
                                        return [
                                            'value' => $semester->id,
                                            'label' => $classroom.' - '.$semester->student->full_name,
                                            'selected' => in_array($semester->id, old('smt_id', []))
                                        ];
                                    });
                                })->values()->toArray()"
                                placeholder="Cari nama siswa disini ..."
                                :error="$errors->has('smt_id')"
                                multiple
                            />
                        @endif
                        @error('smt_id')
                            <small class="invalid-feedback d-block">{{ $message }}</small>
                        @enderror
                    </x-input-group>

                    <x-input-group label="Kategori kasus" :isRow="false">
                        <x-select
                            name="category_id"
                            :options="$categories->mapWithKeys(fn($c) => [$c->id => $c->name])"
                            :selected="old('category_id', $case->category_id ?? '')"
                            :data="['descriptions' => $isEdit ? $case->category->descriptions : '']"
                            required
                        />
                        @error('category_id')
                            <small class="invalid-feedback d-block">{{ $message }}</small>
                        @enderror
                    </x-input-group>

                    <x-input-group label="Deskripsi" :isRow="false">
                        <x-select
                            name="description"
                            :options="$isEdit ? $case->category->descriptions->pluck('name','name') : []"
                            :selected="old('description', $case->description ?? '')"
                            placeholder="Pilih kategori terlebih dahulu"
                            required
                            :tags="true"
                        />
                        @error('description')
                            <small class="invalid-feedback d-block">{{ $message }}</small>
                        @enderror
                    </x-input-group>

                    <x-input-group label="Poin" :isRow="false">
                        <x-input
                            name="point"
                            type="number"
                            :value="old('point', $case->point ?? 0)"
                            required
                        />
                        @error('point')
                            <small class="invalid-feedback d-block">{{ $message }}</small>
                        @enderror
                    </x-input-group>

                    <x-input-group label="Saksi" :isRow="false">
                        <x-input
                            name="witness"
                            :value="old('witness', $case->witness ?? '')"
                            required
                        />
                        @error('witness')
                            <small class="invalid-feedback d-block">{{ $message }}</small>
                        @enderror
                    </x-input-group>

                    <x-input-group label="Tanggal dan waktu" :isRow="false">
                        <x-input
                            name="break_at"
                            type="datetime-local"
                            :value="old('break_at', $isEdit ? \Carbon\Carbon::parse($case->break_at)->toDateTimeLocalString() : now()->format('Y-m-d\TH:i'))"
                            required
                        />
                        @error('break_at')
                            <small class="invalid-feedback d-block">{{ $message }}</small>
                        @enderror
                    </x-input-group>

                    <div class="mt-3">
                        <x-btn variant="primary">{{ $isEdit ? 'Simpan perubahan' : 'Simpan' }}</x-btn>
                        <a class="btn btn-secondary" href="{{ request('next', route('counseling::cases.index')) }}">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-5 col-lg-4">
        @include('account::includes.account-info')
        <div class="card">
            <div class="card-header"><i class="mdi mdi-cogs mr-2"></i>Lanjutan</div>
            <div class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action text-muted" href="{{ route('counseling::cases.index') }}"><i class="mdi mdi-briefcase-account-outline"></i> Data kasus</a>
                <a class="list-group-item list-group-item-action text-muted" href="{{ route('counseling::manage.cases.categories.index') }}"><i class="mdi mdi-briefcase-outline"></i> Kelola kategori</a>
                <a class="list-group-item list-group-item-action text-muted" href="{{ route('counseling::manage.cases.descriptions.index') }}"><i class="mdi mdi-briefcase-outline"></i> Kelola deskripsi</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/select2/css/select2-bootstrap4.min.css') }}">
@endpush

@push('script')
<script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
<script>
    $('[name="category_id"]').on('change', function() {
        var descs = $(this).find(':selected').data('descriptions') || [];
        var $descSelect = $('[name="description"]');
        $descSelect.html('');
        $.each(descs, function(k,v) {
            $descSelect.append(`<option value="${v.name}" data-point="${v.point}">${v.name}</option>`);
        });
        $('[name="point"]').val(descs[0]?.point || 0);
    });

    $('[name="description"]').on('change', function() {
        var point = $(this).find(':selected').data('point');
        $('[name="point"]').val(point);
    });

    $('[name="description"]').select2({
        theme: 'bootstrap4',
        tags: true
    });
</script>
@endpush
