@extends('finance::layouts.default')

@section('title', 'Pekerjaan tambahan | ')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('finance::service.outwork.manage.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Pengajuan pekerjaan tambahan baru</h2>
            <div class="text-muted">Jangan lupa isi riwayat pekerjaan tambahan divisi kamu!</div>
        </div>
    </div>
    <div class="card card-body border-0">
        <div class="row justify-content-center">
            <div class="col-md-11 mt-2">
                <form class="form-confirm form-block" action="{{ route('finance::service.outwork.manage.store', ['next' => request('next')]) }}" method="post" enctype="multipart/form-data"> @csrf
                    <div class="row mb-3">
                        <label class="col-lg-4 col-xl-3 col-form-label required">Nama karyawan</label>
                        <div class="col-xl-8 col-xxl-6">
                            <select type="text" class="form-select @error('empl_id') is-invalid @enderror" name="empl_id" value="{{ old('empl_id') }}" required>
                                <option value="">-- Pilih karyawan --</option>
                            </select>
                            @error('empl_id')
                                <small class="text-danger d-block"> {{ $message }} </small>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-md-4 col-lg-3 col-form-label required">Bentuk kegiatan</label>
                        <div class="col-md-8">
                            <div class="card tg-steps-outwork-category @error('ctg_id') border-danger mb-1 @else mb-0 @enderror">
                                <div class="overflow-auto rounded" style="max-height: 300px;">
                                    @forelse($categories as $category => $children)
                                        @if ($children->count())
                                            <div class="card-header border-bottom-0 text-muted small text-uppercase" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $loop->iteration }}" style="cursor: pointer;">{{ $category }} <i class="mdi mdi-chevron-down float-end"></i></div>
                                            <div class="list-group list-group-flush show collapse" id="collapse-{{ $loop->iteration }}">
                                                @foreach ($children as $child)
                                                    <label class="list-group-item d-flex align-items-center">
                                                        <input class="form-check-input me-3" type="radio" name="ctg_id" data-meta="{{ json_encode($child->meta) }}" onchange="togglePrepareable(event.target)" value="{{ $child->id }}">
                                                        <div class="flex-grow-1">
                                                            <div>{{ ucfirst($child->description) }}</div>
                                                        </div>
                                                    </label>
                                                @endforeach
                                            </div>
                                        @else
                                            <label class="card-body border-secondary d-flex align-items-center @if (!$loop->last) border-bottom @endif py-2">
                                                <input class="form-check-input me-3" type="radio" name="ctg_id" data-meta="{{ json_encode($category->meta) }}" value="{{ $category->id }}" onchange="togglePrepareable(event.target)" required>
                                                <div>{{ ucfirst($child->description) }}</div>
                                            </label>
                                        @endif
                                    @empty
                                        <div class="card-body text-muted">Tidak ada kategori izin</div>
                                    @endforelse
                                </div>
                            </div>
                            @error('ctg_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="row prepareable mb-3">
                        <label class="col-md-4 col-lg-3 col-form-label">Aktivitas persiapan?</label>
                        <div class="col-md-8 pt-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="prepare" id="prepare" value="1" @if (old('prepare') == 1) checked @endif>
                                <label class="form-check-label" for="prepare">Centang jika kegiatan Kamu merupakan aktivitas persiapan, misalnya rapat, belanja, survey dan/atau mengurus perizinan</label>
                            </div>
                            @error('prepare')
                                <div class="small text-danger mb-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-md-4 col-lg-3 col-form-label required">Nama kegiatan</label>
                        <div class="col-md-8">
                            <div class="tg-steps-outwork-name">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="small text-danger mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-md-4 col-lg-3 col-form-label required pt-xl-0">Waktu</label>
                        <div class="col-md-8">
                            <div class="tg-steps-outwork-dates">
                                <div class="row gy-1 d-none d-md-flex me-2">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-6"></div>
                                    <div class="col-md-2"></div>
                                </div>
                                <div id="dates">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="flex-grow-1">
                                            <div class="row gy-1 me-2">
                                                <div class="col-xl-4">
                                                    <p class="text-muted d-none d-xl-block mb-2">Tanggal</p>
                                                    <input type="date" class="form-control @error('dates.d.0') is-invalid @enderror" name="dates[d][]" min="{{ date('Y-m-d', strtotime($limit_at)) }}" max="{{ date('Y-m-d') }}" value="{{ old('dates.d.0') }}" required>
                                                </div>
                                                <div class="col-xl-5">
                                                    <p class="text-muted d-none d-xl-block mb-2">Waktu</p>
                                                    <div class="input-group">
                                                        <input type="time" class="form-control @error('dates.s.0') is-invalid @enderror" name="dates[s][]" onchange="changeMinTime(event)" value="{{ old('dates.s.0') }}" required>
                                                        <div class="input-group-text">s.d.</div>
                                                        <input type="time" class="form-control @error('dates.e.0') is-invalid @enderror" name="dates[e][]" onchange="changeMaxTime(event)" value="{{ old('dates.e.0') }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3">
                                                    <p class="text-muted d-none d-xl-block mb-2">Istirahat <small>(menit)</small></p>
                                                    <div class="input-group">
                                                        <div class="input-group-text d-xl-none">Istirahat</div>
                                                        <input type="number" class="form-control @error('dates.b.0') is-invalid @enderror" name="dates[b][]" min="0" value="{{ old('dates.b.0') }}" required>
                                                        <div class="input-group-text d-xl-none">menit</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-muted d-none d-md-block mb-2">&nbsp;</p>
                                            <button type="button" class="btn btn-light text-danger rounded-circle btn-add px-2 py-1"><i class="mdi mdi-plus"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @error('dates.*.*')
                                <div class="small text-danger mb-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-4 col-xl-3 col-form-label">Deskripsi</label>
                        <div class="col-xl-8 col-xxl-6">
                            <div class="tg-steps-overtime-description">
                                <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="4" placeholder="Silakan tulis realisasi kegiatan dan keterangan/alasan/catatan kegiatan kamu. ...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-4 col-xl-3 col-form-label">Lampiran</label>
                        <div class="col-xl-8 col-xxl-6">
                            <div class="tg-steps-overtime-attachment">
                                <input class="form-control @error('attachment') is-invalid @enderror" name="attachment" type="file" id="upload-input" accept="image/*,application/pdf">
                                <small class="text-muted">Berkas berupa .jpg, .png atau .pdf maksimal berukuran 2mb</small>
                                @error('attachment')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 pt-3">
                        <div class="col-lg-8 offset-lg-4 offset-xl-3">
                            <button class="btn btn-soft-danger"><i class="mdi mdi-exit-to-app"></i> Simpan</button>
                            <a class="btn btn-ghost-light text-dark" href="{{ request('next', route('finance::service.outwork.manage.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/tom-select/css/tom-select.bootstrap5.min.css') }}">
@endpush

@push('scripts')
    <div id="dates-template" class="d-none">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="flex-grow-1 dates-input">
                <div class="row gy-1 me-2">
                    <div class="col-xl-4">
                        <input type="date" class="form-control" name="dates[d][]" max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-xl-5">
                        <div class="input-group">
                            <input type="time" class="form-control" name="dates[s][]" onchange="changeMinTime(event)">
                            <div class="input-group-text">s.d.</div>
                            <input type="time" class="form-control" name="dates[e][]" onchange="changeMaxTime(event)">
                        </div>
                    </div>
                    <div class="col-xl-3">
                        <div class="input-group">
                            <div class="input-group-text d-xl-none">Istirahat</div>
                            <input type="number" class="form-control" name="dates[b][]" min="0">
                            <div class="input-group-text d-xl-none">menit</div>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-secondary rounded-circle btn-remove px-2 py-1" onclick="removeAttachment(event)"><i class="mdi mdi-minus"></i></button>
        </div>
    </div>

    <script>
        const max_dates = 5;

        const renderTomSelect = () => {
            new TomSelect('[name="empl_id"]', {
                valueField: 'id',
                labelField: 'text',
                searchField: 'text',
                load: function(q, callback) {
                    fetch("{{ route('api::hrms.employees.search') }}?q=" + encodeURIComponent(q))
                        .then(response => response.json())
                        .then(json => {
                            callback(json.employees);
                        }).catch(() => {
                            callback();
                        });
                }
            });
        }

        const togglePrepareable = (el) => {
            if (el.dataset.meta) {
                let meta = JSON.parse(el.dataset.meta)
                document.querySelector('.prepareable').classList.toggle('d-none', !meta.prepareable);
                if (!meta.prepareable) {
                    document.querySelector('.prepareable input').checked = false;
                }
            }
        }

        let removeAttachment = (e) => {
            e.currentTarget.parentNode.remove();
            document.querySelector('#dates .btn-add').classList.toggle('disabled', document.getElementById('dates').querySelectorAll('.dates-input').length > max_dates);
        }

        let changeMinTime = (e) => {
            for (let sibling of e.target.parentNode.children) {
                if (sibling !== e.target) sibling.min = e.target.value;
            }
        }

        let changeMaxTime = (e) => {
            for (let sibling of e.target.parentNode.children) {
                if (sibling !== e.target) sibling.max = e.target.value;
            }
        }

        window.addEventListener('DOMContentLoaded', () => {
            document.querySelector('#dates .btn-add').addEventListener('click', (e) => {
                if (document.getElementById('dates').querySelectorAll('.dates-input').length < max_dates) {
                    document.getElementById('dates').insertAdjacentHTML('beforeend', document.getElementById('dates-template').innerHTML);
                    e.currentTarget.classList.toggle('disabled', document.getElementById('dates').querySelectorAll('.dates-input').length == max_dates)
                }
            });
            renderTomSelect();
        });
    </script>
@endpush
