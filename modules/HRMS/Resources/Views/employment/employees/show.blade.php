@extends('hrms::layouts.default')

@section('title', 'Karyawan | ')
@section('navtitle', 'Karyawan')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('hrms::employment.employees.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Lihat detail karyawan</h2>
            <div class="text-secondary">Menampilkan informasi karyawan, detail kontak, alamat, dan peran.</div>
        </div>
    </div>
    <div class="row">
         <div class="col-12 p-2">
            {{-- <div class="container"> --}}
                @if (Session::has('success'))
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                        <div class="alert alert-success">
                            {!! Session::get('success') !!}
                        </div>
                    </div>
                @endif 

                @if (Session::has('danger'))
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                        <div class="alert-danger alert">
                            {!! Session::get('danger') !!}
                        </div>
                    </div>
                @endif
            {{-- </div> --}}
        </div>

        <div class="col-sm-4">
            <div class="card border-0">
                <div class="card-body text-center">
                    <div class="py-4">
                        <img class="rounded-circle" src="{{ asset('img/default-avatar.svg') }}" alt="" width="128">
                    </div>
                    <h5 class="mb-1"><strong>{{ $employee->user->name }}</strong></h5>
                    <p>{{ $employee->user->username }}</p>
                    <h4 class="mb-0">
                        @if ($employee->user->getMeta('phone_whatsapp'))
                            <a class="text-danger px-1" href="https://wa.me/{{ $employee->user->getMeta('phone_code') . $employee->user->getMeta('phone_number') }}" target="_blank"><i class="mdi mdi-whatsapp"></i></a>
                        @endif
                        @if ($employee->user->email_verified_at)
                            <a class="text-danger px-1" href="mailto:{{ $employee->user->email_address }}"><i class="mdi mdi-email-outline"></i></a>
                        @endif
                    </h4>
                </div>
                <div class="list-group list-group-flush border-top">

                </div>
            </div>
            <div class="card border-0">
                <div class="card-body">
                    <h4 class="mb-1">Info karyawan</h4>
                    <p class="text-muted mb-2">Informasi detail karyawan a.n. {{ $employee->user->display_name }}</p>
                </div>
                <div class="list-group list-group-flush">
                    <div class="list-group-item border-0">
                        Bergabung pada <br> <strong> {{ $employee->joined_at?->diffForHumans() ?: '-' }}</strong>
                    </div>
                    <div class="list-group-item border-0">
                        Tgl penetapan karyawan <br> <strong> {{ $employee->permanent_at?->diffForHumans() ?: '-' }}</strong>
                    </div>
                    <div class="list-group-item border-0">
                        No penetapan karyawan <br> <strong> {{ $employee->permanent_kd ?: '-' }}</strong>
                    </div>
                    <div class="list-group-item border-0">
                        Perjanjian kerja saat ini <br>
                        <strong>
                            @if ($contract = $employee->contracts->filter(fn($contract) => $contract->is_active)->first())
                                <i class="mdi mdi-circle {{ $contract->is_active ? 'text-success' : 'text-danger' }}" style="font-size: 11pt;"></i> &nbsp; {{ $contract?->kd }}
                            @else
                                -
                            @endif
                        </strong>
                    </div>
                    <div class="list-group-item text-muted border-0">
                        <i class="mdi mdi-account-circle"></i> ID Karyawan : {{ $employee->id }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="card border-0">
                <div class="card-body"><i class="mdi mdi-account-details-outline"></i> Utama</div>
                <div class="card-body border-top">
                    <form class="form-block" action="{{ route('hrms::employment.employees.update', ['employee' => $employee->id]) }}" method="POST" enctype="multipart/form-data"> @csrf @method('PUT')
                        <fieldset class="mb-4">
                            <div class="row">
                                <div class="col-md-7 offset-md-4 offset-lg-3">
                                    <h5 class="text-muted font-weight-normal mb-3">Informasi umum</h5>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-4 col-lg-3 col-form-label">Nama lengkap</label>
                                <div class="col-md-7">
                                    <input class="form-control" value="{{ $employee->user->name }}" readonly />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-4 col-lg-3 col-form-label">Nomor ponsel</label>
                                <div class="col-md-5">
                                    <div class="input-group d-flex">
                                        <input type="text" class="form-control bg-light flex-grow-0" value="+{{ $employee->user->getMeta('phone_code', '62') }}" style="width: 100px;" readonly />
                                        <input type="text" class="form-control" value="{{ $employee->user->getMeta('phone_number') }}" readonly />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-7 offset-md-4 offset-lg-3">
                                    <small class="form-text text-muted">Beberapa informasi umum tidak bisa diubah, silakan hubungi Administrator utama untuk mengubah detail informasi.</small>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <div class="row">
                                <div class="col-md-7 offset-md-4 offset-lg-3">
                                    <h5 class="text-muted font-weight-normal mb-3">Data kepegawaian</h5>
                                </div>
                            </div>
                            <div class="row required mb-3">
                                <label class="col-md-4 col-lg-3 col-form-label">Tanggal bergabung</label>
                                <div class="col-md-4">
                                    <input type="datetime-local" class="form-control @error('joined_at') is-invalid @enderror" name="joined_at" value="{{ old('joined_at', $employee->joined_at?->toDateTimeLocalString()) }}">
                                    @error('joined_at')
                                        <small class="text-danger d-block"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-4 col-lg-3 col-form-label">Nomor Induk Karyawan</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control @error('kd') is-invalid @enderror" name="kd" value="{{ old('kd', $employee->kd) }}">
                                    @error('kd')
                                        <small class="text-danger d-block"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-4 col-lg-3 col-form-label">Nomor SPKT</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control @error('permanent_kd') is-invalid @enderror" name="permanent_kd" value="{{ old('permanent_kd', $employee->permanent_kd) }}">
                                    @error('permanent_kd')
                                        <small class="text-danger d-block"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-4 col-lg-3 col-form-label">Tanggal SPKT</label>
                                <div class="col-md-4">
                                    <input type="datetime-local" class="form-control @error('permanent_at') is-invalid @enderror" name="permanent_at" value="{{ old('permanent_at', $employee->permanent_at?->toDateTimeLocalString()) }}">
                                    @error('permanent_at')
                                        <small class="text-danger d-block"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-4 col-lg-3 col-form-label">Dokumen SPKT</label>
                                <div class="col-md-6">
                                    <div class="mb-1">
                                        <input class="form-control" name="files" type="file" id="upload-input" accept="application/pdf">
                                    </div>
                                    @error('files')
                                        <small class="text-danger d-block"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                        </fieldset>
                        <div class="row mb-3 mt-2">
                            <div class="col-lg-8 offset-lg-4 offset-xl-3">
                                <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                                <a class="btn btn-ghost-light text-dark" href="{{ request('next', route('hrms::employment.employees.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-body"><i class="mdi mdi-file-account-outline"></i> Perjanjian kerja</div>
                <div class="table-responsive border-top">
                    <table class="table-hover table">
                        <thead>
                            <tr>
                                <th>Nomor</th>
                                <th>Dibuat pada</th>
                                <th>Masa berlaku</th>
                                <th>Jabatan</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employee->contracts as $contract)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="pe-3">
                                                <i class="mdi mdi-circle {{ $contract->is_active ? 'text-success' : 'text-danger' }}" style="font-size: 11pt;"></i>
                                            </div>
                                            <div>
                                                <strong>{{ $contract?->kd }}</strong> <br>
                                                <small class="text-muted text-end">{{ $contract->contract->name }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle">{{ $contract->created_at->diffForHumans() }}</td>
                                    <td class="align-middle"><strong>{{ $contract->start_at->isoFormat('LL') }}</strong> s.d. <strong>{{ $contract->end_at?->isoFormat('LLL') ?: 'tidak ditentukan' }}</strong></td>
                                    <td class="align-middle">{{ $contract->position?->position->name ?: '-' }}</td>
                                    <td class="nowrap py-1 align-middle">
                                        <a class="btn {{ $contract->document ? 'btn-soft-info' : 'btn-light disabled' }} rounded px-2 py-1" @isset($contract->document) href="{{ $contract->document->url() ?: 'javascript:;' }}" download="{{ $contract->document->label }}" @endisset>
                                            <i class="mdi mdi-file-download-outline"></i>
                                        </a>
                                        {{-- @can('show', $contract)
                                            <a class="btn btn-soft-primary rounded px-2 py-1" href="{{ route('hrms::employment.contracts.show', ['contract' => $contract->id, 'next' => url()->full()]) }}"><i class="mdi mdi-eye-outline"></i></a>
                                        @endcan --}}
                                        @can('destroy', $contract)
                                            <form class="form-block form-confirm d-inline" action="{{ route('hrms::employment.contracts.destroy', ['contract' => $contract->id, 'next' => url()->full()]) }}" method="post"> @csrf @method('delete')
                                                <button class="btn btn-soft-danger rounded px-2 py-1" data-bs-toggle="tooltip" title="Hapus"><i class="mdi mdi-trash-can-outline"></i></button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted" colspan="5">Belum ada perjanjian kerja yang dibuat, silakan buat perjanjian kerja dengan klik tombol di bawah ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-body">
                    <a class="btn btn-soft-danger" href="{{ route('hrms::employment.contracts.create', ['employee' => $employee->id, 'next' => url()->full()]) }}"><i class="mdi mdi-plus"></i> Buat perjanjian kerja baru</a>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-body"><i class="mdi mdi-file-edit-outline"></i> Surat Keputusan (SK) Perusahaan</div>
                <div class="card-body border-top">
                    <span class="text-muted">Belum ada SK yang dibuat</span>
                </div>
            </div>
        </div>
    </div>
@endsection
