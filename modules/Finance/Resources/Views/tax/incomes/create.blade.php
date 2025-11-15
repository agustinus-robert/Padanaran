@extends('finance::layouts.default')

@section('title', 'Tambah bukti potong PPh 21 | ')
@section('navtitle', 'Tambah bukti potong PPh 21')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-8 col-xl-10">
            <div class="d-flex align-items-center mb-4">
                <a class="text-decoration-none" href="{{ request('next', route('finance::tax.incomes.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                <div class="ms-4">
                    <h2 class="mb-1">Tambah bukti potong PPh 21</h2>
                    <div class="text-secondary">Silakan isi formulir di bawah ini untuk menambah bukti potong PPh 21</div>
                </div>
            </div>
            <div class="card mb-4 border-0">
                <div class="card-body">
                    <i class="mdi mdi-calendar-multiselect"></i> Form bukti potong PPh 21
                </div>
                <div class="card-body">
                    <form class="form-block" action="{{ route('finance::tax.incomes.store', ['next' => request('next', route('finance::tax.incomes.index'))]) }}" method="POST" enctype="multipart/form-data"> @csrf
                        <div class="row required mb-3">
                            <label class="col-lg-2 col-xl-2 col-form-label">Nama karyawan</label>
                            <div class="col-xl-8 col-xxl-8">
                                <select class="form-select @error('empl_id') is-invalid @enderror" name="empl_id" required>
                                    @isset($employee)
                                        <option value="{{ $employee->id }}" selected>{{ $employee->user->name }}</option>
                                    @endisset
                                </select>
                                @error('empl_id')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row required mb-3">
                            <label class="col-lg-2 col-xl-2 col-form-label">Tipe</label>
                            <div class="col-xl-8 col-xxl-8">
                                <select class="form-select @error('type') is-invalid @enderror" name="type" required>
                                    @foreach ($types as $type)
                                        <option value="{{ $type->value }}" @selected($type == Modules\HRMS\Enums\TaxTypeEnum::YEARLY)>{{ $type->label() }}</option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row required mb-3">
                            <label class="col-lg-2 col-xl-2 col-form-label">Periode</label>
                            <div class="col-xl-12 col-xxl-8">
                                <div class="input-group form-calculate mb-2">
                                    <input type="datetime-local" class="form-control" name="start_at" value="{{ old('start_at', $start_at) }}">
                                    <div class="input-group-text">s.d.</div>
                                    <input type="datetime-local" class="form-control" name="end_at" value="{{ old('end_at', $end_at) }}">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-lg-2 col-xl-2 col-form-label">Lampiran</label>
                            <div class="col-xl-8">
                                <input class="form-control @error('files') is-invalid @enderror" name="files" type="file" id="upload-input" accept="image/*,application/pdf">
                                <small class="text-muted">Berkas berupa .jpg, .png atau .pdf maksimal berukuran 2mb</small>
                                @error('files')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-8 offset-lg-4 offset-xl-3">
                                <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                                <a class="btn btn-ghost-light text-dark" href="{{ route('finance::tax.incomes.index', ['next' => request('next')]) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
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
        document.addEventListener("DOMContentLoaded", async () => {
            new TomSelect('[name="empl_id"]', {
                valueField: 'id',
                labelField: 'text',
                searchField: 'text',
                load: function(q, callback) {
                    fetch('{{ route('api::hrms.employees.search') }}?q=' + encodeURIComponent(q))
                        .then(response => response.json())
                        .then(json => {
                            callback(json.employees);
                        }).catch(() => {
                            callback();
                        });
                }
            });
        });
    </script>
@endpush
