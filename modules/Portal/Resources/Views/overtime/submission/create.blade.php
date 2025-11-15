@extends('portal::layouts.index')

@section('title', 'Lembur | ')

@include('components.tourguide', [
    'steps' => array_values(
        array_filter(
            [
                [
                    'selector' => '.tg-steps-overtime-name',
                    'title' => 'Pekerjaan',
                    'content' => 'Tulis apa pekerjaan yang telah kamu lakukan.',
                ],
                [
                    'selector' => '.tg-steps-overtime-dates',
                    'title' => 'Tanggal dan waktu lembur',
                    'content' => 'Isi juga tanggal dan waktu lembur kamu.',
                ],
                [
                    'selector' => '.tg-steps-overtime-description',
                    'title' => 'Deskripsi',
                    'content' => 'Bisa diisi realisasi lembur, catatan, alasan, atau deskripsi penting lainnya kalau ada.',
                ],
                [
                    'selector' => '.tg-steps-overtime-attachment',
                    'title' => 'Lampiran berkas',
                    'content' => 'Kalau ada lampiran bisa diunggah di sini, misalnya screenshot pekerjaan atau lainnya.',
                ],
                [
                    'disabled' => count($superiors) == 0,
                    'selector' => '.tg-steps-overtime-approvers',
                    'title' => 'Persetujuan',
                    'content' => 'Pengajuan lembur yang kamu buat akan dicek sama atasan yang kamu pilih.',
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
                    <a class="text-decoration-none" href="{{ request('next', route('portal::overtime.submission.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                    <div class="ms-4">
                        <h2 class="mb-1">Pengajuan lembur baru</h2>
                        <div class="text-muted">Jangan lupa isi riwayat lemburmu kalo ada kerjaan di luar jam kerja!</div>
                    </div>
                </div>
                <div class="card card-body border-0">
                    <div class="row justify-content-center">
                        <div class="col-xl-11 col-xxl-9">
                            <form class="form-confirm form-block" action="{{ route('portal::overtime.submission.store') }}" method="post" enctype="multipart/form-data"> @csrf
                                <div class="row mb-3">
                                    <label class="col-md-4 col-lg-3 col-form-label required">Pekerjaan</label>
                                    <div class="col-md-8">
                                        <div class="tg-steps-overtime-name">
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                                            @error('name')
                                                <div class="small text-danger mb-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-4 col-lg-3 col-form-label required">Waktu</label>
                                    <div class="col-md-8">
                                        <div class="tg-steps-overtime-dates" id="dates">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div class="flex-grow-1">
                                                    <div class="row gy-1 me-2">
                                                        <div class="col-sm-5">
                                                            <input type="date" class="form-control @error('dates.d.0') is-invalid @enderror" name="dates[d][]" max="{{ date('Y-m-d') }}" value="{{ old('dates.d.0') }}" required>
                                                        </div>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                                <input type="time" class="form-control @error('dates.s.0') is-invalid @enderror" name="dates[s][]" onchange="changeMinTime(event)" value="{{ old('dates.s.0') }}" required>
                                                                <div class="input-group-text">s.d.</div>
                                                                <input type="time" class="form-control @error('dates.e.0') is-invalid @enderror" name="dates[e][]" onchange="changeMaxTime(event)" value="{{ old('dates.e.0') }}" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-light text-danger rounded-circle btn-add px-2 py-1"><i class="mdi mdi-plus"></i></button>
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
                                        <div class="tg-steps-overtime-description">
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
                                        <div class="tg-steps-overtime-attachment">
                                            <input class="form-control @error('attachment') is-invalid @enderror" name="attachment" type="file" id="upload-input" accept="image/*,application/pdf">
                                            <small class="text-muted">Berkas berupa .jpg, .png atau .pdf maksimal berukuran 2mb</small>
                                            @error('attachment')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <hr class="text-secondary">
                                @foreach ($superiors as $superior)
                                    <div class="row mb-3">
                                        <label class="col-md-4 col-lg-3 col-form-label @if ($superior['required']) required @endif">{{ $superior['label'] }}</label>
                                        <div class="col-md-8">
                                            <div class="tg-steps-overtime-approvers">
                                                <select class="@error('approvables.' . $superior['step']) is-invalid @enderror form-select" name="approvables[{{ $superior['step'] }}]" @if ($superior['required']) required @endif>
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
                                        <a class="btn btn-ghost-light text-dark" href="{{ request('next', route('portal::overtime.submission.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
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
                                <div class="col-sm-5">
                                    <input type="date" class="form-control" name="dates[d][]" max="{{ date('Y-m-d') }}">
                                </div>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input type="time" class="form-control" name="dates[s][]" onchange="changeMinTime(event)">
                                        <div class="input-group-text">s.d.</div>
                                        <input type="time" class="form-control" name="dates[e][]" onchange="changeMaxTime(event)">
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
