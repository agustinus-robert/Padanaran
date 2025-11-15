@extends('portal::layouts.index')

@section('title', 'Cuti | ')

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
                    <a class="text-decoration-none" href="{{ request('next', route('portal::vacation.submission.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                    <div class="ms-4">
                        <h2 class="mb-1">Ubah pengajuan cuti/libur hari raya</h2>
                        <div class="text-muted">Ada revisi dari atasanmu, silakan ubah melalui form di bawah ini!</div>
                    </div>
                </div>
                <div class="card card-body border-0">
                    <div class="row justify-content-center">
                        <div class="col-xl-11 col-xxl-9">
                            @error('dates.*')
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ $message }} <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @enderror
                            <form class="form-confirm form-block" action="{{ route('portal::vacation.submission.update', ['vacation' => $vacation->id]) }}" method="post"> @csrf @method('PUT')
                                <div class="row mb-3">
                                    <label class="col-md-4 col-lg-3 col-form-label required">Jenis cuti/libur hari raya</label>
                                    <div class="col-md-8">
                                        <div class="card">
                                            <div class="card-header border-bottom-0 text-muted small text-uppercase">{{ $vacation->quota->category->type->label() }}</div>
                                            <div class="list-group list-group-flush show collapse">
                                                @php($remain = $vacation->quota->quota - $vacation->quota->vacations->sum(fn($vacation) => $vacation->dates->count()) + $vacation->dates->count())
                                                @php($is_remain = !is_null($vacation->quota->quota) && $remain > 0)
                                                <label class="list-group-item d-flex align-items-center">
                                                    <input class="form-check-input me-3" type="radio" data-meta="{{ json_encode($vacation->quota->category->meta) }}" value="{{ $vacation->quota->id }}" data-quota="{{ !is_null($vacation->quota->quota ?? null) ? $remain : -1 }}" checked readonly>
                                                    <div>
                                                        <div class="fw-bold mb-0">{{ $vacation->quota->category->name }}</div>
                                                        <div class="small text-muted">Sisa kuota {{ is_null($vacation->quota->quota) ? '∞' : ($remain <= 0 ? 0 : $remain) }} hari</div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3" id="fields-options">
                                    <label class="col-md-4 col-lg-3 col-form-label required">Pilih tanggal cuti/libur hari raya</label>
                                    <div class="col-md-8">
                                        @if ($vacation->quota->category->meta->fields == 'options')
                                            <div class="inputs-meta-fields" id="inputs-options">
                                                <table class="table-borderless mb-0 table">
                                                    <tbody id="fields-options-tbody">
                                                        @foreach ($vacation->dates as $date)
                                                            <tr @if ($loop->first) id="fields-options-template" @endif>
                                                                <td class="ps-0 pt-0">
                                                                    <div class="input-group">
                                                                        <input type="date" class="form-control" name="dates[]" value="{{ $date['d'] }}">
                                                                        <div class="input-group-text inputs-meta-as_freelances @if (!isset($vacation->quota->category->meta->as_freelance)) d-none @endif">
                                                                            <label class="d-flex align-items-center">
                                                                                <input class="form-check-input mt-0" name="as_freelances[]" type="checkbox" value="1" onchange="toggleCheckbox(event)" @isset($date['f']) checked="checked" @endisset> <span class="ps-1">Freelance</span>
                                                                            </label>
                                                                            <input class="form-check-input d-none unchecked mt-0" name="as_freelances[]" type="checkbox" value="0" @empty($date['f']) checked="checked" @endempty> <span class="ps-1"></span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="pe-0 pt-0 text-end" width="50"><button class="btn btn-light btn-delete text-danger @if ($loop->first) d-none @endif" type="button" onclick="removeRow(event)"><i class="mdi mdi-trash-can-outline"></i></button></td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <div id="inputs-meta-as_freelances-text" class="text-muted small d-none mb-3 mt-2">Centang kolom kanan untuk menandai tanggal yang dipilih sebagai freelance</div>
                                                <button id="fields-options-add" type="button" class="btn btn-light text-danger {{ $is_remain ? '' : 'disabled' }}"><i class="mdi mdi-plus-circle-outline"></i> Tambah tanggal</button>
                                            </div>
                                        @else
                                            <div class="inputs-meta-fields" id="inputs-range">
                                                <div class="input-group">
                                                    <input id="inputs-range-from" type="date" class="form-control" onchange="changeMinDateOfRangeEndAt(event)" value="{{ $vacation->dates->first()['d'] }}">
                                                    <div class="input-group-text">s.d.</div>
                                                    <input id="inputs-range-to" type="date" class="form-control" onchange="createDateRange()" min="{{ $vacation->dates->first()['d'] }}" value="{{ $vacation->dates->last()['d'] }}">
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
                                <div class="row mb-3">
                                    <label class="col-md-4 col-lg-3 col-form-label">Untuk keperluan</label>
                                    <div class="col-md-8">
                                        <textarea class="form-control" name="description" rows="5" placeholder="Silakan tulis keterangan/alasan/catatan terkait cuti kamu ...">{{ $vacation->description }}</textarea>
                                    </div>
                                </div>
                                <div class="row mb-3 pt-3">
                                    <div class="col-lg-8 offset-lg-4 offset-xl-3">
                                        <button class="btn btn-soft-danger"><i class="mdi mdi-exit-to-app"></i> Ajukan ulang</button>
                                        <a class="btn btn-ghost-light text-dark" href="{{ request('next', route('portal::vacation.submission.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                                    </div>
                                </div>
                            </form>
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
        let meta = {}

        document.addEventListener('DOMContentLoaded', () => {
            let addROw = document.getElementById('fields-options-add')
            if (addROw) {
                addROw.addEventListener('click', addRow);
            }
        });

        const toggleAddButtonBasedQuota = () => {
            document.getElementById('fields-options-add').classList.toggle('disabled', !(tbody.children.length < quota))
            document.getElementById('fields-options-add').classList.toggle('text-muted', !(tbody.children.length < quota))
        }

        const addRow = () => {
            let tr = document.querySelector('#fields-options-template');
            if (tbody.children.length < quota) {
                tbody.insertAdjacentHTML('beforeend', tr.innerHTML);
                Array.from(tbody.children).forEach((el, i) => {
                    if (i > 0) {
                        el.querySelector('.btn-delete').classList.remove('d-none');
                    }
                    if (i == tbody.children.length - 1) {
                        el.querySelector('[name="dates[]"]').value = '';
                        el.querySelector('[name="dates[]"]').required = true;
                        el.querySelector('[name="as_freelances[]"]').checked = false;
                    }
                });
            }
            toggleAddButtonBasedQuota();
        }

        const removeRow = (e) => {
            e.target.parentNode.closest('tr').remove();
            toggleAddButtonBasedQuota();
        }

        const toggleCheckbox = (el) => {
            let checkbox = el.target.parentNode.closest('.input-group-text').querySelectorAll('[name="as_freelances[]"]');
            checkbox[1].checked = !checkbox[0].checked
        }

        const changeMinDateOfRangeEndAt = (e) => {
            Array.from(document.querySelectorAll('.inputs-range-dates')).map((el) => el.remove());
            let end_at = document.querySelector('#inputs-range-to');
            end_at.value = '';
            end_at.min = e.target.value;

            if (quota >= 0 && quota !== null) {
                let max = new Date(e.target.value);
                max.setDate(max.getDate() + quota);
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
                    input = document.createElement('input');
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
