@extends('hrms::layouts.default')

@section('title', 'Distribusi cuti | ')
@section('navtitle', 'Distribusi cuti')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('hrms::service.vacation.quotas.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Distribusi cuti karyawan</h2>
            <div class="text-secondary">Anda dapat menambahkan pembagian cuti karyawan dengan mengisi formulir di bawah</div>
        </div>
    </div>
    <div class="card mb-4 border-0">
        <div class="card-body">
            <form class="form-block" action="{{ route('hrms::service.vacation.quotas.store', ['next' => request('next')]) }}" method="POST"> @csrf
                <div class="row required mb-3">
                    <label class="col-lg-4 col-xl-3 col-form-label">Nama guru</label>
                    <div class="col-lg-8 col-xl-7 col-xxl-4">
                        <select class="@error('employee') is-invalid @enderror form-select" name="employee" required>
                            @isset($employee)
                                <option value="{{ $employee->id }}" selected>{{ $employee->user->name ?? $employee->user->profile->name }}</option>
                            @endisset
                        </select>
                        @error('employee')
                            <small class="text-danger d-block"> {{ $message }} </small>
                        @enderror
                    </div>
                </div>
                <div class="row required mb-3">
                    <label class="col-lg-4 col-xl-3 col-form-label">Kategori cuti</label>
                    <div class="col-xl-9 col-xxl-9">
                        <div class="table-responsive rounded border">
                            <table class="table-hover mb-0 table">
                                <thead>
                                    <tr>
                                        <th nowrap class="pt-2">Kategori</th>
                                        <th class="pt-2">Masa berlaku</th>
                                        <th class="pt-2">Kuota (hari)</th>
                                        <th width="50"></th>
                                    </tr>
                                </thead>
                                <tbody id="categories-tbody">
                                    @foreach (setting('cmp_services_vacation_quotas', collect(json_decode('[{}]'))) as $quota)
                                        <tr @if ($loop->first) id="categories-template" @endif>
                                            <td>
                                                <select class="categories-select form-select" name="quotas[category][]" onchange="applyQuota(event)" required>
                                                    <option value="">-- Pilih kategori --</option>
                                                    @foreach ($categories->groupBy(fn($ctg) => $ctg->type->label()) as $type => $_categories)
                                                        <optgroup class="{{ $type }}" label="{{ $type }}">
                                                            @foreach ($_categories as $category)
                                                                <option value="{{ $category->id }}" data-quota="{{ $category->meta->quota ?? -1 }}" @selected(old('quotas.category.*', optional($quota)['ctg_id']) == $category->id)>{{ $category->name }}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endforeach
                                                </select>
                                                @error('quotas.category.*')
                                                    <small class="text-danger d-block"> {{ $message }} </small>
                                                @enderror
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <input type="date" class="form-control @error('start_at') is-invalid @enderror" name="quotas[start_at][]" value="{{ old('quotas.start_at.*', request('year', date('Y')) . '-01-01') }}" required />
                                                    <div class="input-group-text">s.d.</div>
                                                    <input type="date" class="form-control @error('end_at') is-invalid @enderror" name="quotas[end_at][]" value="{{ old('quotas.end_at.*', request('year', date('Y')) . '-12-31') }}" />
                                                </div>
                                                @error('quotas.start_at.*')
                                                    <small class="text-danger d-block"> {{ $message }} </small>
                                                @enderror
                                                @error('quotas.end_at.*')
                                                    <small class="text-danger d-block"> {{ $message }} </small>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="number" class="form-control qty @error('quota') is-invalid @enderror" name="quotas[quota][]" value="{{ old('quotas.quota.*', optional($quota)['quota']) }}" />
                                                @error('quotas.quota.*')
                                                    <small class="text-danger d-block"> {{ $message }} </small>
                                                @enderror
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-light btn-delete text-danger @if ($loop->first) d-none @endif" onclick="removeRow(event)"><i class="mdi mdi-trash-can-outline"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="p-2">
                                <button id="categories-add" type="button" class="btn btn-light text-danger"><i class="mdi mdi-plus-circle-outline"></i> Tambah kategori baru</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-4 col-xl-3 col-form-label">Tampilkan di user mulai tanggal</label>
                    <div class="col-lg-8 col-xl-7 col-xxl-4">
                        <input type="datetime-local" class="form-control @error('visible_at') is-invalid @enderror" name="visible_at">
                        @error('visible_at')
                            <small class="text-danger d-block"> {{ $message }} </small>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-lg-8 offset-lg-4 offset-xl-3">
                        <div class="card card-body border">
                            <div class="form-check d-flex align-items-center">
                                <input class="form-check-input" id="as_template" name="as_template" type="checkbox" value="1">
                                <label class="form-check-label ms-3" for="as_template">
                                    <div><strong>Jadikan sebagai template default</strong></div>
                                    <div class="text-muted">Jika dicentang, maka penambahan distribusi karyawan selanjutnya akan menggunakan kategori yang sama.</div>
                                </label>
                            </div>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" id="agreement" type="checkbox" required>
                            <label class="form-check-label" for="agreement">Dengan ini saya menyatakan data di atas adalah valid</label>
                        </div>
                        <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                        <a class="btn btn-ghost-light text-dark" href="{{ request('next', route('hrms::service.vacation.quotas.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/tom-select/css/tom-select.bootstrap5.min.css') }}">
@endpush

@push('scripts')
    <script>
        let quotanow = {!! json_encode($quotanow) !!};
        let sex = {!! $employee->user->getMeta('profile_sex') !!};
        let religion = {!! json_encode($employee->user->getMeta('profile_religion') ?? '') !!};

        document.addEventListener("DOMContentLoaded", () => {
            renderTomSelect();
            renderYearlyQuota();
            document.getElementById('categories-add').addEventListener('click', addRow);
        });

        const addRow = () => {
            let tr = document.querySelector('#categories-template').innerHTML;
            let tbody = document.querySelector('#categories-tbody');
            if (tbody.children.length < 20) {
                tbody.insertAdjacentHTML('beforeend', tr);
                Array.from(tbody.children).forEach((el, i) => {
                    if (i > 0)
                        el.querySelector('.btn-delete').classList.remove('d-none');
                    if (i == tbody.children.length - 1)
                        Array.from(el.querySelectorAll('input:not([type="date"]), select')).map(el => (el.value = ''));
                });
            }
        }

        const removeRow = (e) => {
            e.target.parentNode.closest('tr').remove()
        }

        const applyQuota = (e) => {
            let quota = e.target.options[e.target.selectedIndex].dataset.quota;
            e.target.parentNode.closest('tr').querySelector('[name="quotas[quota][]"]').value = quota >= 0 ? quota : null
        }

        const renderYearlyQuota = () => {
            [...document.querySelectorAll('.categories-select')].map((select) => {
                [...select].map((option) => {
                    if (option.selected == true) {
                        if (sex != 2) {
                            [6, 7].includes(parseFloat(option.value)) ?
                                option.parentNode.parentNode.closest('tr').querySelector('.btn-delete').click() :
                                false;
                        }
                        if ([1, 2, 3].includes(parseFloat(option.value))) {
                            let sel = option.parentNode.parentNode;
                            [...sel.options].map((e) => {
                                e.selected == true ? e.removeAttribute('selected') : false;
                                e.value == quotanow.quota_id ? e.setAttribute('selected', 'true') : false;
                            });
                            sel.closest('tr').querySelector('.qty').value = quotanow.value;
                        }
                        if (religion) {
                            if (religion == 1) {
                                parseFloat(option.value) == 5 ?
                                    option.parentNode.parentNode.closest('tr').querySelector('.btn-delete').click() :
                                    false;
                            } else {
                                parseFloat(option.value) == 4 ?
                                    option.parentNode.parentNode.closest('tr').querySelector('.btn-delete').click() :
                                    false;
                            }
                        }
                    }
                });
            });
        }


        const renderTomSelect = () => {
            new TomSelect('[name="employee"]', {
                valueField: 'id',
                labelField: 'text',
                searchField: 'text',
                load: function(q, callback) {
                    fetch('{{ route('api::hrms.employees.search') }}?q=' + encodeURIComponent(q))
                        .then(response => response.json())
                        .then(json => {
                            callback(json.employees);
                        }).catch(() => {
                            callback();
                        });
                }
            });
        }
    </script>
@endpush
