@extends('admin::layouts.default')

@section('title', 'Tambah pengaturan embark | ')
@section('navtitle', 'Tambah pengaturan embark')

@section('content')
    <div class="row justify-content-center">
        <div class="col-9">
            <div class="d-flex align-items-center mb-4">
                <a class="text-decoration-none" href="{{ request('next', route('core::company.salaries.configs.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                <div class="ms-4">
                    <h2 class="mb-1">Pengaturan</h2>
                    <div class="text-secondary">Isi formulir dibawah untuk menambahkan pengaturan</div>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-file-plus-outline"></i> Formulir pengaturan template
                </div>
                <div class="card-body border-top border-light p-4">
                    <form class="form-block form-confirm" action="" method="POST"> @csrf
                        <div class="mb-3">
                            <label class="form-label">Tipe setting {{ $disabled }}</label>
                            <div class="card card-body px-3 py-2">
                                @foreach ($types as $label => $type)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="active" id="active-{{ $type->value }}" value="{{ $type->value }}" data-route="{{ route('core::company.salaries.configs.create', [...request()->only('active_id', 'next'), 'active_id' => request('active', $type->value)]) }}" onchange="reloadActiveId(event.currentTarget)" @checked(old('active', request('active_id')) == $type->value) @disabled($type->value == $disabled)>
                                        <label class="form-check-label" for="active-{{ $type->value }}">{{ $type->label() }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="key" class="form-label required">Name</label>
                            <input type="text" class="form-control" name="key" id="key" placeholder="" required />
                            <small class="form-text text-muted">Nama tanpa spasi, gunakan tanda underscore sebagai pengganti spasi, huruf kecil semua!</small><br>
                            <small class="form-text text-muted">Contoh penulisan: <strong>cmp_overtime_config</strong></small>
                        </div>
                        @if (!is_null($active))
                            @include($active)
                        @endif
                        <div class="mb-3">
                            <button type="submit" class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                        </div>
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
