@extends('hrms::layouts.default')

@section('title', 'Kelola izin | ')
@section('navtitle', 'Kelola izin')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('hrms::service.leave.manage.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Lihat detail pengajuan izin</h2>
            <div class="text-secondary">Menampilkan tanggal pengajuan dan detail lainnya terkait izin yang diajukan.</div>
        </div>
    </div>
    @if ($leave->trashed())
        <div class="alert alert-danger border-0">
            <strong>Perhatian!</strong> Pengajuan ini telah dihapus, Anda tidak lagi dapat mengelola pengajuan ini.
        </div>
    @endif
    <div class="row">
        <div class="col-xl-8">
            <div class="card border-0">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div><i class="mdi mdi-eye-outline"></i> Detail pengajuan</div>
                    @if (!$leave->trashed())
                        <a class="btn btn-soft-success btn-sm rounded px-2 py-1" href="{{ route('hrms::service.leave.manage.print', ['leave' => $leave->id]) }}" target="_blank"><i class="mdi mdi-printer-outline"></i> <span class="d-none d-sm-inline">Cetak dokumen (.pdf)</span></a>
                    @endif
                </div>
                <div class="card-body border-top">
                    <form class="form-confirm form-block" enctype="multipart/form-data" @if (!$leave->trashed()) action="{{ route('hrms::service.leave.manage.update', ['leave' => $leave->id]) }}" method="post" @endif> @csrf @method('put')
                        <div class="row mb-3">
                            <label class="col-md-4 col-lg-3 col-form-label required">Nama karyawan</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" readonly disabled value="{{ $leave->employee->user->name }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-lg-3 col-form-label required">Jenis izin</label>
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="list-group list-group-flush show collapse">
                                        <label class="list-group-item d-flex align-items-center">
                                            <input class="form-check-input me-3" type="radio" data-meta="{{ json_encode($leave->category->meta) }}" value="{{ $leave->id }}" data-quota="{{ !is_null($leave->quota ?? null) ? $remain : -1 }}" checked readonly>
                                            <div>
                                                <div class="fw-bold mb-0">{{ $leave->category->name }}</div>
                                                <div class="small text-muted">Sisa kuota {{ is_null($leave->category->meta?->quota) ? '∞' : $leave->category->meta->quota }} hari</div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3" id="fields-options">
                            <label class="col-md-4 col-lg-3 col-form-label required">Pilih tanggal izin</label>
                            <div class="col-md-8">
                                <div class="inputs-meta-fields" id="inputs-options">
                                    <table class="table-borderless mb-0 table">
                                        <tbody id="fields-options-tbody">
                                            @foreach ($leave->dates as $date)
                                                <tr @if ($loop->first) id="fields-options-template" @endif>
                                                    <td class="ps-0 pt-0">
                                                        <input type="date" class="form-control @error('dates') is-invalid @enderror" name="dates[]" value="{{ $date['d'] }}" @if ($leave->trashed()) disabled @endif required>
                                                    </td>
                                                    <td class="pe-0 pt-0 text-end" width="50"><button class="btn btn-light btn-delete text-danger @if ($loop->first) d-none @endif" type="button" onclick="removeRow(event)"><i class="mdi mdi-trash-can-outline"></i></button></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @error('dates')
                                        <div class="small text-danger mb-1">{{ $message }}</div>
                                    @enderror
                                    @if (!$leave->trashed())
                                        <button id="fields-options-add" type="button" class="btn btn-light {{ count($leave->dates) >= ($leave->category->meta?->quota ?: 365) ? 'disabled text-muted' : 'text-danger' }}"><i class="mdi mdi-plus-circle-outline"></i> Tambah tanggal</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row {{ isset($date['t_s']) || isset($date['t_e']) ? '' : 'd-none' }} mb-3" id="hide_if_date_only">
                            <label class="col-md-4 col-lg-3 col-form-label required">Pukul</label>
                            <div class="col-xl-5 col-md-6">
                                <div class="input-group">
                                    <input type="time" class="form-control @error('time_start') is-invalid @enderror" name="time_start" value="{{ old('time_start', $date['t_s'] ?? '') }}" @if ($leave->trashed()) disabled @endif>
                                    @isset($date['t_e'])
                                        <div class="input-group-text">s.d.</div>
                                        <input type="time" class="form-control @error('time_end') is-invalid @enderror" name="time_end" value="{{ old('time_start', $date['t_e'] ?? '') }}" @if ($leave->trashed()) disabled @endif>
                                    @endisset
                                </div>
                                @error('time_end')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-lg-3 col-form-label">Deskripsi</label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="description" rows="5" placeholder="Silakan tulis keterangan/alasan/catatan terkait izin ..." @if ($leave->trashed()) disabled @endif>{{ $leave->description }}</textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-lg-3 col-form-label">Lampiran</label>
                            <div class="col-md-8">
                                <input class="form-control @error('attachment') is-invalid @enderror" name="attachment" type="file" id="upload-input" accept="image/*,application/pdf" @if ($leave->trashed()) disabled @endif>
                                <small class="text-muted">Kosongkan jika tidak ingin memperbarui lampiran yang telah diunggah</small>
                                @error('attachment')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 offset-lg-4 offset-xl-3">
                                <button class="btn btn-soft-danger @if ($leave->trashed()) disabled @endif"><i class="mdi mdi-check"></i> Perbarui</button>
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
                @foreach ($leave->approvables as $approvable)
                    <div class="@if (!$loop->last) mb-4 @endif">
                        <div class="mb-3">
                            <div class="text-muted small mb-1">
                                {{ ucfirst($approvable->type) }} #{{ $approvable->level }}
                            </div>
                            <strong>{{ $approvable->userable->getApproverLabel() }}</strong>
                        </div>
                        <div>
                            <form class="form-block" @if (!$leave->trashed()) action="{{ route('hrms::service.leave.manage.approvable.update', ['leave' => $leave->id, 'approvable' => $approvable->id]) }}" method="post" @endif> @csrf @method('PUT')
                                <div class="row gy-3">
                                    <div class="col-xl-3 col-lg-4 col-md-5">
                                        <div class="input-group">
                                            <div class="input-group-text">Status</div>
                                            <select class="form-select @error('result.' . $approvable->id) is-invalid @enderror" name="result[{{ $approvable->id }}]" @if ($leave->trashed()) disabled @endif>
                                                @foreach ($results as $result)
                                                    @unless (($approvable->cancelable && in_array($result, Modules\HRMS\Models\EmployeeVacation::$cancelable_disable_result)) || in_array($result, Modules\HRMS\Models\EmployeeVacation::$approvable_disable_result ?? []))
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
                                        <textarea class="form-control @error('reason.' . $approvable->id) is-invalid @enderror" type="text" name="reason[{{ $approvable->id }}]" placeholder="Alasan ..." style="height: 38px; min-height: 38px;" @if ($leave->trashed()) disabled @endif>{{ old('reason.' . $approvable->id, $approvable->reason) }}</textarea>
                                        @error('reason.' . $approvable->id)
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-auto">
                                        <button class="btn btn-soft-danger @if ($leave->trashed()) disabled @endif"><i class="mdi mdi-check"></i> Simpan</button>
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
            'Nama karyawan' => $leave->employee->user->name,
            'NIP' => $leave->employee->kd ?: '-',
            'Jabatan' => $leave->employee->position->position->name ?? '-',
            'Departemen' => $leave->employee->position->position->department->name ?? '-',
            'Atasan' => $leave->employee->position->position->parents->last()?->employees->first()->user->name ?? null,
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
        @if ($leave->trashed())
            <form class="form-block form-confirm" action="{{ route('hrms::service.leave.manage.restore', ['leave' => $leave->id]) }}" method="post"> @csrf @method('put')
                <button class="btn btn-outline-danger w-100 text-danger d-flex align-items-center bg-white py-3 text-start">
                    <i class="mdi mdi-trash-can-outline me-3"></i>
                    <div>Pulihkan pengajuan <br> <small class="text-muted">Aktifkan kembali data pengajuan dari daftar</small></div>
                </button>
            </form>
        @else
            <form class="form-block form-confirm" action="{{ route('hrms::service.leave.manage.destroy', ['leave' => $leave->id]) }}" method="post"> @csrf @method('delete')
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
        let quota = {!! $leave->category->meta?->quota ?: 365 !!};
        let meta = {}

        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('fields-options-add').addEventListener('click', addRow);
        });

        const toggleAddButtonBasedQuota = () => {
            document.getElementById('fields-options-add').classList.toggle('disabled', !(tbody.children.length < quota))
            document.getElementById('fields-options-add').classList.toggle('text-danger', !(tbody.children.length >= quota))
            document.getElementById('fields-options-add').classList.toggle('text-muted', !(tbody.children.length < quota))
        }

        const addRow = () => {
            let tr = document.querySelector('#fields-options-template');
            let length = tbody.children.length;
            if (length < quota) {
                tbody.insertAdjacentHTML('beforeend', tr.innerHTML);
                Array.from(tbody.children).forEach((el, i) => {
                    if (i > 0) {
                        if (i >= length) {
                            el.querySelector('input').value = ''
                        }
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
    </script>
@endpush
