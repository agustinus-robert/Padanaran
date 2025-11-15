@extends('core::layouts.default')

@section('title', 'Hari libur | ')
@section('navtitle', 'Hari libur')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-8 col-xl-10">
            <div class="d-flex align-items-center mb-4">
                <a class="text-decoration-none" href="{{ request('next', route('core::company.moments.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                <div class="ms-4">
                    <h2 class="mb-1">Buat hari libur baru</h2>
                    <div class="text-secondary">Silakan isi formulir di bawah ini untuk membuat hari libur baru</div>
                </div>
            </div>
            <div class="card mb-4 border-0">
                <div class="card-body">
                    <form class="form-block" action="{{ route('core::company.moments.store', ['next' => request('next')]) }}" method="POST"> @csrf
                        <div class="row required mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Tipe hari libur</label>
                            <div class="col-xl-8 col-xxl-6">
                                <select class="form-select @error('type') is-invalid @enderror" name="type" required>
                                    @forelse($types as $_type)
                                        @if ($loop->first)
                                            <option value="">-- Pilih tipe hari libur --</option>
                                        @endif
                                        <option value="{{ $_type->value }}" @selected(old('type') == $_type->value)>{{ $_type->label() }}</option>
                                    @empty
                                        <option value="">Tidak ada tipe yang ditetapkan</option>
                                    @endforelse
                                </select>
                                @error('type')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row required mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Nama hari libur</label>
                            <div class="col-xl-8 col-xxl-6">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required />
                                @error('name')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Tanggal</label>
                            <div class="col-xl-6 col-xxl-4">
                                <input type="date" class="form-control @error('date') is-invalid @enderror" name="date" value="{{ old('date') }}" />
                                @error('date')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Tetapkan sebagai libur?</label>
                            <div class="col-xl-6 col-xxl-4 pt-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_holiday" value="1" name="is_holiday" @checked(old('is_holiday', 1) == 1)>
                                    <label class="form-check-label" for="is_holiday"><strong><span id="is_holiday-text">
                                                @if (old('is_holiday', 1) == 1)
                                                    Ya, tetapkan sebagai hari libur
                                                @else
                                                    Tidak, tanggal tersebut tetap hari kerja
                                                @endif
                                            </span></strong></label>
                                </div>
                                @error('date')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Tetapkan tanggal default penghitung THR?</label>
                            <div class="col-xl-6 col-xxl-4 pt-2">
                                @foreach ($religions as $key => $religion)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="religion-{{ $key }}" value="{{ $religion->value }}" name="religion[]" @checked(in_array(old('religion', $religion->value), $moment->meta?->religion ?? []))>
                                        <label class="form-check-label" for="religion-{{ $key }}"><strong>{{ $religion->label() }}</strong></label>
                                    </div>
                                @endforeach
                                @error('religion[]')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-8 offset-lg-4 offset-xl-3">
                                <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                                <a class="btn btn-ghost-light text-dark" href="{{ route('core::company.moments.store', ['next' => request('next')]) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
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
        document.addEventListener("DOMContentLoaded", async () => {
            document.querySelector('#is_holiday').addEventListener('change', (e) => {
                document.querySelector('#is_holiday-text').innerHTML = e.target.checked ? 'Ya, tetapkan sebagai hari libur' : 'Tidak, tanggal tersebut tetap hari kerja'
            });
        });
    </script>
@endpush
