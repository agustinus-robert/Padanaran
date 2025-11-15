@extends('core::layouts.default')

@section('title', 'Kategori pinjaman | ')
@section('navtitle', 'Kategori pinjaman')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-8 col-xl-10">
            <div class="d-flex align-items-center mb-4">
                <a class="text-decoration-none" href="{{ request('next', route('core::company.services.loan-categories.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                <div class="ms-4">
                    <h2 class="mb-1">Buat kategori pinjaman baru</h2>
                    <div class="text-secondary">Silakan isi formulir di bawah ini untuk membuat kategori pinjaman baru</div>
                </div>
            </div>
            <div class="card mb-4 border-0">
                <div class="card-body">
                    <form class="form-block" action="{{ route('core::company.services.loan-categories.store', ['next' => request('next')]) }}" method="POST"> @csrf
                        <div class="row required mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Nama kategori</label>
                            <div class="col-xl-8 col-xl-9">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $category->name) }}" required />
                                @error('name')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row required mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Tipe</label>
                            <div class="col-xl-6 col-xl-7">
                                <select class="form-select @error('type') is-invalid @enderror" name="type" id="type" required>
                                    <option value=""></option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type->value }}" @selected($category->type->value == $type->value)>{{ $type->label() }}</option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Bunga pinjaman</label>
                            <div class="col-xl-6 col-xl-7">
                                <select class="form-select @error('interest_id') is-invalid @enderror" name="interest_id" id="interest_id">
                                    <option value=""></option>
                                    @foreach ($categories as $parent)
                                        <option value="{{ $parent->id }}" @selected($parent->id == $category->interest_id)>{{ $parent->name }}</option>
                                    @endforeach
                                </select>
                                @error('interest_id')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-lg-3 col-form-label pt-0">Deskripsi</label>
                            <div class="col-md-8 col-xl-9">
                                <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
                                @error('description')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-lg-3 col-form-label pt-0">Ketersediaan metode</label>
                            <div class="col-md-8 col-lg-9">
                                @include('dynamic-form', ['items' => config('modules.core.features.loans') ?? [], 'meta' => (array) $category->meta])
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-8 offset-lg-4 offset-xl-3">
                                <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                                <a class="btn btn-ghost-light text-dark" href="{{ route('core::company.services.loan-categories.store', ['next' => request('next')]) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('#single_date').on('change', (e) => {
            $('#single_date-text').text($(e.target).is(':checked') ? 'Aktif, pengguna hanya bisa memilih 1 tanggal' : 'Nonaktif, pengguna bisa memilih 1 tanggal atau lebih')
        });

        $('.only_one').on('click', (e) => {
            $('.only_one').not($(e.target)).prop('checked', false);
        });
    </script>
@endpush
