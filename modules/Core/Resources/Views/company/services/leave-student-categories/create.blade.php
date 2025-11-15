@extends('core::layouts.default')

@section('title', 'Kategori izin | ')
@section('navtitle', 'Kategori izin')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-8 col-xl-10">
            <div class="d-flex align-items-center mb-4">
                <a class="text-decoration-none" href="{{ request('next', route('core::company.services.leave-student-categories.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                <div class="ms-4">
                    <h2 class="mb-1">Buat kategori izin baru</h2>
                    <div class="text-secondary">Silakan isi formulir di bawah ini untuk membuat kategori izin baru</div>
                </div>
            </div>
            <div class="card mb-4 border-0">
                <div class="card-body">
                    <form class="form-block" action="{{ route('core::company.services.leave-student-categories.store', ['next' => request('next')]) }}" method="POST"> @csrf
                        <div class="row required mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Nama kategori izin</label>
                            <div class="col-xl-8 col-xxl-6">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required />
                                @error('name')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Kategori parent</label>
                            <div class="col-xl-8 col-xxl-6">
                                <select class="@error('parent_id') is-invalid @enderror form-select" name="parent_id" value="{{ old('parent_id') }}">
                                    @forelse($categories as $_category)
                                        @if ($loop->first)
                                            <option value="">Tanpa parent</option>
                                        @endif
                                        <option value="{{ $_category->id }}" @if (old('parent_id') == $_category->id) selected @endif>{{ $_category->name }}</option>
                                    @empty
                                        <option value="">Tidak ada parent</option>
                                    @endforelse
                                </select>
                                @error('parent_id')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Kuota</label>
                            <div class="col-xl-6 col-xxl-4">
                                <div class="input-group">
                                    <input type="number" class="form-control @error('quota') is-invalid @enderror" name="quota" value="{{ old('quota') }}" max="366" />
                                    <div class="input-group-text">hari</div>
                                </div>
                                <small class="text-muted">Kosongkan jika tidak ada batasan untuk kuota</small>
                                @error('quota')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-lg-3">Inputan waktu</label>
                            <div class="col-md-4">
                                @foreach (['start_to_end' => 'Menampilkan jam mulai izin dan jam izin akhir', 'start_only' => 'Hanya menampilkan jam mulai izin'] as $v => $description)
                                    <div class="form-check">
                                        <input class="form-check-input only_one" type="checkbox" id="time_input_{{ $v }}" value="{{ $v }}" name="time_input" @if (old('time_input') == $v) checked @endif>
                                        <label class="form-check-label" for="time_input_{{ $v }}"><code>{{ $v }}</code> <br> <small class="text-muted">{{ $description }}</small></label>
                                    </div>
                                @endforeach
                                @error('time_input')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-8 offset-lg-4 offset-xl-3">
                                <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                                <a class="btn btn-ghost-light text-dark" href="{{ route('core::company.services.leave-student-categories.store', ['next' => request('next')]) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
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
        document.addEventListener('DOMContentLoaded', () => {
            Array.from(document.querySelectorAll('.only_one')).map((el) => {
                el.addEventListener('change', toggleOnlyOneCheckbox)
            })
        })

        const toggleOnlyOneCheckbox = (e) => {
            Array.from(document.querySelectorAll('.only_one')).map((el) => {
                if (e.target.value != el.value) {
                    el.checked = false;
                }
            });
        }
    </script>
@endpush
