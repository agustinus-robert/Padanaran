@extends('layouts.horizontal-layout')

@section('title', 'Tambah pengaturan template gaji | ')
@section('navtitle', 'Tambah pengaturan template gaji')

@push('nav')
    @include('core::layouts.includes.navbar-core')
@endpush

@section('body-content')
    @include('components.navbar-admin')

    <div class="row container-fluid justify-content-center">
        <div class="col-9">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient-dark text-white">
                    Formulir pengaturan template
                </div>

                <div class="card-body border-top border-light p-4">
                    <form class="form-block form-confirm" action="{{ route('core::company.salaries.configs.store', ['next' => request('next', route('core::company.salaries.configs.index'))]) }}" method="POST"> @csrf
                       <form class="form-block form-confirm" action="{{ route('core::company.salaries.configs.store', ['next' => request('next', route('core::company.salaries.configs.index'))]) }}" method="POST">
                        @csrf

                        <x-input-group :isRow="true" required>
                            <x-label value="Tipe setting {{ $disabled }}" />
                            <x-col size="12">
                                <div class="card card-body px-3 py-2">
                                    @foreach ($types as $label => $type)
                                        <x-radio
                                            name="active"
                                            id="active-{{ $type->value }}"
                                            value="{{ $type->value }}"
                                            :label="$type->label()"
                                            :checked="old('active', request('active_id')) == $type->value"
                                            :disabled="$type->value == $disabled"
                                            onchange="reloadActiveId(event.currentTarget)"
                                            data-route="{{ route('core::company.salaries.configs.create', [...request()->only('active_id', 'next'), 'active_id' => request('active', $type->value)]) }}"
                                        />
                                    @endforeach
                                </div>
                                @error('active')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </x-col>
                        </x-input-group>

                        {{-- Key --}}
                        <x-input-group :isRow="true" required>
                            <x-label value="Name" />
                            <x-col size="12">
                                <x-input type="text" name="key" id="key" placeholder="" required
                                        @class(['is-invalid' => $errors->has('key')]) />
                                @error('key')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                                <small class="form-text text-muted">
                                    Nama tanpa spasi, gunakan underscore, huruf kecil semua!<br>
                                    Contoh: <strong>cmp_overtime_config</strong>
                                </small>
                            </x-col>
                        </x-input-group>

                        {{-- Include Active Form --}}
                        @if (!is_null($active))
                            @include($active)
                        @endif

                            {{-- Tombol Simpan --}}
                        <x-input-group :isRow="false">
                            <x-col size="12" class="d-flex gap-2 mt-2">
                                <x-btn  variant="dark">
                                    <span class="material-symbols-rounded">check</span> Simpan
                                </x-btn>
                            </x-col>
                        </x-input-group>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .readonly {
            background: transparent !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        const reloadActiveId = (e) => {
            window.location.href = e.dataset.route;
        }
    </script>
@endpush
