@extends('portal::layouts.default')

@section('title', 'Cuti | ')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('portal::vacation.submission.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Ajukan pembatalan</h2>
            <div class="text-muted">Silakan pilih tanggal yang akan dibatalkan melalui form di bawah ini!</div>
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
                <form id="cancelation-form" class="form-confirm form-block" action="{{ route('portal::vacation.cancelation.update', ['vacation' => $vacation->id]) }}" method="post"> @csrf @method('PUT')
                    <div class="row mb-3">
                        <label class="col-md-4 col-lg-3 col-form-label required">Jenis cuti/libur hari raya</label>
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header border-bottom-0 text-muted small text-uppercase">{{ $vacation->quota->category->type->label() }}</div>
                                <div class="list-group list-group-flush collapse show">
                                    <label class="list-group-item d-flex align-items-center">
                                        <input class="form-check-input me-3" type="radio" checked readonly>
                                        <div>
                                            <div class="fw-bold mb-0">{{ $vacation->quota->category->name }}</div>
                                            <div class="small text-muted">Jumlah tanggal cuti/libur hari raya sebanyak {{ $vacation->dates->count() }} hari</div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-md-4 col-lg-3 col-form-label required">Pilih tanggal cuti/libur hari raya</label>
                        <div class="col-md-8">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Hari</th>
                                        <th colspan="2">Tanggal</th>
                                        <th>Batalkan?</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($vacation->dates as $i => $date)
                                        <tr>
                                            <td>{{ strftime('%A', strtotime($date['d'])) }}</td>
                                            <td>{{ strftime('%d %B %Y', strtotime($date['d'])) }}</td>
                                            <td class="text-muted">{{ isset($date['f']) ? 'Freelance' : '' }}</td>
                                            <td class="py-1 text-center" width="50">
                                                @if ($vacation->cancelable_dates->contains('d', $date['d']))
                                                    <input type="checkbox" class="btn-check" id="dates-{{ $i }}" autocomplete="off" name="dates[]" value="{{ $date['d'] }}" required>
                                                    <label class="btn btn-outline-danger btn-sm rounded" for="dates-{{ $i }}"><i class="mdi mdi-trash-can-outline"></i></label>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="text-muted small">Anda hanya dapat membatalkan tanggal yang lebih dari {{ Modules\HRMS\Models\EmployeeVacation::$cancelable_day_limit }} hari dari sekarang</div>
                        </div>
                    </div>
                    <div class="row mb-3 pt-3">
                        <div class="col-lg-8 offset-lg-4 offset-xl-3">
                            <div class="form-check mb-3">
                                <input class="form-check-input" id="agreement" type="checkbox" required>
                                <label class="form-check-label" for="agreement">Dengan ini saya menyatakan data yang saya ajukan di atas adalah valid</label>
                            </div>
                            <button class="btn btn-soft-danger" id="cancelation-form-submit"><i class="mdi mdi-exit-to-app"></i> Ajukan pembatalan</button>
                            <a class="btn btn-ghost-light text-dark" href="{{ request('next', route('portal::vacation.submission.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Array.from(document.querySelectorAll('[name="dates[]"]')).map((el) => {
                el.addEventListener('change', (e) => {
                    e.target.parentNode.closest('tr').classList.toggle('text-secondary', e.target.checked)
                    Array.from(document.querySelectorAll('[name="dates[]"]')).map((checkbox) => {
                        checkbox.required = e.target.checked ? '' : 'required'
                    })
                });
            });
        });
    </script>
@endpush
