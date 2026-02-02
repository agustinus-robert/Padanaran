@extends('portal::layouts.index')

@section('title', 'Kegiatan lainnya | ')

@section('navtitle', 'Insentif')

@include('components.tourguide', [
    'steps' => array_values(
        array_filter(
            [
                ['selector' => '.tg-steps-outwork-name', 'title' => 'Nama kegiatan', 'content' => 'Tulis nama aktivitas/kegiatan yang akan diajukan.'],
                ['selector' => '.tg-steps-outwork-category', 'title' => 'Bentuk kegiatan', 'content' => 'Pilih salah satu bentuk kegiatan sesuai dengan aktivitas yang Kamu lakukan.'],
                ['selector' => '.tg-steps-outwork-dates', 'title' => 'Tanggal dan waktu', 'content' => 'Isi juga tanggal dan waktu pelaksanaan kegiatan kamu.'],
                ['selector' => '.tg-steps-outwork-description', 'title' => 'Deskripsi', 'content' => 'Bisa diisi realisasi kegiatan, catatan, alasan, atau deskripsi penting lainnya kalau ada.'],
                ['selector' => '.tg-steps-outwork-attachment', 'title' => 'Lampiran berkas', 'content' => 'Kalau ada lampiran bisa diunggah di sini, misalnya surat tugas/pengantar, screenshot atau lainnya.'],
                ['disabled' => count($superiors) == 0, 'selector' => '.tg-steps-outwork-approvers', 'title' => 'Persetujuan', 'content' => 'Pengajuan kegiatan yang kamu buat akan dicek sama atasan yang kamu pilih.'],
            ],
            fn($step) => !($step['disabled'] ?? false))),
])

