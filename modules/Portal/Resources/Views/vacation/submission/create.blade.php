@extends('portal::layouts.index')

@section('title', 'Cuti | ')

@include('components.tourguide', [
    'steps' => array_filter([
        [
            'selector' => '.tg-steps-vacation-category',
            'title' => 'Jenis cuti/libur hari raya',
            'content' => 'Pilih jenis cuti atau libur hari raya yang sesuai dengan kebutuhan kamu.',
        ],
        [
            'selector' => '.tg-steps-vacation-date',
            'title' => 'Tanggal cuti',
            'content' => 'Kolom ini diisi tanggal cuti yang udah kamu rencanain.',
        ],
        [
            'selector' => '.tg-steps-vacation-description',
            'title' => 'Keperluan cuti',
            'content' => 'Bisa diisi keperluan, catatan, alasan, atau deskripsi penting lainnya kalau ada.',
        ],
    ]),
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
                    <a class="text-decoration-none" href="{{ request('next', route('portal::vacation.submission.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                    <div class="ms-4">
                        <h2 class="mb-1">Pengajuan cuti/libur hari raya baru</h2>
                        <div class="text-muted">Ambil cutimu atau liburmu buat ke pantai atau muncak!</div>
                    </div>
                </div>
                @error('dates.*')
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ $message }} <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @enderror
                @if (count($errors))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div>Maaf, terjadi kegagalan, silakan periksa kembali isian Kamu</div>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="card border-0">
                    <div class="card-body border-bottom d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            <i class="mdi mdi-plus"></i> Pengajuan cuti/libur hari raya
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-xl-11 col-xxl-9">
                                <form class="form-confirm form-block" action="{{ route('portal::vacation.submission.store') }}" method="post"> @csrf
                                    <div class="row mb-3">
                                        <label class="col-md-4 col-lg-3 col-form-label required">Jenis cuti/libur hari raya</label>
                                        <div class="col-md-8">
                                            <div class="card tg-steps-vacation-category">
                                                @foreach ($quotas->groupBy(fn($quota) => $quota->category->type->label()) as $type => $_quotas)
                                                    <div class="card-header border-bottom-0 text-muted small text-uppercase" data-bs-toggle="collapse" data-bs-target="#collapse-{{ Str::slug($type) }}" style="cursor: pointer;">{{ $type }} <i class="mdi mdi-chevron-down float-end"></i></div>
                                                    <div class="list-group list-group-flush {{ $_quotas->first()->category->type->quotaVisibility() == true ? 'show' : '' }} collapse" id="collapse-{{ Str::slug($type) }}">
                                                        @foreach ($_quotas as $quota)
                                                            @php($is_remain = !is_null($quota->quota) && $quota->remain <= 0)
                                                            <label class="list-group-item d-flex align-items-center {{ $is_remain ? 'text-muted bg-soft-secondary' : '' }}">
                                                                <input class="form-check-input me-3" type="radio" name="quota_id" data-meta="{{ json_encode($quota->category->meta) }}" value="{{ $quota->id }}" data-quota="{{ !is_null($quota->quota ?? null) ? $quota->remain : -1 }}" data-start="{{ $quota->start_at <= now() ? now()->format('Y-m-d') : $quota->start_at->format('Y-m-d') }}" @if ($is_remain) disabled @endif required>
                                                                <div>
                                                                    <div class="fw-bold mb-0">{{ $quota->category->name }}</div>
                                                                    <div class="small text-muted">Sisa kuota {{ is_null($quota->quota) ? '∞' : ($quota->remain <= 0 ? 0 : $quota->remain) }} hari, berlaku s.d. {{ $quota->end_at->formatLocalized('%d %B %Y') }}</div>
                                                                </div>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3" id="fields-options">
                                        <label class="col-md-4 col-lg-3 col-form-label required">Pilih tanggal cuti/libur hari raya</label>
                                        <div class="col-md-8">
                                            <div class="inputs-meta-fields tg-steps-vacation-date" id="inputs-options">
                                                <table class="table-borderless mb-0 table">
                                                    <tbody id="fields-options-tbody">
                                                        <tr id="fields-options-template">
                                                            <td class="ps-0 pt-0">
                                                                <div class="input-group">
                                                                    <input type="date" class="form-control" name="dates[]" min="{{ date('Y-m-d') }}">
                                                                    <div class="input-group-text inputs-meta-as_freelances d-none">
                                                                        <label class="d-flex align-items-center">
                                                                            <input class="form-check-input mt-0" name="as_freelances[]" type="checkbox" value="1" onchange="toggleCheckbox(event)"> <span class="ps-1">Freelance</span>
                                                                        </label>
                                                                        <input class="form-check-input d-none unchecked mt-0" name="as_freelances[]" type="checkbox" value="0" checked="checked"> <span class="ps-1"></span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="pe-0 pt-0 text-end" width="50"><button class="btn btn-light btn-delete text-danger d-none" type="button" onclick="removeRow(event)"><i class="mdi mdi-trash-can-outline"></i></button></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <div id="inputs-meta-as_freelances-text" class="text-muted small d-none mb-3 mt-2">Centang kolom kanan untuk menandai tanggal yang dipilih sebagai freelance</div>
                                                <button id="fields-options-add" type="button" class="btn btn-light text-danger disabled"><i class="mdi mdi-plus-circle-outline"></i> Tambah tanggal</button>
                                            </div>
                                            <div class="inputs-meta-fields d-none" id="inputs-range">
                                                <div class="input-group">
                                                    <input id="inputs-range-from" type="date" class="form-control" onchange="changeMinDateOfRangeEndAt(event)">
                                                    <div class="input-group-text">s.d.</div>
                                                    <input id="inputs-range-to" type="date" class="form-control" onchange="createDateRange()">
                                                </div>
                                                <div id="inputs-range-dates-group"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-md-4 col-lg-3 col-form-label">Untuk keperluan</label>
                                        <div class="col-md-8">
                                            <textarea class="form-control tg-steps-vacation-description" name="description" rows="5" placeholder="Silakan tulis keterangan/alasan/catatan terkait cuti kamu ..."></textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3 pt-3">
                                        <div class="col-lg-8 offset-lg-4 offset-xl-3">
                                            <button class="btn btn-soft-danger"><i class="mdi mdi-exit-to-app"></i> Ajukan</button>
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
    </div>
