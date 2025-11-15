@extends('portal::layouts.default')

@section('title', 'Lembur | ')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('portal::overtime.submission.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Lembur</h2>
            <div class="text-muted">Berikut adalah informasi detail pengajuan lembur karyawan!</div>
        </div>
    </div>
    @if ($overtime->trashed())
        <div class="alert alert-danger border-0">
            <strong>Perhatian!</strong> Pengajuan ini telah dihapus, Anda tidak lagi dapat mengelola pengajuan ini.
        </div>
    @endif
    <div class="row">
        <div class="col-xl-8">
            @if ($overtime->dates || is_null($overtime->accepted_at))
                <div class="card mb-3 border-0">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div><i class="mdi mdi-eye-outline"></i> Detail pengajuan</div>
                    </div>
                    <div class="card-body border-top">
                        <div class="row gy-4 mb-4">
                            <div class="col-md-6">
                                <div class="small text-muted">Tanggal pengajuan</div>
                                <div class="fw-bold"> {{ $overtime->created_at->formatLocalized('%A, %d %B %Y') }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="small text-muted">Nama kegiatan</div>
                                <div class="fw-bold"> {{ $overtime->name }}</div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="small text-muted mb-1">Tanggal lembur yang diajukan</div>
                            <div>
                                @if ($overtime->schedules)
                                    @foreach ($overtime->schedules as $date)
                                        <span class="badge bg-soft-secondary text-dark fw-normal user-select-none" @isset($date['f']) data-bs-toggle="tooltip" title="Sebagai freelancer" @endisset style="font-size: 14px;">
                                            @isset($date['f'])
                                                <i class="mdi mdi-account-network-outline text-danger"></i>
                                            @endisset
                                            {{ strftime('%d %B %Y', strtotime($date['d'])) }}
                                            @isset($date['t_s'])
                                                pukul {{ $date['t_s'] }}
                                            @endisset
                                            @isset($date['t_e'])
                                                s.d. {{ $date['t_e'] }}
                                            @endisset
                                        </span>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="small text-muted mb-1">Pelaksanaan lembur</div>
                            <div>
                                @if ($overtime->dates)
                                    @foreach ($overtime->dates as $date)
                                        <span class="badge bg-soft-secondary text-dark fw-normal user-select-none" @isset($date['f']) data-bs-toggle="tooltip" title="Sebagai freelancer" @endisset style="font-size: 14px;">
                                            @isset($date['f'])
                                                <i class="mdi mdi-account-network-outline text-danger"></i>
                                            @endisset
                                            {{ strftime('%d %B %Y', strtotime($date['d'])) }}
                                            @isset($date['t_s'])
                                                pukul {{ $date['t_s'] }}
                                            @endisset
                                            @isset($date['t_e'])
                                                s.d. {{ $date['t_e'] }}
                                            @endisset
                                        </span>
                                    @endforeach
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="small text-muted mb-1">Deskripsi/catatan/alasan</div>
                            <div class="fw-bold">{{ $overtime->description ?: '-' }}</div>
                        </div>
                        <div class="mb-4">
                            <div class="small text-muted mb-1">Status</div>
                            <div>@include('portal::overtime.components.status', ['overtime' => $overtime])</div>
                        </div>
                        <div>
                            <div class="small text-muted mb-1">Lampiran</div>
                            @if (isset($overtime->attachment) && Storage::exists($overtime->attachment))
                                <a href="{{ Storage::url($overtime->attachment) }}" target="_blank"><i class="mdi mdi-file-link-outline"></i> Lihat lampiran</a>
                            @else
                                <div> Tidak diunggah </div>
                            @endif
                        </div>
                    </div>
                    @if ($overtime->approvables->count())
                        <div class="card-header border-top d-none d-md-block border-0">
                            <div class="row">
                                <div class="col-md-6 small text-muted"> Penanggungjawab </div>
                                <div class="col-md-6 small text-muted"> Persetujuan </div>
                            </div>
                        </div>
                        <div class="card-body border-top">
                            @foreach ($overtime->approvables as $approvable)
                                <div class="row gy-2 @if (!$loop->last) mb-4 @endif">
                                    <div class="col-md-6">
                                        <div class="text-muted small mb-1">
                                            {{ ucfirst($approvable->type) }} #{{ $approvable->level }}
                                        </div>
                                        <strong>{{ $approvable->userable->getApproverLabel() }}</strong>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="h-100 d-sm-flex align-items-center">
                                            <div class="align-self-center badge bg-{{ $approvable->result->color() }} fw-normal text-white"><i class="{{ $approvable->result->icon() }}"></i> {{ $approvable->result->label() }}</div>
                                            <div class="ms-sm-3 mt-sm-0 mt-2">{{ $approvable->reason }}</div>
                                        </div>
                                    </div>
                                </div>
                                @if ($approvable->history)
                                    <div class="row">
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
                    @endif
                </div>
            @endif
            @if (!$overtime->dates && $overtime->accepted_at)
                <div class="card border-0">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div><i class="mdi mdi-eye-outline"></i> Realisasi lembur</div>
                    </div>
                    <div class="card-body border-top">
                        <form class="form-confirm form-block" action="{{ route('portal::overtime.submission.update', ['overtime' => $overtime->id]) }}" method="post" enctype="multipart/form-data"> @csrf @method('put')
                            <div class="row mb-3">
                                <label class="col-md-4 col-lg-3 col-form-label required">Pekerjaan</label>
                                <div class="col-md-8">
                                    <div class="tg-steps-overtime-name">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $overtime->name) }}" required>
                                        @error('name')
                                            <div class="small text-danger mb-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-4 col-lg-3 col-form-label required">Waktu</label>
                                <div class="col-md-8">
                                    <div class="tg-steps-overtime-dates" id="dates">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div class="flex-grow-1">
                                                <div class="row gy-1 me-2">
                                                    <div class="col-sm-5">
                                                        <input type="date" class="form-control @error('dates.d.0') is-invalid @enderror" name="dates[d][]" max="{{ date('Y-m-d') }}" value="{{ old('dates.d.0') }}" required>
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <div class="input-group">
                                                            <input type="time" class="form-control @error('dates.s.0') is-invalid @enderror" name="dates[s][]" onchange="changeMinTime(event)" value="{{ old('dates.s.0') }}" required>
                                                            <div class="input-group-text">s.d.</div>
                                                            <input type="time" class="form-control @error('dates.e.0') is-invalid @enderror" name="dates[e][]" onchange="changeMaxTime(event)" value="{{ old('dates.e.0') }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-light text-danger rounded-circle btn-add px-2 py-1"><i class="mdi mdi-plus"></i></button>
                                        </div>
                                    </div>
                                    @error('dates.*.*')
                                        <div class="small text-danger mb-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-4 col-lg-3 col-form-label">Deskripsi</label>
                                <div class="col-md-8">
                                    <div class="tg-steps-overtime-description">
                                        <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="4" placeholder="Silakan tulis realisasi kegiatan dan keterangan/alasan/catatan kegiatan kamu. ...">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-4 col-lg-3 col-form-label">Lampiran</label>
                                <div class="col-md-8">
                                    <div class="tg-steps-overtime-attachment">
                                        <input class="form-control @error('attachment') is-invalid @enderror" name="attachment" type="file" id="upload-input" accept="image/*,application/pdf">
                                        <small class="text-muted">Berkas berupa .jpg, .png atau .pdf maksimal berukuran 2mb</small>
                                        @error('attachment')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3 pt-3">
                                <div class="col-lg-8 offset-lg-4 offset-xl-3">
                                    <button class="btn btn-soft-danger"><i class="mdi mdi-exit-to-app"></i> Ajukan</button>
                                    <a class="btn btn-ghost-light text-dark" href="{{ request('next', route('portal::overtime.submission.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-xl-4">
            {{-- {{dd($overtime->employee)}} --}}
            @include('portal::components.employee-detail-card', ['employee' => $overtime->employee])
            <div class="mb-3">
                @if ($overtime->empl_id == Auth::user()->employee->id && is_null($overtime->accepted_at))
                    <form class="form-block form-confirm" action="{{ route('portal::overtime.submission.approve', ['overtime' => $overtime->id]) }}" method="post"> @csrf @method('put')
                        <button class="btn btn-outline-success w-100 text-success d-flex align-items-center bg-white py-3 text-start">
                            <i class="mdi mdi-check me-3"></i>
                            <div>Terima lembur <br> <small class="text-muted">Terima instruksi lembur dari atasan.</small></div>
                        </button>
                    </form>
                @endif
            </div>

            @unless ($overtime->hasAnyApprovableResultIn('REJECT') || !$overtime->hasApprovables() || $overtime->trashed())
                @if ($overtime->hasAllApprovableResultIn('PENDING') || $overtime->hasAnyApprovableResultIn('REVISION') || !$overtime->hasApprovables())
                    <form class="form-block form-confirm" action="{{ route('portal::overtime.submission.destroy', ['overtime' => $overtime->id]) }}" method="post"> @csrf @method('delete')
                        <button class="btn btn-outline-danger w-100 text-danger d-flex align-items-center bg-white py-3 text-start">
                            <i class="mdi mdi-trash-can-outline me-3"></i>
                            <div>Batalkan pengajuan <br> <small class="text-muted">Hapus data pengajuan {{ $overtime->hasApprovables() ? 'sebelum disetujui oleh atasan' : '' }}</small></div>
                        </button>
                    </form>
                @endif
            @endunless
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
        const schedules = @JSON($overtime->schedules);

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

        const addRow = (e = null) => {
            const datesContainer = document.getElementById('dates');
            const dateInputs = datesContainer.querySelectorAll('.dates-input');
            if (dateInputs.length < max_dates) {
                datesContainer.insertAdjacentHTML('beforeend', document.getElementById('dates-template').innerHTML);
                if (e) {
                    e.currentTarget.classList.toggle('disabled', dateInputs.length + 1 === max_dates);
                }
            }
        };

        const renderSchedule = () => {
            const datesContainer = document.getElementById('dates');

            // Add rows if the schedules array has more than one item
            for (let i = 1; i < schedules.length; i++) {
                addRow();
            }

            // Populate values for each schedule entry
            ['d', 's', 'e'].forEach((key, keyIndex) => {
                datesContainer.querySelectorAll(`[name="dates[${key}][]"]`).forEach((element, index) => {
                    element.value = keyIndex === 0 ? schedules[index].d : keyIndex === 1 ? schedules[index].t_s : schedules[index].t_e;
                });
            });
        };

        window.addEventListener('DOMContentLoaded', () => {
            renderSchedule();
            const addButton = document.querySelector('#dates .btn-add');
            addButton.addEventListener('click', (e) => {
                addRow(e);
            });
            if (document.getElementById('dates').querySelectorAll('.dates-input').length >= max_dates) {
                addButton.classList.add('disabled');
            }
        });
    </script>
@endpush