@section('contents')
    @include('layouts.component.material-nav')

    <style>
        .material-symbols-rounded { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; }
        .card-form { border-radius: 1.25rem; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .form-label-custom { font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.025rem; color: #344767; }
        .category-scroll { max-height: 250px; overflow-y: auto; border: 1px solid #ebedef; border-radius: 12px; }
        .category-item { border-bottom: 1px solid #f0f2f5; transition: all 0.2s ease; cursor: pointer; margin-bottom: 0; }
        .category-item:hover { background-color: #f8f9fa; }
        .category-item:last-child { border-bottom: none; }
        @media (min-width: 992px) {
            .divider-vertical { border-right: 1px solid #ebedef; padding-right: 2rem; }
            .content-right { padding-left: 2rem; }
        }
    </style>

    <div class="main-content container-fluid py-4">
        <div class="page-content">
            <div class="container-fluid">
                {{-- Header --}}
                <div class="d-flex align-items-center mb-4 ps-2">
                    <a href="{{ request('next', route('portal::outwork.submission.index')) }}" class="btn btn-link text-dark p-0 me-3">
                        <span class="material-symbols-rounded" style="font-size: 32px;">arrow_back_ios_new</span>
                    </a>
                    <div>
                        <h3 class="font-weight-bolder mb-0">Pengajuan Insentif</h3>
                        <p class="text-sm mb-0 text-secondary">Laporkan kegiatan tambahan Anda untuk perhitungan insentif.</p>
                    </div>
                </div>

                <div class="card card-form border-0">
                    <div class="card-body p-4 p-md-5">
                        <div class="row justify-content-center">
                            <div class="col-xl-11">
                                <form class="form-confirm form-block" action="{{ route('portal::outwork.submission.store') }}" method="post" enctype="multipart/form-data">
                                    @csrf

                                    {{-- Bentuk Kegiatan --}}
                                    <div class="row align-items-start mb-4">
                                        <div class="col-lg-3 divider-vertical">
                                            <label class="form-label-custom font-weight-bold">
                                                <span class="material-symbols-rounded me-1 text-danger" style="font-size: 20px;">category</span>
                                                Bentuk Kegiatan
                                            </label>
                                            <p class="text-xxs text-secondary mt-1 d-none d-lg-block">Pilih kategori yang paling sesuai dengan aktivitas Anda.</p>
                                        </div>
                                        <div class="col-lg-9 content-right">
                                            <div class="tg-steps-outwork-category @error('ctg_id') border border-danger border-radius-md @enderror">
                                                <div class="category-scroll">
                                                    @forelse($categories as $category => $children)
                                                        @if ($children->count())
                                                            <div class="bg-light p-2 ps-3 text-muted text-xxs font-weight-bolder text-uppercase" style="letter-spacing: 1px;">{{ $category }}</div>
                                                            @foreach ($children as $child)
                                                                <label class="category-item d-flex align-items-center p-3 mb-0">
                                                                    <input class="form-check-input mb-0" type="radio" name="ctg_id" data-meta="{{ json_encode($child->meta) }}" onchange="togglePrepareable(event.target)" value="{{ $child->id }}" @selected(old('ctg_id') == $child->id)>
                                                                    <div class="ms-3 text-sm text-dark">{{ ucfirst($child->description) }}</div>
                                                                </label>
                                                            @endforeach
                                                        @endif
                                                    @empty
                                                        <div class="p-3 text-center text-muted text-sm">Tidak ada kategori tersedia</div>
                                                    @endforelse
                                                </div>
                                            </div>
                                            @error('ctg_id') <small class="text-danger ps-1">{{ $message }}</small> @enderror

                                            <div class="prepareable @if (old('prepare') != 1) d-none @endif mt-3 p-3 border-radius-md bg-light">
                                                <div class="form-check mb-0">
                                                    <input class="form-check-input" type="checkbox" name="prepare" id="prepare" value="1" @checked(old('prepare') == 1)>
                                                    <label class="form-check-label text-xs mb-0 font-weight-bold" for="prepare">Aktivitas Persiapan?</label>
                                                    <p class="text-xxs text-secondary mb-0">Centang jika ini merupakan rapat, belanja kebutuhan, atau survey perizinan.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="horizontal dark my-4">

                                    {{-- Nama Kegiatan --}}
                                    <div class="row align-items-start mb-4">
                                        <div class="col-lg-3 divider-vertical">
                                            <label class="form-label-custom font-weight-bold">
                                                <span class="material-symbols-rounded me-1 text-danger" style="font-size: 20px;">edit_note</span>
                                                Nama Kegiatan
                                            </label>
                                        </div>
                                        <div class="col-lg-9 content-right">
                                            <div class="tg-steps-outwork-name">
                                                <input type="text" class="form-control border-radius-md @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Contoh: Panitia PPDB 2024" required>
                                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="horizontal dark my-4">

                                    {{-- Waktu --}}
                                    <div class="row align-items-start mb-4">
                                        <div class="col-lg-3 divider-vertical">
                                            <label class="form-label-custom font-weight-bold">
                                                <span class="material-symbols-rounded me-1 text-danger" style="font-size: 20px;">schedule</span>
                                                Waktu Pelaksanaan
                                            </label>
                                            <p class="text-xxs text-secondary mt-1 d-none d-lg-block">Anda dapat menambahkan maksimal 5 rentang waktu dalam satu pengajuan.</p>
                                        </div>
                                        <div class="col-lg-9 content-right">
                                            <div class="tg-steps-outwork-dates" id="dates">
                                                <div class="date-row position-relative mb-3 p-3 border border-radius-md bg-gray-100 shadow-none">
                                                    <div class="row g-2">
                                                        <div class="col-md-4">
                                                            <label class="text-xxs font-weight-bolder text-uppercase opacity-7 ps-1">Tanggal</label>
                                                            <input type="date" class="form-control form-control-sm" name="dates[d][]" max="{{ date('Y-m-d') }}" value="{{ old('dates.d.0') }}" required>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <label class="text-xxs font-weight-bolder text-uppercase opacity-7 ps-1">Jam (Mulai - Selesai)</label>
                                                            <div class="input-group input-group-sm">
                                                                <input type="time" class="form-control" name="dates[s][]" onchange="changeMinTime(event)" value="{{ old('dates.s.0') }}" required>
                                                                <span class="input-group-text bg-white">s.d.</span>
                                                                <input type="time" class="form-control" name="dates[e][]" value="{{ old('dates.e.0') }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="text-xxs font-weight-bolder text-uppercase opacity-7 ps-1">Istirahat (Menit)</label>
                                                            <input type="number" class="form-control form-control-sm" name="dates[b][]" min="0" value="{{ old('dates.b.0', 0) }}" required>
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn btn-icon-only btn-rounded btn-outline-primary bg-white btn-add position-absolute shadow-sm" style="right: -15px; top: 50%; transform: translateY(-50%); width: 30px; height: 30px;">
                                                        <span class="material-symbols-rounded text-sm">add</span>
                                                    </button>
                                                </div>
                                            </div>
                                            @error('dates.*.*') <small class="text-danger d-block mt-n2">{{ $message }}</small> @enderror
                                        </div>
                                    </div>

                                    <hr class="horizontal dark my-4">

                                    {{-- Deskripsi & Lampiran --}}
                                    <div class="row align-items-start mb-4">
                                        <div class="col-lg-3 divider-vertical">
                                            <label class="form-label-custom font-weight-bold">
                                                <span class="material-symbols-rounded me-1 text-danger" style="font-size: 20px;">description</span>
                                                Keterangan
                                            </label>
                                        </div>
                                        <div class="col-lg-9 content-right">
                                            <div class="tg-steps-outwork-description mb-3">
                                                <textarea class="form-control border-radius-md" name="description" rows="3" placeholder="Tulis rincian atau realisasi kegiatan...">{{ old('description') }}</textarea>
                                            </div>
                                            <div class="tg-steps-outwork-attachment">
                                                <label class="text-xxs font-weight-bolder text-uppercase opacity-7 ps-1 mb-1">Lampiran (Opsional)</label>
                                                <input class="form-control form-control-sm border-radius-md" name="file" type="file" accept="image/*,application/pdf">
                                                <p class="text-xxs text-secondary mt-1 mb-0">* Maks 2MB (.jpg, .png, .pdf)</p>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="horizontal dark my-4">

                                    {{-- Persetujuan --}}
                                    <div class="row align-items-start mb-4">
                                        <div class="col-lg-3 divider-vertical">
                                            <label class="form-label-custom font-weight-bold">
                                                <span class="material-symbols-rounded me-1 text-danger" style="font-size: 20px;">person_search</span>
                                                Persetujuan
                                            </label>
                                        </div>
                                        <div class="col-lg-9 content-right">
                                            @foreach ($superiors as $superior)
                                                <div class="mb-3">
                                                    <label class="text-xxs font-weight-bolder text-uppercase opacity-7 ps-1 mb-1">{{ $superior['label'] }}</label>
                                                    <div class="tg-steps-outwork-approvers">
                                                        <select class="form-select border-radius-md @error('approvables.' . $superior['step']) is-invalid @enderror" name="approvables[{{ $superior['step'] }}]" required>
                                                            @if (count($superior['positions']) > 1)
                                                                <option value="">-- Pilih Atasan --</option>
                                                            @endif
                                                            @foreach ($superior['positions'] as $position)
                                                                <optgroup label="{{ $position->name }}">
                                                                    @foreach ($position->employeePositions as $empPos)
                                                                        <option value="{{ $empPos->id }}" @selected(count($superior['positions']) == 1 || old('approvables.' . $superior['step']) == $empPos->id)>{{ $empPos->employee->user->name }}</option>
                                                                    @endforeach
                                                                </optgroup>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- Action --}}
                                    <div class="row mt-5">
                                        <div class="col-lg-9 offset-lg-3 content-right d-flex gap-2">
                                            <button type="submit" class="btn bg-gradient-danger border-radius-md px-4 d-flex align-items-center gap-2 mb-0">
                                                <span class="material-symbols-rounded">send</span> Ajukan Kegiatan
                                            </button>
                                            <a href="{{ request('next', route('portal::outwork.submission.index')) }}" class="btn btn-outline-secondary border-radius-md px-4 mb-0">Batal</a>
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

    {{-- Template Row untuk JS --}}
    <template id="dates-template">
        <div class="date-row position-relative mb-3 p-3 border border-radius-md bg-gray-100 shadow-none animate__animated animate__fadeIn">
            <div class="row g-2">
                <div class="col-md-4">
                    <label class="text-xxs font-weight-bolder text-uppercase opacity-7 ps-1">Tanggal</label>
                    <input type="date" class="form-control form-control-sm" name="dates[d][]" max="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-5">
                    <label class="text-xxs font-weight-bolder text-uppercase opacity-7 ps-1">Jam (Mulai - Selesai)</label>
                    <div class="input-group input-group-sm">
                        <input type="time" class="form-control" name="dates[s][]" onchange="changeMinTime(event)" required>
                        <span class="input-group-text bg-white">s.d.</span>
                        <input type="time" class="form-control" name="dates[e][]" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="text-xxs font-weight-bolder text-uppercase opacity-7 ps-1">Istirahat (Menit)</label>
                    <input type="number" class="form-control form-control-sm" name="dates[b][]" min="0" value="0" required>
                </div>
            </div>
            <button type="button" class="btn btn-icon-only btn-rounded btn-outline-danger bg-white btn-remove position-absolute shadow-sm" style="right: -15px; top: 50%; transform: translateY(-50%); width: 30px; height: 30px;" onclick="removeDateRow(this)">
                <span class="material-symbols-rounded text-sm">remove</span>
            </button>
        </div>
    </template>
@endsection

@push('scripts')
    <script>
        const MAX_DATES = 5;

        const togglePrepareable = (el) => {
            if (el.dataset.meta) {
                let meta = JSON.parse(el.dataset.meta);
                const container = document.querySelector('.prepareable');
                container.classList.toggle('d-none', !meta.prepareable);
                if (!meta.prepareable) container.querySelector('input').checked = false;
            }
        }

        const changeMinTime = (e) => {
            const row = e.target.closest('.input-group');
            const endTimeInput = row.querySelector('input[name="dates[e][]"]');
            endTimeInput.min = e.target.value;
        }

        const removeDateRow = (btn) => {
            btn.closest('.date-row').remove();
            checkAddButton();
        }

        const checkAddButton = () => {
            const count = document.querySelectorAll('.date-row').length;
            const addBtn = document.querySelector('.btn-add');
            if (addBtn) addBtn.style.display = count >= MAX_DATES ? 'none' : 'flex';
        }

        document.addEventListener('click', function(e) {
            if (e.target.closest('.btn-add')) {
                const datesContainer = document.getElementById('dates');
                const template = document.getElementById('dates-template');

                if (document.querySelectorAll('.date-row').length < MAX_DATES) {
                    const clone = template.content.cloneNode(true);
                    datesContainer.appendChild(clone);
                }
                checkAddButton();
            }
        });
    </script>
@endpush
