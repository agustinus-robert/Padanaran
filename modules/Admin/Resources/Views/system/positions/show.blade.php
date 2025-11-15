@extends('admin::layouts.default')

@section('title', 'Jabatan | ')
@section('navtitle', 'Jabatan')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('admin::system.positions.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Ubah jabatan</h2>
            <div class="text-secondary">Silakan isi formulir di bawah ini untuk memperbarui informasi jabatan {{ $position->name }}</div>
        </div>
    </div>
    <div class="card mb-4 border-0">
        <div class="card-body">
            <form class="form-block" action="{{ route('admin::system.positions.update', ['position' => $position->id, 'next' => request('next')]) }}" method="POST"> @csrf @method('PUT')
                <div class="row justify-content-center">
                    <div class="col-xxl-5 col-xl-6">
                        <div class="row mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Departemen</label>
                            <div class="col-xl-8 col-xxl-6">
                                <select class="form-select @error('dept_id') is-invalid @enderror" name="dept_id">
                                    <option value="">-- Pilih --</option>
                                    @foreach ($departments as $_department)
                                        <option value="{{ $_department->id }}" @if (old('dept_id', $position->dept_id) == $_department->id) selected @endif>{{ $_department->name }}</option>
                                    @endforeach
                                </select>
                                @error('dept_id')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row required mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Kode jabatan</label>
                            <div class="col-xl-8 col-xxl-4">
                                <input type="text" class="form-control @error('kd') is-invalid @enderror" name="kd" value="{{ old('kd', $position->kd) }}" required />
                                @error('kd')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row required mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Nama jabatan</label>
                            <div class="col-xl-8 col-xxl-6">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $position->name) }}" required />
                                @error('name')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Deskripsi</label>
                            <div class="col-lg-8">
                                <textarea type="text" class="form-control @error('description') is-invalid @enderror" name="description">{{ old('description', $position->description) }}</textarea>
                                @error('description')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Peran bawaan</label>
                            <div class="col-xl-8 col-xxl-6">
                                <select class="form-select @error('default_applied_role') is-invalid @enderror" name="default_applied_role">
                                    <option value="">-- Pilih --</option>
                                    @foreach ($roles as $_role)
                                        <option value="{{ $_role->id }}" @selected(old('default_applied_role', $position->getMeta('default_applied_role')?->id) == $_role->id)>{{ $_role->name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted d-block mt-1">Peran ini (termasuk hak akses) diterapkan ke pengguna yang menggunakan jabatan ini</small>
                                @error('default_applied_role')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="required row mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Visibilitas</label>
                            <div class="col-lg-8">
                                <div class="btn-group">
                                    <input class="btn-check" type="radio" id="is_visible1" name="is_visible" value="1" required autocomplete="off" @if (!is_null(old('is_visible', $position->is_visible)) && old('is_visible', $position->is_visible) == 1) checked @endif required />
                                    <label class="btn btn-outline-light text-dark" for="is_visible1"><i class="mdi mdi-eye-outline"></i></label>
                                    <input class="btn-check" type="radio" id="is_visible0" name="is_visible" value="0" required autocomplete="off" @if (!is_null(old('is_visible', $position->is_visible)) && old('is_visible', $position->is_visible) == 0) checked @endif required />
                                    <label class="btn btn-outline-light text-dark" for="is_visible0"><i class="mdi mdi-eye-off-outline"></i></label>
                                </div>
                                @error('description')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-7 col-xl-6">
                        <div class="row mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Atasan</label>
                            <div class="col-xl-8 col-xxl-6">
                                <select class="form-select @error('parents') is-invalid @enderror" name="parents[]" style="height: 240px;" multiple>
                                    @foreach ($positions as $department => $_positions)
                                        @if ($loop->first)
                                            <option value="">-- Pilih --</option>
                                        @endif
                                        <optgroup label="{{ $department ?: 'Lainnya' }}">
                                            @forelse($_positions as $_position)
                                                <option value="{{ $_position->id }}" @if (in_array($_position->id, old('parents', $position->parents->pluck('id')->toArray()))) selected @endif @if ($_position->id == $position->id) disabled @endif>{{ $_position->name }}</option>
                                            @empty
                                                <option value="">Tidak ada jabatan</option>
                                            @endforelse
                                        </optgroup>
                                    @endforeach
                                </select>
                                <small class="text-muted d-block mt-1">Tekan dan tahan <code>ctrl</code> untuk menghapus atau memilih lebih dari dua</small>
                                @error('parents')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Bawahan</label>
                            <div class="col-xl-8 col-xxl-6">
                                <select class="form-select @error('children.0') is-invalid @enderror" name="children[]" style="height: 240px;" multiple>
                                    @foreach ($positions as $department => $_positions)
                                        @if ($loop->first)
                                            <option value="">-- Pilih --</option>
                                        @endif
                                        <optgroup label="{{ $department ?: 'Lainnya' }}">
                                            @forelse($_positions as $_position)
                                                <option value="{{ $_position->id }}" @if (in_array($_position->id, old('children', $position->children->pluck('id')->toArray()))) selected @endif @if ($_position->id == $position->id) disabled @endif>{{ $_position->name }}</option>
                                            @empty
                                                <option value="">Tidak ada jabatan</option>
                                            @endforelse
                                        </optgroup>
                                    @endforeach
                                </select>
                                <small class="text-muted d-block mt-1">Tekan dan tahan <code>ctrl</code> untuk menghapus atau memilih lebih dari dua</small>
                                @error('children.0')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="required row mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Tingkat</label>
                            <div class="col-xl-8 col-xxl-6">
                                <div class="input-group">
                                    <input type="number" class="form-control @error('level') is-invalid @enderror" name="level" value="{{ old('level', $position->level->value) }}" max="10" required />
                                    <span class="input-group-append input-group-text" id="level-desc"></span>
                                </div>
                                @error('level')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-8 offset-lg-4 offset-xl-3">
                                <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                                <a class="btn btn-ghost-light text-dark" href="{{ route('admin::system.positions.store', ['next' => request('next')]) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const positions = {!! $positions->flatten()->pluck('name', 'level.value') !!}
        const setLevelDesc = (e) => {
            let level = document.querySelector('[name="level"]').value;
            let x = Object.keys(positions).indexOf(level) == -1
            document.getElementById('level-desc').innerHTML = (x ? 'Tidak setara dengan apapun' : ('Setara ' + positions[level]))
        }

        window.addEventListener('DOMContentLoaded', () => {
            ['keyup', 'change'].forEach((event) => {
                document.querySelector('[name="level"]').addEventListener(event, (e) => setLevelDesc(e))
            })

            setLevelDesc();
        });
    </script>
@endpush
