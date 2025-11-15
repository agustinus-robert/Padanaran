@extends('hrms::layouts.default')

@section('title', 'Kelola lembur | ')
@section('navtitle', 'Kelola lembur')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('hrms::service.overtime.manage.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Lihat detail pengajuan lembur</h2>
            <div class="text-secondary">Menampilkan tanggal pengajuan dan detail lainnya terkait lembur yang diajukan.</div>
        </div>
    </div>
    @if ($overtime->trashed())
        <div class="alert alert-danger border-0">
            <strong>Perhatian!</strong> Pengajuan ini telah dihapus, Anda tidak lagi dapat mengelola pengajuan ini.
        </div>
    @endif
    <div class="row">
        <div class="col-xl-8">
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-eye-outline"></i> Detail pengajuan
                </div>
                <div class="card-body border-top">
                    <form class="form-confirm form-block" @if (!$overtime->trashed()) action="{{ route('hrms::service.overtime.manage.update', ['overtime' => $overtime->id]) }}" method="post" @endif enctype="multipart/form-data"> @csrf @method('put')
                        <div class="row mb-3">
                            <label class="col-md-4 col-lg-3 col-form-label required">Pekerjaan</label>
                            <div class="col-md-8">
                                <input @if ($overtime->trashed()) disabled @endif type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $overtime->name) }}" required>
                                @error('name')
                                    <div class="small text-danger mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-lg-3 col-form-label required">Waktu</label>
                            <div class="col-md-8">
                                <div id="dates">
                                    @foreach ($overtime->dates as $i => $date)
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div class="flex-grow-1">
                                                <div class="row gy-1 me-2">
                                                    <div class="col-sm-5">
                                                        <input @if ($overtime->trashed()) disabled @endif type="date" class="form-control @error('dates.d.' . $i) is-invalid @enderror" name="dates[d][]" max="{{ date('Y-m-d') }}" value="{{ old('dates.d.0', $date['d']) }}" required>
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <div class="input-group">
                                                            <input @if ($overtime->trashed()) disabled @endif type="time" class="form-control @error('dates.s.' . $i) is-invalid @enderror" name="dates[s][]" onchange="changeMinTime(event)" value="{{ old('dates.s.0', $date['t_s']) }}" required>
                                                            <div class="input-group-text">s.d.</div>
                                                            <input @if ($overtime->trashed()) disabled @endif type="time" class="form-control @error('dates.e.' . $i) is-invalid @enderror" name="dates[e][]" onchange="changeMaxTime(event)" value="{{ old('dates.e.0', $date['t_e']) }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($i < 1)
                                                <button type="button" class="btn btn-light text-danger rounded-circle btn-add px-2 py-1"><i class="mdi mdi-plus"></i></button>
                                            @else
                                                <button type="button" class="btn btn-secondary rounded-circle btn-remove px-2 py-1" onclick="removeAttachment(event)"><i class="mdi mdi-minus"></i></button>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                @error('dates.*.*')
                                    <div class="small text-danger mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-lg-3 col-form-label">Deskripsi</label>
                            <div class="col-md-8">
                                <textarea @if ($overtime->trashed()) disabled @endif class="form-control @error('description') is-invalid @enderror" name="description" rows="4" placeholder="Silakan tulis realisasi kegiatan dan keterangan/alasan/catatan kegiatan Kamu. ...">{{ old('description', $overtime->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-lg-3 col-form-label">Lampiran</label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input @if ($overtime->trashed()) disabled @endif class="form-control @error('file') is-invalid @enderror" name="file" type="file" id="upload-input" accept="image/*,application/pdf">
                                    @if (Storage::url($overtime->attachment))
                                        <a class="btn btn-soft-dark" href="{{ Storage::url($overtime->attachment) }}" target="_blank"><i class="mdi mdi-file-link-outline"></i></a>
                                    @endif
                                </div>
                                <small class="text-muted">Kosongkan jika tidak ingin memperbarui lampiran yang telah diunggah</small>
                                @error('file')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3 pt-3">
                            <div class="col-lg-8 offset-lg-4 offset-xl-3">
                                <button class="btn btn-soft-danger" @if ($overtime->trashed()) disabled @endif><i class="mdi mdi-check"></i> Perbarui</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi- ogress-clock"></i> Status pengajuan/pembatalan
                </div>
                <div class="card-body border-top">
                    @foreach ($overtime->approvables as $approvable)
                        <div class="@if (!$loop->last) mb-4 @endif">
                            <div class="mb-3">
                                <div class="text-muted small mb-1">
                                    {{ ucfirst($approvable->type) }} #{{ $approvable->level }}
                                </div>
                                <strong>{{ $approvable->userable->getApproverLabel() }}</strong>
                            </div>
                            <div>
                                <form class="form-block" @if (!$overtime->trashed()) action="{{ route('hrms::service.overtime.manage.approvable.update', ['overtime' => $overtime->id, 'approvable' => $approvable->id]) }}" method="post" @endif> @csrf @method('PUT')
                                    <div class="row gy-3">
                                        <div class="col-xl-3 col-lg-4 col-md-5">
                                            <div class="input-group">
                                                <div class="input-group-text">Status</div>
                                                <select class="form-select @error('result.' . $approvable->id) is-invalid @enderror" name="result[{{ $approvable->id }}]" @if ($overtime->trashed()) disabled @endif>
                                                    @foreach ($results as $result)
                                                        <option value="{{ $result->value }}" @selected($result->value == old('result., $overtime->result, $overtime->result' . $approvable->id, $approvable->result->value))>{{ $result->label() }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('result.' . $approvable->id)
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-auto flex-grow-1">
                                            <textarea class="form-control @error('reason.' . $approvable->id) is-invalid @enderror" type="text" name="reason[{{ $approvable->id }}]" placeholder="Alasan ..." style="height: 38px; min-height: 38px;" @if ($overtime->trashed()) disabled @endif>{{ old('reason., $overtime->reason, $overtime->reason' . $approvable->id, $approvable->reason) }}</textarea>
                                            @error('reason.' . $approvable->id)
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-auto">
                                            <button class="btn btn-soft-danger @if ($overtime->trashed()) disabled @endif"><i class="mdi mdi-check"></i> Perbarui</button>
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
                    @foreach ([
            'Nama karyawan' => $overtime->employee->user->name,
            'NIP' => $overtime->employee->kd ?: '-',
            'Jabatan' => $overtime->employee->position->position->name ?? '-',
            'Departemen' => $overtime->employee->position->position->department->name ?? '-',
            'Atasan' => $overtime->employee?->position?->position?->parents?->last()?->employees?->first()?->user?->name ?? ('-' ?? '-'),
        ] as $label => $value)
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
            @if ($overtime->trashed())
                <form class="form-block form-confirm" action="{{ route('hrms::service.overtime.manage.restore', ['overtime' => $overtime->id]) }}" method="post"> @csrf @method('put')
                    <button class="btn btn-outline-danger w-100 text-danger d-flex align-items-center bg-white py-3 text-start">
                        <i class="mdi mdi-trash-can-outline me-3"></i>
                        <div>Pulihkan pengajuan <br> <small class="text-muted">Aktifkan kembali data pengajuan dari daftar</small></div>
                    </button>
                </form>
            @else
                <form class="form-block form-confirm" action="{{ route('hrms::service.overtime.manage.destroy', ['overtime' => $overtime->id]) }}" method="post"> @csrf @method('delete')
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
    <div id="dates-template" class="d-none">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="flex-grow-1 dates-input">
                <div class="row gy-1 me-2">
                    <div class="col-sm-5">
                        <input type="date" class="form-control" name="dates[d][]" max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-sm-7">
                        <div class="input-group">
                            <input type="time" class="form-control" name="dates[s][]" onchange="changeMinTime(event)">
                            <div class="input-group-text">s.d.</div>
                            <input type="time" class="form-control" name="dates[e][]" onchange="changeMaxTime(event)">
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-secondary rounded-circle btn-remove px-2 py-1" onclick="removeAttachment(event)"><i class="mdi mdi-minus"></i></button>
        </div>
    </div>

    <script>
        const max_dates = 5;

        let removeAttachment = (e) => {
            e.currentTarget.parentNode.remove();
            document.querySelector('#dates .btn-add').classList.toggle('disabled', document.getElementById('dates').querySelectorAll('.dates-input').length > max_dates);
        }

        let changeMinTime = (e) => {
            for (let sibling of e.target.parentNode.children) {
                if (sibling !== e.target) sibling.min = e.target.value;
            }
        }

        let changeMaxTime = (e) => {
            for (let sibling of e.target.parentNode.children) {
                if (sibling !== e.target) sibling.max = e.target.value;
            }
        }

        window.addEventListener('DOMContentLoaded', () => {

            document.querySelector('#dates .btn-add').addEventListener('click', (e) => {
                if (document.getElementById('dates').querySelectorAll('.dates-input').length < max_dates) {
                    document.getElementById('dates').insertAdjacentHTML('beforeend', document.getElementById('dates-template').innerHTML);
                    e.currentTarget.classList.toggle('disabled', document.getElementById('dates').querySelectorAll('.dates-input').length == max_dates)
                }
            });
        });
    </script>
@endpush
