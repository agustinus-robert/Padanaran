@extends('finance::layouts.default')

@section('title', 'Ubah potongan | ')
@section('navtitle', 'Ubah potongan')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-8 col-xl-10">
            <div class="d-flex align-items-center mb-4">
                <a class="text-decoration-none" href="{{ request('next', url()->previous()) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                <div class="ms-4">
                    <h2 class="mb-1">Ubah potongan</h2>
                    <div class="text-secondary">Silakan isi formulir di bawah ini untuk membuat potongan</div>
                </div>
            </div>
            <div class="card mb-4 border-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <i class="mdi mdi-plus"></i> Ubah potongan
                    </div>
                </div>
                <div class="card-body">
                    <form class="form-block" enctype="multipart/form-data" action="{{ route('finance::service.deduction.manage.update', ['deduction' => $deduction->id, 'next' => request('next')]) }}" method="POST"> @csrf @method('put')
                        <div class="row required mb-3">
                            <label class="col-md-4 col-lg-3 col-form-label required">Nama pengguna</label>
                            <div class="col-md-6">
                                <select name="empl" id="empl" class="form-select @error('empl') is-invalid @enderror" required>
                                    <option value="{{ $deduction->empl_id }}">{{ $deduction->employee->user->name }}</option>
                                </select>
                                @error('empl')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row required mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Kategori</label>
                            <div class="col-xl-8 col-xxl-6">
                                @foreach ($categories as $type)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="type" id="type-{{ $type->value }}" value="{{ $type->value }}" required @checked(old('type', $type->value) == $deduction->type->value) />
                                        <label class="form-check-label" for="type-{{ $type->value }}"> {{ $type->label() }} </label>
                                    </div>
                                @endforeach
                                @error('type')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row required mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Komponen</label>
                            <div class="col-lg-8 col-xl-7">
                                <select name="component_id" id="component_id" class="form-select">
                                    <option value=""></option>
                                    @foreach ($items ?? [] as $item)
                                        <option value="{{ $item->component_id }}" @selected(old('component_id', $item->component_id) == $deduction->component_id)>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row required mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Nominal</label>
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1">Rp</span>
                                    <input type="number" name="amount" class="form-control" value="{{ old('amount', $deduction->amount) }}" required />
                                </div>
                            </div>
                        </div>
                        <div class="row required mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Keterangan</label>
                            <div class="col-lg-8 col-xl-9">
                                <textarea name="description" id="description" rows="10" class="form-control">{{ old('description', $deduction->description) }}</textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-8 offset-lg-4 offset-xl-3">
                                <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                                <a class="btn btn-ghost-light text-dark" href="{{ request('next', url()->previous()) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/tom-select/css/tom-select.bootstrap5.min.css') }}">
@endpush

@push('scripts')
    <script>
        const reloadCartId = (e) => {
            window.location.href = e.querySelector('option:checked').dataset.route;
        }

        document.addEventListener("DOMContentLoaded", async () => {
            new TomSelect('[name="empl"]', {});
            new TomSelect('[name="component_id"]', {});
        });
    </script>
@endpush
