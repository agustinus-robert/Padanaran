@extends('portal::layouts.index')

@section('title', 'Kegiatan lainnya | ')

@include('components.tourguide', [
    'steps' => array_values(
        array_filter(
            [
                [
                    'selector' => '.tg-steps-outwork-name',
                    'title' => 'Nama kegiatan',
                    'content' => 'Tulis nama aktivitas/kegiatan yang akan diajukan.',
                ],
                [
                    'selector' => '.tg-steps-outwork-category',
                    'title' => 'Bentuk kegiatan',
                    'content' => 'Pilih salah satu bentuk kegiatan sesuai dengan aktivitas yang Kamu lakukan.',
                ],
                [
                    'selector' => '.tg-steps-outwork-dates',
                    'title' => 'Tanggal dan waktu',
                    'content' => 'Isi juga tanggal dan waktu pelaksanaan kegiatan kamu.',
                ],
                [
                    'selector' => '.tg-steps-outwork-description',
                    'title' => 'Deskripsi',
                    'content' => 'Bisa diisi realisasi kegiatan, catatan, alasan, atau deskripsi penting lainnya kalau ada.',
                ],
                [
                    'selector' => '.tg-steps-outwork-attachment',
                    'title' => 'Lampiran berkas',
                    'content' => 'Kalau ada lampiran bisa diunggah di sini, misalnya surat tugas/pengantar, screenshot atau lainnya.',
                ],
                [
                    'disabled' => count($superiors) == 0,
                    'selector' => '.tg-steps-outwork-approvers',
                    'title' => 'Persetujuan',
                    'content' => 'Pengajuan kegiatan yang kamu buat akan dicek sama atasan yang kamu pilih.',
                ],
            ],
            fn($step) => !($step['disabled'] ?? false))),
])

@section('contents')
    <header id="page-topbar">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box">
                    <a href="index.html" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="{{ asset('skote/images/logo.svg') }}" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ asset('skote/images/logo-dark.png') }}" alt="" height="17">
                        </span>
                    </a>

                    <a href="index.html" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="{{ asset('skote/images/logo-light.svg') }}" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ asset('skote/images/logo-light.png') }}" alt="" height="39">
                        </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm font-size-16 d-lg-none header-item waves-effect waves-light px-3" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                    <i class="fa fa-fw fa-bars"></i>
                </button>

            </div>

            <div class="d-flex">
                @php($user=auth()->user())
                @include('portal::layouts.components.notifications')
                
                @include('layouts.shortcut_menu')

                @include('layouts.nav_name')
                
            </div>
    </header>

    <div class="topnav">
        <div class="container-fluid">
            <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

                <div class="navbar-collapse collapse" id="topnav-menu-content">
                    <ul class="navbar-nav">

                        <li class="nav-item">
                            <a class="nav-link arrow-none" href="{{ route('portal::dashboard-msdm.index') }}" id="topnav-dashboard" role="button">
                                <i class="bx bx-home-circle me-2"></i><span key="t-dashboards">Dashboards</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>

    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <div class="d-flex align-items-center mb-4">
                    <a class="text-decoration-none" href="{{ request('next', route('portal::outwork.submission.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                    <div class="ms-4">
                        <h2 class="mb-1">Pengajuan insentif kegiatan</h2>
                        <div class="text-muted">Jangan lupa isi insentif kegiatan kalau ada kerjaan di luar pekerjaan utama kamu!</div>
                    </div>
                </div>
                <div class="card card-body border-0">
                    <div class="row justify-content-center">
                        <div class="col-xxl-11">
                            <form class="form-confirm form-block" action="{{ route('portal::outwork.submission.store') }}" method="post" enctype="multipart/form-data"> @csrf
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
                                <div class="row prepareable @if (old('prepare') != 1) d-none @endif mb-3">
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
                                                                <input type="date" class="form-control @error('dates.d.0') is-invalid @enderror" name="dates[d][]" max="{{ date('Y-m-d') }}" value="{{ old('dates.d.0') }}" required>
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
                                                                    <input type="number" class="form-control @error('dates.b.0') is-invalid @enderror" name="dates[b][]" min="0" value="{{ old('dates.b.0', 0) }}" required>
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
                                    <label class="col-md-4 col-lg-3 col-form-label">Deskripsi</label>
                                    <div class="col-md-8">
                                        <div class="tg-steps-outwork-description">
                                            <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="4" placeholder="Silakan tulis realisasi kegiatan dan keterangan/alasan/catatan kegiatan kamu. ...">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-4 col-lg-3 col-form-label">Lampiran</label>
                                    <div class="col-md-8">
                                        <div class="tg-steps-outwork-attachment">
                                            <input class="form-control @error('file') is-invalid @enderror" name="file" type="file" id="upload-input" accept="image/*,application/pdf">
                                            <small class="text-muted">Berkas berupa .jpg, .png atau .pdf maksimal berukuran 2mb</small>
                                            @error('file')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <hr class="text-secondary">

                                @foreach ($superiors as $superior)
                                    <div class="row mb-3">
                                        <label class="col-md-4 col-lg-3 col-form-label required">{{ $superior['label'] }}</label>
                                        <div class="col-md-8">
                                            <div class="tg-steps-outwork-approvers">
                                                <select class="@error('approvables.' . $superior['step']) is-invalid @enderror form-select" name="approvables[{{ $superior['step'] }}]" required>
                                                    @if (count($superior['positions']) > 1)
                                                        <option value="">-- Pilih --</option>
                                                    @endif
                                                    @foreach ($superior['positions'] as $position)
                                                        <optgroup label="{{ $position->name }}">
                                                            @forelse ($position->employeePositions as $position)
                                                                <option value="{{ $position->id }}" @selected(count($superior['positions']) == 1)>{{ $position->employee->user->name }}</option>
                                                            @empty
                                                                <option value="" disabled>-</option>
                                                            @endforelse
                                                        </optgroup>
                                                    @endforeach
                                                </select>
                                                @error('approvables.' . $superior['step'])
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="row mb-3 pt-3">
                                    <div class="col-lg-8 offset-lg-4 offset-xl-3">
                                        <button class="btn btn-soft-danger"><i class="mdi mdi-exit-to-app"></i> Ajukan</button>
                                        <a class="btn btn-ghost-light text-dark" href="{{ request('next', route('portal::outwork.submission.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endsection

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
                                        <input type="number" class="form-control" name="dates[b][]" min="0" value="0">
                                        <div class="input-group-text d-xl-none">menit</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary rounded-circle btn-remove px-2 py-1" onclick="removeAttachment(event)"><i class="mdi mdi-minus"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const max_dates = 5;

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
        });
    </script>
@endpush
