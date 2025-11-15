@extends('core::layouts.default')

@section('title', 'Ubah kategori gaji | ')
@section('navtitle', 'Ubah kategori gaji')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-8 col-xl-10">
            <div class="d-flex align-items-center mb-4">
                <a class="text-decoration-none" href="{{ request('next', route('core::company.salaries.components.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                <div class="ms-4">
                    <h2 class="mb-1">Ubah kategori gaji</h2>
                    <div class="text-secondary">Silakan isi formulir di bawah ini untuk memperbarui informasi komponen gaji {{ $salary->name }}</div>
                </div>
            </div>
            <div class="card mb-4 border-0">
                <div class="card-body">
                    <form class="form-block" action="{{ route('core::company.salaries.components.update', ['component' => $salary->id, 'next' => request('next')]) }}" method="POST"> @csrf @method('PUT')
                        <div class="mb-3">
                            <label class="form-label required" for="ctg_id">Kategori</label>
                            <select name="ctg_id" id="ctg_id" class="form-select @error('ctg_id') is-invalid @enderror" required>
                                <option value="">-- Pilih --</option>
                                @foreach ($slips as $slip)
                                    <optgroup label="{{ $slip->name }}">
                                        @forelse($slip->categories as $category)
                                            <option value="{{ $category->id }}" @selected(old('unit', $salary->ctg_id) == $category->id)>{{ $category->name }}</option>
                                        @empty
                                            <option value="" disabled>-- Tidak memiliki kategori --</option>
                                        @endforelse
                                    </optgroup>
                                @endforeach
                            </select>
                            @error('ctg_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label required" for="unit">Satuan</label>
                            <select name="unit" id="unit" class="form-select @error('unit') is-invalid @enderror" required>
                                <option value="">-- Pilih --</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->value }}" @selected(old('unit', $salary->unit->value) == $unit->value)>{{ $unit->label() }} ({{ implode(' ', array_filter([$unit->prefix(), $unit->suffix()])) }})</option>
                                @endforeach
                            </select>
                            @error('unit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="d-none mb-3">
                            <label class="form-label" for="operate">Operasi</label>
                            <select name="operate" id="operate" class="form-select @error('operate') is-invalid @enderror">
                                <option value="">-- Pilih --</option>
                                @foreach ($operates as $operate)
                                    <option value="{{ $operate->value }}" @selected(old('operate', $salary->operate?->value) == $operate->value)>{{ $operate->label() }}</option>
                                @endforeach
                            </select>
                            @error('operate')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label required" for="name">Nama komponen</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $salary->name) }}" required>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div>
                            <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Perbarui</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
