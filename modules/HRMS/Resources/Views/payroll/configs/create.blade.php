@extends('hrms::layouts.default')

@section('title', 'Tambah pengaturan template gaji | ')
@section('navtitle', 'Tambah pengaturan template gaji')

@section('content')
    <div class="row justify-content-center">
        <div class="col-9">
            <div class="d-flex align-items-center mb-4">
                <a class="text-decoration-none" href="{{ request('next', route('hrms::payroll.configs.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                <div class="ms-4">
                    <h2 class="mb-1">Pengaturan template gaji</h2>
                    <div class="text-secondary">Isi formulir dibawah untuk menambahkan pengaturan</div>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-file-plus-outline"></i> Formulir pengaturan template
                </div>
                <div class="card-body border-top border-light p-4">
                    <form class="form-block form-confirm" action="{{ route('hrms::payroll.configs.store', ['next' => request('next', route('hrms::payroll.configs.index'))]) }}" method="POST"> @csrf
                        <div class="mb-3">
                            <label for="key" class="form-label">Name</label>
                            <input type="text" class="form-control" name="key" id="key" aria-describedby="helpId" placeholder="" />
                            <small id="helpId" class="form-text text-muted">Nama tanpa spasi, gunakan tanda underscore sebagai pengganti spasi, huruf kecil semua!</small><br>
                            <small id="helpId" class="form-text text-muted">Contoh penulisan: <strong>cmp_overtime_config</strong></small>
                        </div>
                        <div class="mb-3">
                            <label for="default_component" class="form-label">Komponen default</label>
                            <input type="text" class="form-control" name="default_component" id="default_component" aria-describedby="def" data-id="{{ $default->id }}" value="{{ $default->name }}" readonly />
                        </div>
                        <div class="mb-3">
                            <label for="default_component" class="form-label">Komponen</label>
                            <select name="component" id="component" class="form-select">
                                @foreach ($components->groupBy('slip.name') as $key => $_components)
                                    <optgroup label="{{ $key ?: 'Lainnya' }}">
                                        @forelse($_components as $key => $component)
                                            <option value="{{ $component->id }}">{{ $component->name }}</option>
                                        @empty
                                        @endforelse
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="calculation" class="form-label">Rumus hitung</label>
                            <input type="text" class="form-control" name="calculation" id="calculation" aria-describedby="calc" />
                            <small id="calculation" class="form-text text-muted">Gaji pokok dilambangkan dengan huruf <strong>"p"</strong></small><br>
                            <small id="calculation" class="form-text text-muted">Contoh penulisan: <strong>((p/20)/8) * (135/100)</strong></small>
                        </div>
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
