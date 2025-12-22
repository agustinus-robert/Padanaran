@extends('layouts.horizontal-layout')

@section('title', 'Distribusi cuti | ')
@section('navtitle', 'Distribusi cuti')

@push('nav')
@include('hrms::layouts.includes.navbar-hrms')
@endpush

@section('body-content')
<div class="row container-fluid justify-content-center">
    @include('components.navbar-admin')

    <div class="col-xxl-8 col-xl-10">
        <div class="card mb-4 border-0 shadow-sm">
            <x-card-header type="{{ config('theme.default') }}">
                Distribusi Cuti Karyawan
            </x-card-header>

            <div class="card-body">
                <form class="form-block" action="{{ route('hrms::service.vacation.quotas.store', ['next' => request('next')]) }}" method="POST"> @csrf
                    <x-input-group label="Nama guru" required>
                        <x-select
                            name="employee"
                            :options="[
                                ['value' => $employee->id, 'label' => $employee->user->name ?? $employee->user->profile->name]
                            ]"
                            :value="old('employee', $employee->id ?? '')"
                            required
                        />
                    </x-input-group>


                    <x-input-group :isRow="false" label="Kategori cuti" required>
                        <div class="table table-responsive rounded border">
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
                                               @php
                                                    $categoryOptions = $categories->groupBy(fn($ctg) => $ctg->type->label())->map(function($group, $type) {
                                                        return [
                                                            'label' => $type,
                                                            'children' => $group->map(fn($ctg) => [
                                                                'value' => $ctg->id,
                                                                'label' => $ctg->name,
                                                                'data-quota' => $ctg->meta->quota ?? -1
                                                            ])->toArray()
                                                        ];
                                                    })->values()->toArray();
                                                @endphp

                                                <x-select
                                                    name="quotas[category][]"
                                                    class="categories-select"
                                                    :options="$categoryOptions"
                                                    :value="old('quotas.category.*', optional($quota)['ctg_id'] ?? '')"
                                                    required
                                                    onchange="applyQuota(event)"
                                                    placeholder="-- Pilih kategori --"
                                                />
                                            </td>

                                            <td>
                                                <x-input-group>
                                                    <x-input type="date" name="quotas[start_at][]" :value="old('quotas.start_at.*', request('year', date('Y')).'-01-01')" required />
                                                    <p class="m-2">s.d.</p>
                                                    <x-input type="date" name="quotas[end_at][]" :value="old('quotas.end_at.*', request('year', date('Y')).'-12-31')" />
                                                </x-input-group>
                                            </td>
                                            <td>
                                                <x-input type="number" name="quotas[quota][]" :value="old('quotas.quota.*', optional($quota)['quota'])" class="qty" />
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-delete @if($loop->first) d-none @endif" onclick="removeRow(event)">
                                                    <i class="material-symbols-rounded">delete</i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="p-2">
                                <button id="categories-add" type="button" class="btn btn-sm btn-info text-white">
                                    <i class="material-symbols-rounded text-white fs-5">add</i> Tambah kategori baru
                                </button>
                            </div>
                        </div>
                    </x-input-group>

                    <x-input-group label="Tampilkan di user mulai tanggal">
                        <x-input type="datetime-local" name="visible_at" :value="old('visible_at')" />
                    </x-input-group>


                    <div class="card card-body border mb-3 justify checklist-item checklist-item-primary">
                        <x-input-group :isRow="true" :isOutline="false">
                            <div class="form-check is-filled">
                                <x-input type="checkbox" name="as_template" id="as_template" value="1" />
                                <label for="as_template" class="ms-2">
                                    <strong>Jadikan sebagai template default</strong><br>
                                    <span class="text-muted">Jika dicentang, maka penambahan distribusi karyawan selanjutnya akan menggunakan kategori yang sama.</span>
                                </label>
                            </div>
                        </x-input-group>
                    </div>

                    <div class="card card-body border mb-3 justify checklist-item checklist-item-primary">
                        <x-input-group :isRow="true" :isOutline="false">
                                <div class="form-check is-filled">
                                    <x-input type="checkbox" name="agreement" id="agreement" required />
                                    <label for="agreement" class="ms-2">Dengan ini saya menyatakan data di atas adalah valid</label>
                                </div>
                        </x-input-group>
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <x-btn variant="dark"><i class="mdi mdi-check"></i> Simpan</x-btn>
                        <a class="btn btn-light text-dark" href="{{ request('next', route('hrms::service.vacation.quotas.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                    </div>

                </form>
            </div>
        </div>
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
            //renderTomSelect();
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
