@extends('finance::layouts.default')

@section('title', ($status == '' ? 'Lihat' : 'Edit') . ' rekap | ')
@section('navtitle', ($status == '' ? 'Lihat' : 'Edit') . ' rekap')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-8 col-xl-10">
            <div class="d-flex align-items-center mb-4">
                <a class="text-decoration-none" href="{{ request('next', url()->previous()) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                <div class="ms-4">
                    <h2 class="mb-1">{{ $status == 'edit' ? 'Edit' : 'Lihat' }} rekap</h2>
                    <div class="text-secondary">{{ $status == 'edit' ? 'Edit' : 'Lihat' }} rekap murid yang akan ditampilkan di selip gaji guru</div>
                </div>
            </div>
            <div class="card mb-4 border-0">
                {{-- <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <i class="mdi mdi-{{ $status == 'edit' ? 'pencil' : 'eye' }}"></i> {{ $status == 'edit' ? 'Edit' : 'Lihat' }} rekap koordinator
                    </div>
                </div> --}}
                <div class="card-body">
                    @if ($status == 'edit')
                        <a href="javascript:void(0)" id="addition" class="btn btn-danger mb-4">+ Tambah Field</a>
                    @endif
                    <form class="form-block" enctype="multipart/form-data" action="{{ route('administration::summary.coords.update', ['employee' => $employee->id, 'next' => request('next', route('administration::summary.coords.create'))]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="start_at" value="{{ $start_at }}" />
                        <input type="hidden" name="end_at" value="{{ $end_at }}" />
                        <input type="hidden" name="employee" value="{{ $employee->id }}" />

                        <table id="fieldTable">
                            <thead>
                                <tr>
                                    <th>Nama Murid</th>
                                    <th>Jumlah Jam</th>
                                    <th>Biaya Per Jam</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($attendance->result as $key => $values)
                                    @if ($key !== 'grand_total')
                                        <tr>
                                            <input type="hidden" name="field[]" />
                                            <td><input class="form-control" type="text" name="student_name[]" value="{{ $values->name }}"></td>
                                            <td><input class="form-control" type="number" name="hour[]" value="{{ $values->hourly }}"></td>
                                            <td><input class="form-control" type="number" name="price[]" value="{{ $values->price }}"></td>
                                            <td></td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>

                        @if ($userNow->position_id !== 3 && $status == 'edit')
                            <div class="row mt-4">
                                <div class="col-lg-8 offset-lg-4 offset-xl-3">
                                    <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                                    <a class="btn btn-ghost-light text-dark" href="{{ request('next', url()->previous()) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                                </div>
                            </div>
                        @endif

                    </form>

                    @if ($status !== 'edit')
                        @if ($userNow->position_id == 3)
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    @foreach ($attendance->approvables as $approvable)
                                        <div class="row gy-2 @if (!$loop->last) mb-4 @endif">
                                            <div class="col-md-12">
                                                <div class="text-muted small mb-1">
                                                    {{ ucfirst($approvable->type) }} #{{ $approvable->level }}
                                                </div>
                                                <strong>{{ $approvable->userable->getApproverLabel() }}</strong>
                                            </div>
                                            <div class="col-md-12">
                                                @if ($approvable->userable->is($userNow) && !$attendance->trashed())
                                                    <form class="form-block" action="{{ route('administration::summary.summary.coordinator.permission', ['recap' => $attendance->id, 'next' => request('next', route('administration::summary.teachings.index'))]) }}" method="post"> @csrf @method('PUT')
                                                        <div class="mb-3">
                                                            <select class="form-select @error('result') is-invalid @enderror" name="result">
                                                                @foreach ($results as $result)
                                                                    @unless (($approvable->cancelable && in_array($result, Modules\HRMS\Models\EmployeeRecapSubmission::$cancelable_disable_result)) || in_array($result, Modules\HRMS\Models\EmployeeRecapSubmission::$approvable_disable_result ?? []))
                                                                        <option value="{{ $result->value }}" @selected($result->value == old('result', $approvable->result->value))>{{ $result->label() }}</option>
                                                                    @endunless
                                                                @endforeach
                                                            </select>
                                                            @error('result')
                                                                <div class="text-danger small">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="mb-3">
                                                            <textarea class="form-control @error('reason') is-invalid @enderror" type="text" name="reason" placeholder="Alasan ...">{{ old('reason', $approvable->reason) }}</textarea>
                                                            @error('reason')
                                                                <div class="text-danger small">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                                                        <a class="btn btn-soft-secondary text-dark" href="{{ request('next', route('portal::schedule.submission.index')) }}"><i class="mdi mdi-arrow-left-circle-outline"></i> Kembali</a>
                                                    </form>
                                                @else
                                                    <div class="h-100 d-flex">
                                                        <div class="align-self-center badge bg-{{ $approvable->result->color() }} fw-normal text-white"><i class="{{ $approvable->result->icon() }}"></i> {{ $approvable->result->label() }}</div>
                                                    </div>
                                                @endif
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
                        @else
                            @foreach ($attendance->approvables as $approvable)
                                <div class="row p-0">
                                    <div class="col-md-12 offset-md-12">
                                        <hr class="text-muted mt-0">
                                        <p class="small text-muted mb-1">Catatan riwayat sebelumnya</p>
                                        @foreach ($results as $result)
                                            @unless (($approvable->cancelable && in_array($result, Modules\HRMS\Models\EmployeeRecapSubmission::$cancelable_disable_result)) || in_array($result, Modules\HRMS\Models\EmployeeRecapSubmission::$approvable_disable_result ?? []))
                                                @if ($result->value == $approvable->result->value)
                                                    {{ $result->label() }}
                                                @endif
                                            @endunless
                                        @endforeach
                                    </div>

                                    <div class="col-md-12 offset-md-12">
                                        <hr class="text-muted mt-0">
                                        <p class="small text-muted mb-1">Alasan</p>
                                        {{ $approvable->reason }}
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    @endif
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
        document.getElementById("addition").addEventListener("click", function() {
            let table = document.getElementById("fieldTable").getElementsByTagName('tbody')[0];

            // Buat elemen row baru
            let newRow = document.createElement("tr");
            newRow.innerHTML = `
            <input type="hidden" name="field[]" />
        <td><input class="form-control" type="text" name="student_name[]"></td>
        <td><input class="form-control" type="number" name="hour[]" value="0"></td>
        <td><input class="form-control" type="number" name="price[]" value="0"></td>
        <td><button type="button" class="btn btn-secondary remove-field">-</button></td>
    `;

            // Tambahkan row ke dalam tabel
            table.appendChild(newRow);
        });

        // Event delegation untuk tombol hapus field
        document.getElementById("fieldTable").addEventListener("click", function(event) {
            if (event.target.classList.contains("remove-field")) {
                let row = event.target.closest("tr");
                if (row && document.querySelectorAll("#fieldTable tbody tr").length > 1) {
                    row.remove();
                }
            }
        });
    </script>
@endpush
