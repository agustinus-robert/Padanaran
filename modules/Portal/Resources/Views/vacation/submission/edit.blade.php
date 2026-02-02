@extends('portal::layouts.index')

@section('title', 'Cuti | ')

@section('navtitle', 'Cuti')

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
        .category-item-static {
            border: 1px solid #ebedef;
            border-radius: 10px;
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
                        <h3 class="font-weight-bolder mb-0">Ubah Pengajuan Cuti</h3>
                        <p class="text-sm mb-0 text-secondary">Silakan perbaiki data pengajuan Anda sesuai arahan atasan.</p>
                    </div>
                </div>

                {{-- Errors --}}
                @error('dates.*')
                    <div class="alert alert-danger border-0 text-white shadow-sm mb-4 border-radius-md" role="alert">
                        <div class="d-flex align-items-center">
                            <span class="material-symbols-rounded me-2">error</span>
                            <span class="text-sm">{{ $message }}</span>
                        </div>
                    </div>
                @enderror

                <div class="card card-form border-0">
                    <div class="card-body p-4 p-md-5">
                        <div class="row justify-content-center">
                            <div class="col-xl-11">
                                <form class="form-confirm form-block" action="{{ route('portal::vacation.submission.update', ['vacation' => $vacation->id]) }}" method="post">
                                    @csrf @method('PUT')

                                    {{-- Jenis Cuti (Readonly) --}}
                                    <div class="row align-items-start mb-4">
                                        <div class="col-lg-3 divider-vertical">
                                            <label class="form-label-custom font-weight-bold">
                                                <span class="material-symbols-rounded me-1 text-danger" style="font-size: 20px;">category</span>
                                                Jenis Cuti
                                            </label>
                                        </div>
                                        <div class="col-lg-9 content-right">
                                            <div class="category-item-static d-flex align-items-center p-3">
                                                <div class="form-check mb-0">
                                                    <input class="form-check-input" type="radio" checked disabled>
                                                </div>
                                                <div class="ms-2 flex-grow-1">
                                                    <div class="text-sm font-weight-bold text-dark mb-0">{{ $vacation->quota->category->name }}</div>
                                                    <div class="text-xxs text-secondary">
                                                        Kategori: <span class="font-weight-bold text-uppercase">{{ $vacation->quota->category->type->label() }}</span>
                                                        @php($remain = $vacation->quota->quota - $vacation->quota->vacations->sum(fn($v) => $v->dates->count()) + $vacation->dates->count())
                                                        &bull; Sisa Kuota: <span class="font-weight-bold">{{ is_null($vacation->quota->quota) ? '∞' : ($remain <= 0 ? 0 : $remain) }} Hari</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" data-meta="{{ json_encode($vacation->quota->category->meta) }}" data-quota="{{ !is_null($vacation->quota->quota ?? null) ? $remain : -1 }}">
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
                                            <p class="text-xxs text-secondary mt-1 d-none d-lg-block">Sesuaikan kembali tanggal yang diajukan.</p>
                                        </div>
                                        <div class="col-lg-9 content-right">
                                            @if ($vacation->quota->category->meta->fields == 'options')
                                                <div class="inputs-meta-fields" id="inputs-options">
                                                    <div id="fields-options-tbody">
                                                        @foreach ($vacation->dates as $date)
                                                            <div class="d-flex align-items-center mb-3" @if ($loop->first) id="fields-options-template" @endif>
                                                                <div class="flex-grow-1">
                                                                    <div class="input-group">
                                                                        <input type="date" class="form-control border-radius-md" name="dates[]" value="{{ $date['d'] }}">
                                                                        <div class="input-group-text bg-light inputs-meta-as_freelances @if (!isset($vacation->quota->category->meta->as_freelance)) d-none @endif">
                                                                            <div class="form-check mb-0 d-flex align-items-center">
                                                                                <input class="form-check-input mt-0" name="as_freelances[]" type="checkbox" value="1" onchange="toggleCheckbox(event)" @isset($date['f']) checked="checked" @endisset>
                                                                                <span class="ms-1 text-xxs font-weight-bold text-uppercase">Freelance</span>
                                                                            </div>
                                                                            <input class="form-check-input d-none unchecked mt-0" name="as_freelances[]" type="checkbox" value="0" @empty($date['f']) checked="checked" @endempty>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <button class="btn btn-link text-danger btn-delete @if ($loop->first) d-none @endif mb-0 px-2 py-1" type="button" onclick="removeRow(event)">
                                                                    <span class="material-symbols-rounded">delete</span>
                                                                </button>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <button id="fields-options-add" type="button" class="btn btn-outline-danger btn-sm border-radius-md mb-0 @if(!is_null($vacation->quota->quota) && $remain <= 0) disabled @endif">
                                                        <span class="material-symbols-rounded text-sm">add_circle</span> Tambah Tanggal
                                                    </button>
                                                </div>
                                            @else
                                                <div class="inputs-meta-fields" id="inputs-range">
                                                    <div class="input-group shadow-none border-radius-md overflow-hidden" style="border: 1px solid #d2d6da;">
                                                        <input id="inputs-range-from" type="date" class="form-control border-0" onchange="changeMinDateOfRangeEndAt(event)" value="{{ $vacation->dates->first()['d'] }}">
                                                        <span class="input-group-text bg-light border-0">s.d.</span>
                                                        <input id="inputs-range-to" type="date" class="form-control border-0" onchange="createDateRange()" min="{{ $vacation->dates->first()['d'] }}" value="{{ $vacation->dates->last()['d'] }}">
                                                    </div>
                                                    <div id="inputs-range-dates-group">
                                                        @foreach ($vacation->dates as $date)
                                                            <input type="hidden" name="dates[]" class="inputs-range-dates" value="{{ $date['d'] }}">
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
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
                                        </div>
                                        <div class="col-lg-9 content-right">
                                            <textarea class="form-control border-radius-md" name="description" rows="4" placeholder="Tulis alasan pengambilan cuti...">{{ $vacation->description }}</textarea>
                                        </div>
                                    </div>

                                    {{-- Actions --}}
                                    <div class="row mt-5">
                                        <div class="col-lg-9 offset-lg-3 content-right d-flex gap-2">
                                            <button type="submit" class="btn bg-gradient-danger border-radius-md px-4 d-flex align-items-center gap-2 mb-0">
                                                <span class="material-symbols-rounded">sync</span> Ajukan Ulang
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
        const tbody = document.querySelector('#fields-options-tbody');
        let quota = @json($vacation->quota->quota);

        document.addEventListener('DOMContentLoaded', () => {
            let addButton = document.getElementById('fields-options-add');
            if (addButton) {
                addButton.addEventListener('click', addRow);
            }
        });

        const toggleAddButtonBasedQuota = () => {
            const btn = document.getElementById('fields-options-add');
            if(btn && quota !== null) {
                const canAdd = tbody.children.length < quota;
                btn.classList.toggle('disabled', !canAdd);
                btn.classList.toggle('opacity-5', !canAdd);
            }
        }

        const addRow = () => {
            let firstRow = document.querySelector('#fields-options-template');
            if (tbody.children.length < quota || quota === null) {
                let newRow = firstRow.cloneNode(true);
                newRow.removeAttribute('id');

                // Reset values
                let dateInput = newRow.querySelector('input[type="date"]');
                dateInput.value = '';
                dateInput.required = true;

                newRow.querySelector('.btn-delete').classList.remove('d-none');

                let freelanceCheck = newRow.querySelector('[name="as_freelances[]"]');
                if(freelanceCheck) freelanceCheck.checked = false;

                let unchecked = newRow.querySelector('.unchecked');
                if(unchecked) unchecked.checked = true;

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

            if (quota >= 0 && quota !== null) {
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