@endsection

@push('scripts')
    <script>
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

                Array.from(tbody.children).map((el, i) => {
                    if (i > 0) el.remove();
                })

                Array.from(document.querySelectorAll('.inputs-range-dates')).map((el) => el.remove());

                document.querySelector('#inputs-range-from').value = '';
                document.querySelector('#inputs-range-to').value = '';

                document.querySelector('#inputs-range-from').required = meta.fields == 'range';
                document.querySelector('#inputs-range-to').required = meta.fields == 'range';
                document.querySelector('#inputs-options input').required = meta.fields == 'options';

                Array.from(document.querySelectorAll('.inputs-meta-fields')).map((el) => el.classList.add('d-none'));
                document.querySelector(`#inputs-${meta.fields}`).classList.remove('d-none');

                Array.from(document.querySelectorAll('.inputs-meta-as_freelances')).map((el) => el.classList.toggle('d-none', !meta.as_freelance));
                document.querySelector('#inputs-meta-as_freelances-text').classList.toggle('d-none', !meta.as_freelance);

                Array.from(document.querySelectorAll('[name="dates[]"]')).map((el) => el.value = '');
                Array.from(document.querySelectorAll('[name="as_freelances[]"]')).map((el) => el.checked = false);
                Array.from(document.querySelectorAll('[name="as_freelances[]"].unchecked')).map((el) => el.checked = true);

                // limit start
                Array.from(document.querySelectorAll('[name="dates[]"]')).map((el) => el.setAttribute('min', start));

                toggleAddButtonBasedQuota();
            }
        }

        const toggleAddButtonBasedQuota = () => {
            document.getElementById('fields-options-add').classList.toggle('disabled', !(tbody.children.length < quota))
            document.getElementById('fields-options-add').classList.toggle('text-muted', !(tbody.children.length < quota))
        }

        const addRow = () => {
            let tr = document.querySelector('#fields-options-template').innerHTML;
            if (tbody.children.length < quota) {
                tbody.insertAdjacentHTML('beforeend', tr);
                Array.from(tbody.children).forEach((el, i) => {
                    if (i > 0) {
                        el.querySelector('.btn-delete').classList.remove('d-none');
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
