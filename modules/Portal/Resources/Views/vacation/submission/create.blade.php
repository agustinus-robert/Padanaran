@extends('portal::layouts.index')

@section('title', 'Cuti | ')

@section('navtitle', 'Cuti')

@include('components.tourguide', [
    'steps' => array_filter([
        ['selector' => '.tg-steps-vacation-category', 'title' => 'Jenis cuti/libur hari raya', 'content' => 'Pilih jenis cuti atau libur hari raya yang sesuai dengan kebutuhan kamu.'],
        ['selector' => '.tg-steps-vacation-date', 'title' => 'Tanggal cuti', 'content' => 'Kolom ini diisi tanggal cuti yang udah kamu rencanain.'],
        ['selector' => '.tg-steps-vacation-description', 'title' => 'Keperluan cuti', 'content' => 'Bisa diisi keperluan, catatan, alasan, atau deskripsi penting lainnya kalau ada.'],
    ]),
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
        .category-item {
            border: 1px solid #ebedef;
            border-radius: 10px;
            margin-bottom: 8px;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .category-item:hover {
            background-color: #f8f9fa;
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
                <div class="d-flex align-items-center mb-4 ps-2">
                    <a href="{{ request('next', route('portal::vacation.submission.index')) }}" class="btn btn-link text-dark p-0 me-3">
                        <span class="material-symbols-rounded" style="font-size: 32px;">arrow_back_ios_new</span>
                    </a>
                    <div>
                        <h3 class="font-weight-bolder mb-0">Pengajuan Cuti Baru</h3>
                        <p class="text-sm mb-0 text-secondary">Rencanakan liburan atau istirahat Anda dengan mudah.</p>
                    </div>
                </div>

                {{-- Errors --}}
                @if (count($errors))
                    <div class="alert alert-danger border-0 shadow-sm text-white mb-4 border-radius-md" role="alert">
                        <div class="d-flex align-items-center mb-1">
                            <span class="material-symbols-rounded me-2">error</span>
                            <span class="font-weight-bold">Terjadi Kesalahan</span>
                        </div>
                        <ul class="mb-0 text-sm opacity-9 ps-4">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card card-form border-0">
                    <div class="card-body p-4 p-md-5">
                        <div class="row justify-content-center">
                            <div class="col-xl-11">
                                <form class="form-confirm form-block" action="{{ route('portal::vacation.submission.store') }}" method="post">
                                    @csrf

                                    {{-- Jenis Cuti --}}
                                    <div class="row align-items-start mb-4">
                                        <div class="col-lg-3 divider-vertical">
                                            <label class="form-label-custom font-weight-bold">
                                                <span class="material-symbols-rounded me-1 text-danger" style="font-size: 20px;">category</span>
                                                Jenis Cuti
                                            </label>
                                            <p class="text-xxs text-secondary mt-1 d-none d-lg-block">Pilih kategori cuti atau libur hari raya yang tersedia.</p>
                                        </div>
                                        <div class="col-lg-9 content-right">
                                            <div class="tg-steps-vacation-category">
                                                @foreach ($quotas->groupBy(fn($quota) => $quota->category->type->label()) as $type => $_quotas)
                                                    <div class="mb-3">
                                                        <h6 class="text-xs font-weight-bold text-uppercase text-muted mb-2 ps-1 d-flex align-items-center" data-bs-toggle="collapse" data-bs-target="#collapse-{{ Str::slug($type) }}" style="cursor: pointer;">
                                                            {{ $type }} <span class="material-symbols-rounded ms-auto">expand_more</span>
                                                        </h6>
                                                        <div class="collapse {{ $_quotas->first()->category->type->quotaVisibility() == true ? 'show' : '' }}" id="collapse-{{ Str::slug($type) }}">
                                                            @foreach ($_quotas as $quota)
                                                                @php($is_remain = !is_null($quota->quota) && $quota->remain <= 0)
                                                                <label class="category-item d-flex align-items-center p-3 mb-2 {{ $is_remain ? 'opacity-5 bg-light cursor-not-allowed' : '' }}">
                                                                    <div class="form-check mb-0">
                                                                        <input class="form-check-input" type="radio" name="quota_id" data-meta="{{ json_encode($quota->category->meta) }}" value="{{ $quota->id }}" data-quota="{{ !is_null($quota->quota ?? null) ? $quota->remain : -1 }}" data-start="{{ $quota->start_at <= now() ? now()->format('Y-m-d') : $quota->start_at->format('Y-m-d') }}" @if ($is_remain) disabled @endif required>
                                                                    </div>
                                                                    <div class="ms-2 flex-grow-1">
                                                                        <div class="text-sm font-weight-bold text-dark mb-0">{{ $quota->category->name }}</div>
                                                                        <div class="text-xxs text-secondary">
                                                                            Sisa: <span class="font-weight-bold text-primary">{{ is_null($quota->quota) ? '∞' : ($quota->remain <= 0 ? 0 : $quota->remain) }} Hari</span>
                                                                            &bull; Berlaku s.d. {{ $quota->end_at->format('d M Y') }}
                                                                        </div>
                                                                    </div>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="horizontal dark my-4">

                                    {{-- Tanggal Cuti --}}
                                    <div class="row align-items-start mb-4" id="fields-options">
                                        <div class="col-lg-3 divider-vertical">
                                            <label class="form-label-custom font-weight-bold">
                                                <span class="material-symbols-rounded me-1 text-danger" style="font-size: 20px;">calendar_month</span>
                                                Tanggal
                                            </label>
                                            <p class="text-xxs text-secondary mt-1 d-none d-lg-block">Pilih tanggal spesifik atau rentang waktu cuti Anda.</p>
                                        </div>
                                        <div class="col-lg-9 content-right">
                                            {{-- Field Mode: Options (Tambah Satu-satu) --}}
                                            <div class="inputs-meta-fields tg-steps-vacation-date" id="inputs-options">
                                                <div id="fields-options-tbody">
                                                    <div class="d-flex align-items-center mb-3" id="fields-options-template">
                                                        <div class="flex-grow-1">
                                                            <div class="input-group">
                                                                <input type="date" class="form-control border-radius-md" name="dates[]" min="{{ date('Y-m-d') }}">
                                                                <div class="input-group-text bg-light inputs-meta-as_freelances d-none">
                                                                    <div class="form-check mb-0 d-flex align-items-center">
                                                                        <input class="form-check-input mt-0" name="as_freelances[]" type="checkbox" value="1" onchange="toggleCheckbox(event)">
                                                                        <span class="ms-1 text-xxs font-weight-bold text-uppercase">Freelance</span>
                                                                    </div>
                                                                    <input class="form-check-input d-none unchecked mt-0" name="as_freelances[]" type="checkbox" value="0" checked="checked">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button class="btn btn-link text-danger btn-delete d-none mb-0 px-2 py-1" type="button" onclick="removeRow(event)">
                                                            <span class="material-symbols-rounded">delete</span>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div id="inputs-meta-as_freelances-text" class="text-xxs text-muted mb-3 d-none ps-1">
                                                    * Centang kolom <b>Freelance</b> jika Anda tetap bekerja secara remote pada tanggal tersebut.
                                                </div>
                                                <button id="fields-options-add" type="button" class="btn btn-outline-danger btn-sm border-radius-md mb-0 disabled">
                                                    <span class="material-symbols-rounded text-sm">add_circle</span> Tambah Tanggal
                                                </button>
                                            </div>

                                            {{-- Field Mode: Range (Dari s.d.) --}}
                                            <div class="inputs-meta-fields d-none" id="inputs-range">
                                                <div class="input-group shadow-none border-radius-md overflow-hidden" style="border: 1px solid #d2d6da;">
                                                    <input id="inputs-range-from" type="date" class="form-control border-0" onchange="changeMinDateOfRangeEndAt(event)">
                                                    <span class="input-group-text bg-light border-0">s.d.</span>
                                                    <input id="inputs-range-to" type="date" class="form-control border-0" onchange="createDateRange()">
                                                </div>
                                                <div id="inputs-range-dates-group"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="horizontal dark my-4">

                                    {{-- Keperluan --}}
                                    <div class="row align-items-start mb-4">
                                        <div class="col-lg-3 divider-vertical">
                                            <label class="form-label-custom font-weight-bold">
                                                <span class="material-symbols-rounded me-1 text-danger" style="font-size: 20px;">description</span>
                                                Keperluan
                                            </label>
                                            <p class="text-xxs text-secondary mt-1 d-none d-lg-block">Alasan atau keterangan pendukung untuk pengajuan ini.</p>
                                        </div>
                                        <div class="col-lg-9 content-right">
                                            <textarea class="form-control border-radius-md tg-steps-vacation-description" name="description" rows="4" placeholder="Tulis alasan pengambilan cuti secara singkat dan jelas..."></textarea>
                                        </div>
                                    </div>

                                    {{-- Actions --}}
                                    <div class="row mt-5">
                                        <div class="col-lg-9 offset-lg-3 content-right d-flex gap-2">
                                            <button type="submit" class="btn bg-gradient-danger border-radius-md px-4 d-flex align-items-center gap-2 mb-0">
                                                <span class="material-symbols-rounded">send</span> Ajukan Sekarang
                                            </button>
                                            <a href="{{ request('next', route('portal::vacation.submission.index')) }}" class="btn btn-outline-secondary border-radius-md px-4 mb-0">
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
    <script>
        // DOM tetap sama, hanya menyesuaikan ID container jika berubah
        const tbody = document.querySelector('#fields-options-tbody');
        let quota = 0;
        let meta = {}

        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('fields-options-add').addEventListener('click', addRow);
            [].slice.call(document.querySelectorAll('[name="quota_id"]')).map((e) => {
                e.addEventListener('click', renderFields);
            });
        });

        const renderFields = (e) => {
            if (e.target.dataset.meta) {
                meta = JSON.parse(e.target.dataset.meta);
                quota = JSON.parse(e.target.dataset.quota);
                start = e.target.dataset.start;
                quota = quota < 0 ? 365 : quota;

                // Reset Options
                Array.from(tbody.children).map((el, i) => {
                    if (i > 0) el.remove();
                })

                // Reset Range
                Array.from(document.querySelectorAll('.inputs-range-dates')).map((el) => el.remove());
                document.querySelector('#inputs-range-from').value = '';
                document.querySelector('#inputs-range-to').value = '';

                // Required logic
                document.querySelector('#inputs-range-from').required = meta.fields == 'range';
                document.querySelector('#inputs-range-to').required = meta.fields == 'range';
                document.querySelector('#inputs-options input').required = meta.fields == 'options';

                // Visibility logic
                Array.from(document.querySelectorAll('.inputs-meta-fields')).map((el) => el.classList.add('d-none'));
                document.querySelector(`#inputs-${meta.fields}`).classList.remove('d-none');

                Array.from(document.querySelectorAll('.inputs-meta-as_freelances')).map((el) => el.classList.toggle('d-none', !meta.as_freelance));
                document.querySelector('#inputs-meta-as_freelances-text').classList.toggle('d-none', !meta.as_freelance);

                // Reset values
                Array.from(document.querySelectorAll('[name="dates[]"]')).map((el) => el.value = '');
                Array.from(document.querySelectorAll('[name="as_freelances[]"]')).map((el) => el.checked = false);
                Array.from(document.querySelectorAll('[name="as_freelances[]"].unchecked')).map((el) => el.checked = true);

                // limit start
                Array.from(document.querySelectorAll('[name="dates[]"]')).map((el) => el.setAttribute('min', start));

                toggleAddButtonBasedQuota();
            }
        }

        const toggleAddButtonBasedQuota = () => {
            const btn = document.getElementById('fields-options-add');
            const canAdd = tbody.children.length < quota;
            btn.classList.toggle('disabled', !canAdd);
            btn.classList.toggle('opacity-5', !canAdd);
        }

        const addRow = () => {
            if (tbody.children.length < quota) {
                let firstRow = document.querySelector('#fields-options-template');
                let newRow = firstRow.cloneNode(true);

                // Reset values in new row
                newRow.removeAttribute('id');
                newRow.querySelector('input[type="date"]').value = '';
                newRow.querySelector('.btn-delete').classList.remove('d-none');

                // Ensure correct state for as_freelance in new row
                newRow.querySelector('.inputs-meta-as_freelances').classList.toggle('d-none', !meta.as_freelance);

                tbody.appendChild(newRow);
            }
            toggleAddButtonBasedQuota();
        }

        const removeRow = (e) => {
            e.target.closest('.d-flex').remove();
            toggleAddButtonBasedQuota();
        }

        const toggleCheckbox = (el) => {
            let container = el.target.closest('.input-group-text');
            let checkboxes = container.querySelectorAll('[name="as_freelances[]"]');
            checkboxes[1].checked = !checkboxes[0].checked;
        }

        const changeMinDateOfRangeEndAt = (e) => {
            Array.from(document.querySelectorAll('.inputs-range-dates')).map((el) => el.remove());
            let end_at = document.querySelector('#inputs-range-to');
            end_at.value = '';
            end_at.min = e.target.value;

            if (quota >= 0) {
                let max = new Date(e.target.value);
                max.setDate(max.getDate() + (quota - 1));
                end_at.max = max.toISOString().split('T')[0];
            }
        }

        const createDateRange = (e) => {
            let inputs = document.querySelectorAll('.inputs-range-dates');
            Array.from(inputs).map((el) => el.remove());

            let from = document.querySelector('#inputs-range-from').value;
            let to = document.querySelector('#inputs-range-to').value;

            if (from && to) {
                for (dt = new Date(from); dt <= new Date(to); dt.setDate(dt.getDate() + 1)) {
                    let input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'dates[]';
                    input.classList.add('inputs-range-dates');
                    input.value = (new Date(dt)).toISOString().split('T')[0]
                    document.getElementById('inputs-range-dates-group').appendChild(input)
                }
            }
        }
    </script>
@endpush
