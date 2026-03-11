@extends('layouts.horizontal-layout')

@php
    // VALIDASI AMAN: object / array / null
    $counselingId = data_get($counseling ?? null, 'id');
    $isEdit = !empty($counselingId);
@endphp

@section('title', ($isEdit ? 'Ubah kasus' : 'Input konseling baru') . ' - ')

@push('nav')
    @include('counseling::layouts.includes.navbar-counseling')
@endpush

@push('additional-content')
    @php
        $menus = [
            [
                'label' => 'Data konseling',
                'route' => route('counseling::counselings.index'),
                'icon' => 'folder_open',
                'class' => 'text-dark'
            ],
            [
                'label' => 'Kelola kategori',
                'route' => route('counseling::manage.counseling.categories.index'),
                'icon' => 'category',
                'class' => 'text-dark'
            ],
        ];
    @endphp

    <x-sidebar-card 
        title="Lanjutan" 
        icon="settings" 
        :items="$menus" 
    />
@endpush

@section('body-content')

@include('components.navbar-admin')

<div class="container-fluid">
    <div class="row justify-content-center">

        <div class="col-md-10">
            <div class="card mb-4">
                <x-card-header type="{{ config('theme.default') }}">
                    {{ $isEdit ? 'Ubah kasus' : 'Input konseling baru' }}
                </x-card-header>

                <div class="card-body">
                    <form
                        action="{{ $isEdit
                            ? route('counseling::counselings.update', [
                                'counseling' => $counselingId,
                                'next' => request('next')
                            ])
                            : route('counseling::counselings.store', [
                                'next' => request('next')
                            ])
                        }}"
                        method="POST"
                    >
                        @csrf
                        @if ($isEdit)
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <x-input-group :isRow="false" required label="Nama siswa">
                                    @if ($isEdit)
                                        <x-input
                                            readonly
                                            disabled
                                            :value="data_get($counseling, 'semester.classroom.name').' - '.data_get($counseling, 'semester.student.full_name')"
                                        />
                                    @else
                                        <x-select
                                            name="smt_id[]"
                                            placeholder="Cari nama siswa disini ..."
                                            :options="collect($classrooms)->flatMap(function($semesters, $classroom) {
                                                return $semesters->map(function($semester) use ($classroom) {
                                                    return [
                                                        'value' => $semester->id,
                                                        'label' => $classroom.' - '.$semester->student->full_name,
                                                        'selected' => in_array($semester->id, old('smt_id', []))
                                                    ];
                                                });
                                            })->values()->toArray()"
                                            :error="$errors->has('smt_id')"
                                        />
                                    @endif

                                    @error('smt_id')
                                        <small class="invalid-feedback d-block">{{ $message }}</small>
                                    @enderror
                                </x-input-group>
                            </div>

                            <div class="col-md-6">
                                <x-input-group :isRow="false" required label="Kategori konseling">
                                    <x-select
                                        name="category_id"
                                        placeholder="-- Pilih --"
                                        :options="collect($categories)->map(function($category) use ($isEdit, $counseling) {
                                            return [
                                                'value' => $category->id,
                                                'label' => $category->name,
                                                'selected' => $category->id == old(
                                                    'category_id',
                                                    data_get($counseling, 'category_id')
                                                )
                                            ];
                                        })->toArray()"
                                        :error="$errors->has('category_id')"
                                    />

                                    @error('category_id')
                                        <small class="invalid-feedback d-block">{{ $message }}</small>
                                    @enderror
                                </x-input-group>
                            </div>
                        </div>

                        <x-input-group :isRow="true" required>
                            <x-label col="3" value="Deskripsi" />
                            <x-col size="8">
                                <x-textarea
                                    name="description"
                                    rows="5"
                                    placeholder="Masukkan deskripsi..."
                                    :value="old('description', data_get($counseling, 'description'))"
                                    :error="$errors->has('description')"
                                />
                                @error('description')
                                    <small class="invalid-feedback d-block">{{ $message }}</small>
                                @enderror
                            </x-col>
                        </x-input-group>

                        <x-input-group :isRow="true" required>
                            <x-label col="3" value="Tindak lanjut" />
                            <x-col size="8">
                                <x-textarea
                                    name="follow_up"
                                    rows="5"
                                    placeholder="Masukkan tindak lanjut..."
                                    :value="old('follow_up', data_get($counseling, 'follow_up'))"
                                    :error="$errors->has('follow_up')"
                                />
                                @error('follow_up')
                                    <small class="invalid-feedback d-block">{{ $message }}</small>
                                @enderror
                            </x-col>
                        </x-input-group>

                        <div class="mt-3">
                            <x-btn variant="dark">
                                Simpan
                            </x-btn>
                            {{-- <x-btn type="submit" variant="dark" value="Simpan" /></x-btn> --}}

                            <a class="btn btn-light"
                            href="{{ request('next', route('counseling::counselings.index')) }}">
                                Kembali
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

{{-- SELECT2 KHUSUS CREATE --}}
@push('style')
@if (! $isEdit)
<link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/select2/css/select2-bootstrap4.min.css') }}">
@endif
@endpush

@push('script')
@if (! $isEdit)
<script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
<script>
    $('[name="smt_id[]"]').select2({
        minimumInputLength: 1,
        theme: 'bootstrap4'
    });
</script>
@endif
@endpush
