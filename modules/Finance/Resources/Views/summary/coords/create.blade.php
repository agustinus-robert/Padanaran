@extends('finance::layouts.default')

@section('title', 'Tambah rekap | ')
@section('navtitle', 'Tambah rekap')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-8 col-xl-10">
            <div class="d-flex align-items-center mb-4">
                <a class="text-decoration-none" href="{{ request('next', url()->previous()) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                <div class="ms-4">
                    <h2 class="mb-1">Tambah rekap</h2>
                    <div class="text-secondary">Silakan isi formulir di bawah ini untuk membuat rekap koordinator</div>
                </div>
            </div>
            <div class="card mb-4 border-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <i class="mdi mdi-plus"></i> Tambah rekap koordinator
                    </div>
                </div>
                <div class="card-body">
                    <a href="javascript:void(0)" id="addition" class="btn btn-danger mb-4">+ Tambah Field</a>
                    <form class="form-block" enctype="multipart/form-data" action="{{ route('administration::summary.coords.store', ['next' => request('next', route('administration::summary.coords.create'))]) }}" method="POST"> @csrf
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
                                <input type="hidden" name="field[]" />
                                <td><input class="form-control" type="text" name="student_name[]"></td>
                                <td><input class="form-control" type="number" name="hour[]" value="0"></td>
                                <td><input class="form-control" type="number" name="price[]" value="0"></td>
                                <td></td>
                            </tbody>
                        </table>

                        <div class="row mt-4">
                            <div class="col-lg-8 offset-lg-4 offset-xl-3">
                                <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                                <a class="btn btn-ghost-light text-dark" href="{{ request('next', url()->previous()) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                            </div>
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
        document.getElementById("addition").addEventListener("click", function() {
            let table = document.getElementById("fieldTable").getElementsByTagName('tbody')[0];

            // Buat elemen row baru
            let newRow = document.createElement("tr");
            newRow.innerHTML = `
            <input type="hidden" name="field[]" />
        <td><input class="form-control" type="text" name="student_name[]"></td>
        <td><input class="form-control" type="text" name="hour[]" value="0"></td>
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
