@extends('portal::layouts.index')

@section('title', 'Lembur | ')

@section('navtitle', 'Lembur')

@include('components.tourguide', [
    'steps' => array_values(
        array_filter(
            [
                ['selector' => '.tg-steps-overtime-name', 'title' => 'Pekerjaan', 'content' => 'Tulis apa pekerjaan yang telah kamu lakukan.'],
                ['selector' => '.tg-steps-overtime-dates', 'title' => 'Tanggal dan waktu lembur', 'content' => 'Isi juga tanggal dan waktu lembur kamu.'],
                ['selector' => '.tg-steps-overtime-description', 'title' => 'Deskripsi', 'content' => 'Bisa diisi realisasi lembur, catatan, alasan, atau deskripsi penting lainnya kalau ada.'],
                ['selector' => '.tg-steps-overtime-attachment', 'title' => 'Lampiran berkas', 'content' => 'Kalau ada lampiran bisa diunggah di sini, misalnya screenshot pekerjaan atau lainnya.'],
                ['disabled' => count($superiors) == 0, 'selector' => '.tg-steps-overtime-approvers', 'title' => 'Persetujuan', 'content' => 'Pengajuan lembur yang kamu buat akan dicek sama atasan yang kamu pilih.'],
            ],
            fn($step) => !($step['disabled'] ?? false))),
])

