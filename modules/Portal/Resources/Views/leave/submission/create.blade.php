@extends('portal::layouts.index')

@section('title', 'Izin | ')

@section('navtitle', 'Perizinan')

@include('components.tourguide', [
    'steps' => array_filter([
        ['selector' => '.tg-steps-leave-category', 'title' => 'Jenis izin', 'content' => 'Pilih jenis izin yang sesuai dengan kebutuhan kamu.'],
        ['selector' => '.tg-steps-leave-date', 'title' => 'Tanggal izin', 'content' => 'Kolom ini diisi tanggal izin yang udah kamu rencanain.'],
        ['selector' => '.tg-steps-leave-description', 'title' => 'Keperluan izin', 'content' => 'Bisa diisi keperluan, catatan, alasan, atau deskripsi penting lainnya.'],
        ['selector' => '.tg-steps-leave-attachment', 'title' => 'Lampiran berkas', 'content' => 'Kalau ada lampiran bisa diunggah di sini, misalnya surat keterangan dokter atau lainnya.'],
    ]),
])

@section('contents')
    @include('layouts.component.material-nav')

    <style>
        .material-symbols-rounded {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
        .category-scroll {
            max-height: 350px;
            overflow-y: auto;
            scrollbar-width: thin;
            padding-right: 5px;
        }
        .category-item {
            transition: all 0.2s ease;
            cursor: pointer;
            border: 1px solid #f0f0f0;
            margin-bottom: 8px;
            border-radius: 12px !important;
            background-color: #fff;
        }
        .category-item:hover {
            background-color: #f8f9fa;
            border-color: #d1d1d1;
        }
        .form-check-input:checked + div .fw-bold {
            color: var(--bs-primary);
        }
        .card-form {
            border-radius: 1.25rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        /* Border Tengah (Vertical Divider) */
        @media (min-width: 992px) {
            .divider-vertical {
                border-right: 1px solid #ebedef;
                padding-right: 2rem;
            }
            .content-right {
                padding-left: 2rem;
            }
        }
        .form-label-custom {
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.025rem;
            color: #344767;
        }
    </style>

    <div class="main-content container-fluid py-4">
        <div class="page-content">
            <div class="container-fluid">
                {{-- Header --}}
                <div class="d-flex align-items-center mb-4">
                    <a href="{{ request('next', route('portal::leave.submission.index')) }}" class="btn btn-link text-dark p-0 me-3">
                        <span class="material-symbols-rounded" style="font-size: 36px;">arrow_back_ios_new</span>
                    </a>
                    <div>
                        <h3 class="font-weight-bolder mb-0 text-dark">Formulir Izin</h3>
                        <p class="text-sm mb-0 text-secondary">Silakan lengkapi data pengajuan izin Anda.</p>
                    </div>
                </div>

                {{-- Alert Errors --}}
                @if (count($errors))
                    <div class="alert alert-danger border-0 shadow-sm alert-dismissible fade show text-white mb-4" role="alert" style="background: #ea0606; border-radius: 12px;">
                        <div class="d-flex align-items-center">
                            <span class="material-symbols-rounded me-2">error</span>
                            <strong class="text-sm">Terjadi kesalahan input:</strong>
                        </div>
                        <ul class="ps-4 mb-0 mt-2 text-xs">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="card card-form border-0">
                    <div class="card-body p-4 p-md-5">
                        <form class="form-confirm form-block" action="{{ route('portal::leave.submission.store') }}" method="post" enctype="multipart/form-data">
                            @csrf

                            {{-- Kategori Izin --}}
                            <div class="row align-items-start mb-4">
                                <div class="col-lg-3 divider-vertical">
                                    <label class="form-label-custom font-weight-bold">
                                        <span class="material-symbols-rounded me-1 text-primary" style="font-size: 20px;">category</span>
                                        Kategori
                                    </label>
                                    <p class="text-xxs text-secondary mt-1 d-none d-lg-block">Pilih salah satu alasan atau kategori izin yang tersedia.</p>
                                </div>
                                <div class="col-lg-9 content-right">
                                    <div class="tg-steps-leave-category">
                                        <div class="category-scroll">
                                            @forelse($categories as $category)
                                                @if ($category->children->count())
                                                    <div class="text-uppercase text-xxs font-weight-bolder text-primary mb-2 mt-2 d-flex align-items-center" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $category->id }}" style="cursor: pointer; letter-spacing: 1px;">
                                                        {{ $category->name }}
                                                        <span class="material-symbols-rounded ms-auto" style="font-size: 16px;">expand_more</span>
                                                    </div>
                                                    <div class="collapse show" id="collapse-{{ $category->id }}">
                                                        @foreach ($category->children as $child)
                                                            <label class="list-group-item category-item d-flex align-items-center p-3">
                                                                <input class="form-check-input me-3" type="radio" name="ctg_id" data-meta="{{ json_encode($child->meta) }}" value="{{ $child->id }}" data-quota="{{ $child->meta?->quota ?: -1 }}">
                                                                <div class="flex-grow-1">
                                                                    <div class="fw-bold text-sm mb-0">{{ $child->name }}</div>
                                                                    <div class="text-xxs text-secondary">Sisa Kuota: <span class="badge bg-light text-dark">{{ $child->meta?->quota ?: '∞' }} hari</span></div>
                                                                </div>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <label class="category-item d-flex align-items-center p-3">
                                                        <input class="form-check-input me-3" type="radio" name="ctg_id" data-meta="{{ json_encode($category->meta) }}" value="{{ $category->id }}" data-quota="{{ $category->meta?->quota ?: -1 }}" required>
                                                        <div class="flex-grow-1">
                                                            <div class="fw-bold text-sm mb-0">{{ $category->name }}</div>
                                                            <div class="text-xxs text-secondary">Sisa Kuota: <span class="badge bg-light text-dark">{{ $category->meta?->quota ?: '∞' }} hari</span></div>
                                                        </div>
                                                    </label>
                                                @endif
                                            @empty
                                                <div class="p-4 text-center border-radius-md bg-light">
                                                    <p class="text-sm text-secondary mb-0">Tidak ada kategori izin</p>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="horizontal dark my-4">

                            {{-- Tanggal Izin --}}
                            <div class="row align-items-start mb-4">
                                <div class="col-lg-3 divider-vertical">
                                    <label class="form-label-custom font-weight-bold">
                                        <span class="material-symbols-rounded me-1 text-primary" style="font-size: 20px;">calendar_today</span>
                                        Tanggal
                                    </label>
                                    <p class="text-xxs text-secondary mt-1 d-none d-lg-block">Tentukan satu atau lebih tanggal rencana izin Anda.</p>
                                </div>
                                <div class="col-lg-9 content-right">
                                    <div class="tg-steps-leave-date">
                                        <div class="inputs-meta-fields" id="inputs-options">
                                            <div id="fields-options-tbody">
                                                {{-- Template Row --}}
                                                <div class="d-flex gap-2 mb-2 align-items-start" id="fields-options-template">
                                                    <div class="flex-grow-1">
                                                        <input type="date" class="form-control border-radius-md" name="dates[]" min="{{ date('Y-m-d') }}" required>
                                                    </div>
                                                    <button class="btn btn-link text-danger btn-delete d-none p-2 mb-0" type="button" onclick="removeRow(event)">
                                                        <span class="material-symbols-rounded">delete</span>
                                                    </button>
                                                </div>
                                            </div>
                                            <button id="fields-options-add" type="button" class="btn btn-outline-primary btn-sm mt-2 d-flex align-items-center gap-1 border-radius-md disabled">
                                                <span class="material-symbols-rounded text-sm">add_circle</span> Tambah Tanggal
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Waktu Detail --}}
                            <div class="row d-none mb-4" id="hide_if_date_only">
                                <div class="col-lg-3 divider-vertical">
                                    <label class="form-label-custom font-weight-bold">
                                        <span class="material-symbols-rounded me-1 text-primary" style="font-size: 20px;">schedule</span>
                                        Pukul
                                    </label>
                                </div>
                                <div class="col-lg-6 content-right">
                                    <div class="input-group shadow-none">
                                        <input type="time" class="form-control border-radius-md" name="time_start">
                                        <span class="input-group-text bg-light hide_if_start_only">s.d.</span>
                                        <input type="time" class="form-control border-radius-md hide_if_start_only" name="time_end">
                                    </div>
                                </div>
                            </div>

                            <hr class="horizontal dark my-4">

                            {{-- Deskripsi --}}
                            <div class="row align-items-start mb-4">
                                <div class="col-lg-3 divider-vertical">
                                    <label class="form-label-custom font-weight-bold">
                                        <span class="material-symbols-rounded me-1 text-primary" style="font-size: 20px;">notes</span>
                                        Keterangan
                                    </label>
                                    <p class="text-xxs text-secondary mt-1 d-none d-lg-block">Tuliskan alasan detail untuk mempercepat proses approval.</p>
                                </div>
                                <div class="col-lg-9 content-right">
                                    <div class="tg-steps-leave-description">
                                        <textarea class="form-control border-radius-md" name="description" rows="3" placeholder="Contoh: Mengantar anak imunisasi atau urusan keluarga mendadak..."></textarea>
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
                                    <p class="text-xxs text-secondary mt-1 d-none d-lg-block">Unggah berkas pendukung jika diperlukan (opsional).</p>
                                </div>
                                <div class="col-lg-9 content-right">
                                    <div class="tg-steps-leave-attachment p-3 border-radius-md" style="border: 2px dashed #e9ecef; background: #fafafa;">
                                        <input class="form-control border-0 bg-transparent" name="attachment" type="file" id="upload-input" accept="image/*,application/pdf">
                                        <div class="mt-1"><span class="text-xxs text-secondary">Format: .JPG, .PNG, .PDF (Maks. 2MB)</span></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="row mt-5">
                                <div class="col-lg-9 offset-lg-3 content-right d-flex gap-2">
                                    <button type="submit" class="btn bg-gradient-primary border-radius-md px-4 d-flex align-items-center gap-2 mb-0">
                                        <span class="material-symbols-rounded">check_circle</span> Kirim Pengajuan
                                    </button>
                                    <a href="{{ request('next', route('portal::leave.submission.index')) }}" class="btn btn-light border-radius-md px-4 mb-0">
                                        Batalkan
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const tbody = document.querySelector('#fields-options-tbody');
        let quota = 0;
        let meta = {}

        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('fields-options-add').addEventListener('click', addRow);
            [].slice.call(document.querySelectorAll('[name="ctg_id"]')).map((e) => {
                e.addEventListener('click', renderFields);
            });
        });

        const renderFields = (e) => {
            if (e.target.dataset.meta) {
                meta = JSON.parse(e.target.dataset.meta);
                quota = JSON.parse(e.target.dataset.quota);
                quota = quota < 0 ? 365 : quota;
                let time_input = meta && meta.time_input

                if (time_input) {
                    Array.from(document.querySelectorAll('.hide_if_start_only')).map((el) => {
                        el.classList.toggle('d-none', meta.time_input == 'start_only');
                    })
                }

                document.querySelector('#hide_if_date_only').classList.toggle('d-none', !(meta && meta.hasOwnProperty('time_input')));

                document.querySelector('[name="time_start"]').required = (time_input ? 'required' : '');
                document.querySelector('[name="time_end"]').required = (time_input == 'start_to_end' ? 'required' : '');

                Array.from(tbody.children).map((el, i) => {
                    if (i > 0) el.remove();
                })

                Array.from(document.querySelectorAll('.inputs-meta-fields')).map((el) => el.classList.add('d-none'));
                document.querySelector(`#inputs-options`).classList.remove('d-none');

                Array.from(document.querySelectorAll('[name="dates[]"]')).map((el) => el.value = '');

                toggleAddButtonBasedQuota();
            }
        }

        const toggleAddButtonBasedQuota = () => {
            const addBtn = document.getElementById('fields-options-add');
            const isAtQuota = tbody.children.length >= quota;
            addBtn.classList.toggle('disabled', isAtQuota);
            addBtn.style.opacity = isAtQuota ? '0.5' : '1';
        }

        const addRow = () => {
            let trTemplate = document.querySelector('#fields-options-template');
            if (tbody.children.length < quota) {
                let newRow = trTemplate.cloneNode(true);
                newRow.removeAttribute('id');
                newRow.querySelector('input').value = '';
                newRow.querySelector('.btn-delete').classList.remove('d-none');
                tbody.appendChild(newRow);
            }
            toggleAddButtonBasedQuota();
        }

        const removeRow = (e) => {
            const row = e.target.closest('.d-flex');
            if(row) row.remove();
            toggleAddButtonBasedQuota();
        }
    </script>
@endpush
