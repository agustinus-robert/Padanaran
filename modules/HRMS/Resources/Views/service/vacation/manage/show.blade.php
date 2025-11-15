@extends('hrms::layouts.default')

@section('title', 'Kelola cuti | ')
@section('navtitle', 'Kelola cuti')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('hrms::service.vacation.manage.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Lihat detail pengajuan cuti</h2>
            <div class="text-secondary">Menampilkan tanggal pengajuan dan detail lainnya terkait cuti yang diajukan.</div>
        </div>
    </div>
    @if ($vacation->trashed())
        <div class="alert alert-danger border-0">
            <strong>Perhatian!</strong> Pengajuan ini telah dihapus, Anda tidak lagi dapat mengelola pengajuan ini.
        </div>
    @endif
    <div class="row">
        <div class="col-xl-8">
            <div class="card border-0">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div><i class="mdi mdi-eye-outline"></i> Detail pengajuan</div>
                    @if (!$vacation->trashed())
                        <a class="btn btn-soft-success btn-sm rounded px-2 py-1" href="{{ route('hrms::service.vacation.manage.print', ['vacation' => $vacation->id]) }}" target="_blank"><i class="mdi mdi-printer-outline"></i> <span class="d-none d-sm-inline">Cetak dokumen (.pdf)</span></a>
                    @endif
                </div>
                <div class="card-body border-top">
                    <form class="form-confirm form-block" @if (!$vacation->trashed()) action="{{ route('hrms::service.vacation.manage.update', ['vacation' => $vacation->id]) }}" method="post" @endif> @csrf @method('put')
                        <div class="row mb-3">
                            <label class="col-md-4 col-lg-3 col-form-label required">Jenis cuti/libur hari raya</label>
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header border-bottom-0 text-muted small text-uppercase">{{ $vacation->quota->category->type->label() }}</div>
                                    <div class="list-group list-group-flush collapse show">
                                        @php($remain = $vacation->quota->quota - $vacation->quota->vacations->sum(fn($vacation) => $vacation->dates->count()) + $vacation->dates->count())
                                        @php($is_remain = !is_null($vacation->quota->quota) && $remain > 0)
                                        <label class="list-group-item d-flex align-items-center">
                                            <input class="form-check-input me-3" type="radio" data-meta="{{ json_encode($vacation->quota->category->meta) }}" value="{{ $vacation->quota->id }}" data-quota="{{ !is_null($vacation->quota->quota ?? null) ? $remain : -1 }}" checked readonly>
                                            <div>
                                                <div class="fw-bold mb-0">{{ $vacation->quota->category->name }}</div>
                                                <div class="small text-muted">Sisa kuota {{ is_null($vacation->quota->quota) ? '∞' : ($remain <= 0 ? 0 : $remain) }} hari</div>
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
                                                                <input type="date" class="form-control" name="dates[]" value="{{ $date['d'] }}" @if ($vacation->trashed()) disabled @endif>
                                                                <div class="input-group-text inputs-meta-as_freelances @if (!isset($vacation->quota->category->meta->as_freelance)) d-none @endif">
                                                                    <label class="d-flex align-items-center">
                                                                        <input class="form-check-input mt-0" name="as_freelances[]" type="checkbox" value="1" onchange="toggleCheckbox(event)" @isset($date['f']) checked="checked" @endisset @if ($vacation->trashed()) disabled @endif> <span class="ps-1">Freelance</span>
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
                                        <div id="inputs-meta-as_freelances-text" class="text-muted small @if (!isset($vacation->quota->category->meta->as_freelance)) d-none @endif mt-2 mb-3">Centang kolom kanan untuk menandai tanggal yang dipilih sebagai freelance</div>
                                        @if ($vacation->trashed())
                                            <button id="fields-options-add" type="button" class="btn btn-light text-danger {{ $is_remain ? '' : 'disabled' }}"><i class="mdi mdi-plus-circle-outline"></i> Tambah tanggal</button>
                                        @endif
                                    </div>
                                @else
                                    <div class="inputs-meta-fields" id="inputs-range">
                                        <div class="input-group">
                                            <input id="inputs-range-from" type="date" class="form-control" onchange="changeMinDateOfRangeEndAt(event)" value="{{ $vacation->dates->first()['d'] }}" @if ($vacation->trashed()) disabled @endif>
                                            <div class="input-group-text">s.d.</div>
                                            <input id="inputs-range-to" type="date" class="form-control" onchange="createDateRange()" min="{{ $vacation->dates->first()['d'] }}" value="{{ $vacation->dates->last()['d'] }}" @if ($vacation->trashed()) disabled @endif>
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
                                <textarea class="form-control" name="description" rows="5" placeholder="Silakan tulis keterangan/alasan/catatan terkait cuti ..." @if ($vacation->trashed()) disabled @endif>{{ $vacation->description }}</textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 offset-lg-4 offset-xl-3">
                                <button class="btn btn-soft-danger @if ($vacation->trashed()) disabled @endif"><i class="mdi mdi-check"></i> Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card border-0">
            <div class="card-body">
                <i class="mdi mdi-progress-clock"></i> Status pengajuan/pembatalan
            </div>
            <div class="card-body border-top">
                @foreach ($vacation->approvables as $approvable)
                    <div class="@if (!$loop->last) mb-4 @endif">
                        <div class="mb-3">
                            <div class="text-muted small mb-1">
                                {{ ucfirst($approvable->type) }} #{{ $approvable->level }}
                            </div>
                            <strong>{{ $approvable->userable->getApproverLabel() }}</strong>
                        </div>
                        <div>
                            <form class="form-block" @if (!$vacation->trashed()) action="{{ route('hrms::service.vacation.manage.approvable.update', ['vacation' => $vacation->id, 'approvable' => $approvable->id]) }}" method="post" @endif> @csrf @method('PUT')
                                <div class="row gy-3">
                                    <div class="col-xl-3 col-lg-4 col-md-5">
                                        <div class="input-group">
                                            <div class="input-group-text">Status</div>
                                            <select class="form-select @error('result.' . $approvable->id) is-invalid @enderror" name="result[{{ $approvable->id }}]" @if ($vacation->trashed()) disabled @endif>
                                                @foreach ($results as $result)
                                                    @unless(($approvable->cancelable && in_array($result, Modules\HRMS\Models\EmployeeVacation::$cancelable_disable_result)) || in_array($result, Modules\HRMS\Models\EmployeeVacation::$approvable_disable_result ?? []))
                                                        <option value="{{ $result->value }}" @selected($result->value == old('result.' . $approvable->id, $approvable->result->value))>{{ $result->label() }}</option>
                                                    @endunless
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('result.' . $approvable->id)
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-auto flex-grow-1">
                                        <textarea class="form-control @error('reason.' . $approvable->id) is-invalid @enderror" type="text" name="reason[{{ $approvable->id }}]" placeholder="Alasan ..." style="height: 38px; min-height: 38px;" @if ($vacation->trashed()) disabled @endif>{{ old('reason.' . $approvable->id, $approvable->reason) }}</textarea>
                                        @error('reason.' . $approvable->id)
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-auto">
                                        <button class="btn btn-soft-danger @if ($vacation->trashed()) disabled @endif"><i class="mdi mdi-check"></i> Simpan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @if ($approvable->history)
                        <div class="row p-0">
                            <div class="col-md-6 offset-md-6">
                                <hr class="text-muted mt-0">
                                <p class="small text-muted mb-1">Catatan riwayat sebelumnya</p>
                                {{ $approvable->history->reason }}
                            </div>
                        </div>
                    @endif
                    @if (!$loop->last)
                        <hr class="text-muted">
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card border-0">
            <div class="card-body">
                <i class="mdi mdi-account-box-multiple-outline"></i> Detail karyawan
            </div>
            <div class="list-group list-group-flush border-top">
                @foreach (array_filter([
            'Nama karyawan' => $vacation->quota->employee->user->name,
            'NIP' => $vacation->quota->employee->kd ?: '-',
            'Jabatan' => $vacation->quota->employee->position->position->name ?? '-',
            'Departemen' => $vacation->quota->employee->position->position->department->name ?? '-',
            'Atasan' => $vacation->quota->employee->position->position->parents->last()?->employees->first()->user->name ?? null,
        ]) as $label => $value)
                    <div class="list-group-item">
                        <div class="row d-flex align-items-center">
                            <div class="col-sm-6 col-xl-12">
                                <div class="small text-muted">{{ $label }}</div>
                            </div>
                            <div class="col-sm-6 col-xl-12 fw-bold"> {{ $value }} </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @if ($vacation->trashed())
            <form class="form-block form-confirm" action="{{ route('hrms::service.vacation.manage.restore', ['vacation' => $vacation->id]) }}" method="post"> @csrf @method('put')
                <button class="btn btn-outline-danger w-100 text-danger d-flex align-items-center bg-white py-3 text-start">
                    <i class="mdi mdi-trash-can-outline me-3"></i>
                    <div>Pulihkan pengajuan <br> <small class="text-muted">Aktifkan kembali data pengajuan dari daftar</small></div>
                </button>
            </form>
        @else
            <form class="form-block form-confirm mb-4" action="{{ route('hrms::service.vacation.manage.change', ['vacation' => $vacation->id]) }}" method="post"> @csrf @method('put')
                <button class="btn btn-light w-100 text-warning d-flex align-items-center bg-white py-3 text-start">
                    <i class="mdi mdi-sync me-3"></i>
                    <div>Ubah status pengajuan <br> <small class="text-muted">Jadikan status pengajuan ini menjadi <strong>{{ $vacation->approvables->first()?->cancelable ? 'pengajuan' : 'pembatalan' }}</strong></small></div>
                </button>
            </form>
            <form class="form-block form-confirm" action="{{ route('hrms::service.vacation.manage.destroy', ['vacation' => $vacation->id]) }}" method="post"> @csrf @method('delete')
                <button class="btn btn-outline-danger w-100 text-danger d-flex align-items-center bg-white py-3 text-start">
                    <i class="mdi mdi-trash-can-outline me-3"></i>
                    <div>Batalkan pengajuan <br> <small class="text-muted">Hapus data pengajuan dari daftar</small></div>
                </button>
            </form>
        @endif
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