@section('contents')
    @include('layouts.component.material-nav')

    <style>
        .material-symbols-rounded {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
        .card-form {
            border-radius: 1.25rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .form-label-custom {
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.025rem;
            color: #344767;
        }
        @media (min-width: 992px) {
            .divider-vertical {
                border-right: 1px solid #ebedef;
                padding-right: 2rem;
            }
            .content-right {
                padding-left: 2rem;
            }
        }
    </style>

    <div class="main-content container-fluid py-4">
        <div class="page-content">
            <div class="container-fluid">

                {{-- Header --}}
                <div class="d-flex align-items-center mb-4">
                    <a href="{{ request('next', route('portal::overtime.submission.index')) }}" class="btn btn-link text-dark p-0 me-3">
                        <span class="material-symbols-rounded" style="font-size: 36px;">arrow_back_ios_new</span>
                    </a>
                    <div>
                        <h3 class="font-weight-bolder mb-0">Pengajuan Lembur Baru</h3>
                        <p class="text-sm mb-0 text-secondary">Isi rincian pekerjaan lembur Anda di bawah ini.</p>
                    </div>
                </div>

                <div class="card card-form border-0">
                    <div class="card-body p-4 p-md-5">
                        <div class="row justify-content-center">
                            <div class="col-xl-11">
                                <form class="form-confirm form-block" action="{{ route('portal::overtime.submission.store') }}" method="post" enctype="multipart/form-data">
                                    @csrf

                                    {{-- Nama Pekerjaan --}}
                                    <div class="row align-items-start mb-4">
                                        <div class="col-lg-3 divider-vertical">
                                            <label class="form-label-custom font-weight-bold">
                                                <span class="material-symbols-rounded me-1 text-primary" style="font-size: 20px;">work</span>
                                                Pekerjaan
                                            </label>
                                            <p class="text-xxs text-secondary mt-1 d-none d-lg-block">Apa tugas utama yang Anda kerjakan saat lembur?</p>
                                        </div>
                                        <div class="col-lg-9 content-right">
                                            <div class="tg-steps-overtime-name">
                                                <input type="text" class="form-control border-radius-md @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Contoh: Maintenance Server atau Input Data Siswa" required>
                                                @error('name') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="horizontal dark my-4">

                                    {{-- Waktu Lembur --}}
                                    <div class="row align-items-start mb-4">
                                        <div class="col-lg-3 divider-vertical">
                                            <label class="form-label-custom font-weight-bold">
                                                <span class="material-symbols-rounded me-1 text-primary" style="font-size: 20px;">schedule</span>
                                                Waktu
                                            </label>
                                            <p class="text-xxs text-secondary mt-1 d-none d-lg-block">Tentukan tanggal dan durasi jam lembur Anda.</p>
                                        </div>
                                        <div class="col-lg-9 content-right">
                                            <div class="tg-steps-overtime-dates" id="dates">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <div class="flex-grow-1 dates-input">
                                                        <div class="row gy-2 me-2">
                                                            <div class="col-sm-5">
                                                                <input type="date" class="form-control border-radius-md @error('dates.d.0') is-invalid @enderror" name="dates[d][]" max="{{ date('Y-m-d') }}" value="{{ old('dates.d.0') }}" required>
                                                            </div>
                                                            <div class="col-sm-7">
                                                                <div class="input-group">
                                                                    <input type="time" class="form-control @error('dates.s.0') is-invalid @enderror" name="dates[s][]" onchange="changeMinTime(event)" value="{{ old('dates.s.0') }}" required>
                                                                    <span class="input-group-text bg-light">s.d.</span>
                                                                    <input type="time" class="form-control @error('dates.e.0') is-invalid @enderror" name="dates[e][]" onchange="changeMaxTime(event)" value="{{ old('dates.e.0') }}" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn btn-outline-primary rounded-circle btn-add px-2 py-1"><span class="material-symbols-rounded">add</span></button>
                                                </div>
                                            </div>
                                            @error('dates.*.*') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    <hr class="horizontal dark my-4">

                                    {{-- Deskripsi --}}
                                    <div class="row align-items-start mb-4">
                                        <div class="col-lg-3 divider-vertical">
                                            <label class="form-label-custom font-weight-bold">
                                                <span class="material-symbols-rounded me-1 text-primary" style="font-size: 20px;">notes</span>
                                                Deskripsi
                                            </label>
                                            <p class="text-xxs text-secondary mt-1 d-none d-lg-block">Detail realisasi kegiatan atau alasan lembur.</p>
                                        </div>
                                        <div class="col-lg-9 content-right">
                                            <div class="tg-steps-overtime-description">
                                                <textarea class="form-control border-radius-md @error('description') is-invalid @enderror" name="description" rows="4" placeholder="Tulis rincian hasil pekerjaan lembur di sini...">{{ old('description') }}</textarea>
                                                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Lampiran --}}
                                    <div class="row align-items-start mb-4">
                                        <div class="col-lg-3 divider-vertical">
                                            <label class="form-label-custom font-weight-bold">
                                                <span class="material-symbols-rounded me-1 text-primary" style="font-size: 20px;">upload_file</span>
                                                Lampiran
                                            </label>
                                        </div>
                                        <div class="col-lg-9 content-right">
                                            <div class="tg-steps-overtime-attachment p-3 border-radius-md" style="border: 2px dashed #e9ecef; background: #fafafa;">
                                                <input class="form-control border-0 bg-transparent @error('attachment') is-invalid @enderror" name="attachment" type="file" id="upload-input" accept="image/*,application/pdf">
                                                <div class="mt-1"><span class="text-xxs text-secondary">Format: .jpg, .png, .pdf (Maks. 2MB)</span></div>
                                                @error('attachment') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="horizontal dark my-4">

                                    {{-- Approvers --}}
                                    @foreach ($superiors as $superior)
                                        <div class="row align-items-start mb-4">
                                            <div class="col-lg-3 divider-vertical">
                                                <label class="form-label-custom font-weight-bold">
                                                    <span class="material-symbols-rounded me-1 text-primary" style="font-size: 20px;">person_search</span>
                                                    {{ $superior['label'] }}
                                                </label>
                                            </div>
                                            <div class="col-lg-9 content-right">
                                                <div class="tg-steps-overtime-approvers">
                                                    <select class="form-select border-radius-md @error('approvables.' . $superior['step']) is-invalid @enderror" name="approvables[{{ $superior['step'] }}]" @if ($superior['required']) required @endif>
                                                        @if (count($superior['positions']) > 1)
                                                            <option value="">-- Pilih Atasan --</option>
                                                        @endif
                                                        @foreach ($superior['positions'] as $position)
                                                            <optgroup label="{{ $position->name }}">
                                                                @forelse ($position->employeePositions as $pEmp)
                                                                    <option value="{{ $pEmp->id }}" @selected(count($superior['positions']) == 1)>{{ $pEmp->employee->user->name }}</option>
                                                                @empty
                                                                    <option value="" disabled>Tidak ada karyawan</option>
                                                                @endforelse
                                                            </optgroup>
                                                        @endforeach
                                                    </select>
                                                    @error('approvables.' . $superior['step']) <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    {{-- Actions --}}
                                    <div class="row mt-5">
                                        <div class="col-lg-9 offset-lg-3 content-right d-flex gap-2">
                                            <button type="submit" class="btn bg-gradient-primary border-radius-md px-4 d-flex align-items-center gap-2 mb-0">
                                                <span class="material-symbols-rounded">send</span> Ajukan Lembur
                                            </button>
                                            <a href="{{ request('next', route('portal::overtime.submission.index')) }}" class="btn btn-outline-secondary border-radius-md px-4 mb-0">
                                                Batal
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Template untuk Add Row (Disesuaikan style-nya tanpa ubah JS) --}}
    <div id="dates-template" class="d-none">
        <div class="d-flex justify-content-between align-items-start mb-2 mt-2">
            <div class="flex-grow-1 dates-input">
                <div class="row gy-2 me-2">
                    <div class="col-sm-5">
                        <input type="date" class="form-control border-radius-md" name="dates[d][]" max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-sm-7">
                        <div class="input-group">
                            <input type="time" class="form-control" name="dates[s][]" onchange="changeMinTime(event)">
                            <span class="input-group-text bg-light">s.d.</span>
                            <input type="time" class="form-control" name="dates[e][]" onchange="changeMaxTime(event)">
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-outline-danger rounded-circle btn-remove px-2 py-1" onclick="removeAttachment(event)">
                <span class="material-symbols-rounded">remove</span>
            </button>
        </div>
    </div>

    <script>
        const max_dates = 5;

        let removeAttachment = (e) => {
            // Updated: find parent d-flex to remove
            e.currentTarget.closest('.d-flex').remove();
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
