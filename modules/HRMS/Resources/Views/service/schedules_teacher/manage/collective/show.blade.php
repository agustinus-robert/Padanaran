@extends('hrms::layouts.default')

@section('title', 'Jadwal kerja | ')
@section('container-type', 'container-fluid px-5')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('hrms::service.attendance.schedules.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Lihat jadwal piket</h2>
            <div class="text-secondary">Anda dapat mengubah jadwal piket dengan mengisi formulir di bawah</div>
        </div>
    </div>
    <div class="card mb-4 border-0">
        <div class="card-body">
            <form class="form-block" action="{{ route('hrms::service.attendance.schedules.store', ['next' => request('next')]) }}" method="POST"> @csrf
                <input type="hidden" name="empl_id" value="{{ $duty->id }}" readonly>
                <div class="row required mb-3">
                    <label class="col-lg-4 col-xl-3 col-form-label">Nama lengkap</label>
                    <div class="col-xl-8 col-xxl-6">
                        <input type="text" class="form-control" value="{{ $duty->user->name }}" readonly />
                    </div>
                </div>
                {{-- <div class="row required mb-3">
                    <label class="col-lg-4 col-xl-3 col-form-label">Periode</label>
                    <div class="col-xl-8 col-xxl-6">
                        <input type="month" class="form-control @error('month') is-invalid @enderror" name="month" value="{{ old('month', request('month', date('Y-m'))) }}" readonly required />
                        @error('month')
                            <small class="text-danger d-block"> {{ $message }} </small>
                        @enderror
                    </div>
                </div> --}}
                <div class="mb-3" style="max-height: 480px; overflow-y: auto;">
                    <div class="row d-none d-lg-block sticky-top bg-white pb-3">
                        <div class="col-xl-9 offset-lg-4 offset-xl-3">
                            {{-- <div class="row">
                                @foreach ($workshifts as $shift)
                                    <div class="col-md-{{ 12 / count($workshifts) }} fw-bold text-center">{{ $shift->label() }}</div>
                                @endforeach
                            </div> --}}
                        </div>
                    </div>

                    <table class="table">
                        <thead>
                            <th>Tanggal</th>
                            <th>Jadwal</th>
                        </thead>

                        @foreach ($allDates as $momentas => $schedule)
                            <tbody>
                                <tr>
                                    <td>
                                        <label class="col-lg-6 col-xl-3 col-form-label {{ $momentas ? 'text-black' : '' }}">
                                            <span @if ($momentas) data-bs-toggle="tooltip" title="{{ $momentas }}" data-bs-placement="right" @endif>
                                                {{ strftime('%A, %d %B %Y', strtotime($momentas)) }} @if ($momentas)
                                                    <i class="mdi mdi-information-outline"></i>
                                                @endif
                                            </span>
                                        </label>
                                    </td>

                                    <td>
                                        @php
                                            $shiftRooms = $schedule[2] ?? [];

                                            $defaultShifts = [1 => null, 2 => null];
                                            $shiftRooms = array_replace($defaultShifts, $shiftRooms);
                                        @endphp

                                        @foreach($shiftRooms as $shift => $room)
                                            @php
                                                $badgeClass = is_null($room) ? 'bg-secondary' : 'bg-primary';
                                                $roomText = $room == 1 ? 'putri' : ($room == 2 ? 'putra' : '');
                                            @endphp
                                            <div class="d-inline-block me-2 mb-1">
                                                <span class="badge {{ $badgeClass }} text-light">
                                                    Shift-{{ $shift }} - Ruang: {{ $room ?? '-' }} {{ $roomText }}
                                                    <a href="javascript:void(0)"
                                                    class="badge bg-warning ms-2 open-modal-edit"
                                                    data-url="{{ route('hrms::service.teacher.duty.update', ['duty' => $duty->id]) }}"
                                                    data-room="{{ $room }}"
                                                    data-date="{{ $momentas }}"
                                                    data-shift="{{ $shift }}">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </a>
                                                    @if(!is_null($room))
                                                        <a href="javascript:void(0)"
                                                        class="badge bg-danger ms-1 open-delete-confirm"
                                                        data-date="{{ $momentas }}"
                                                        data-shift="{{ $shift }}">
                                                            <i class="mdi mdi-trash-can-outline"></i>
                                                        </a>
                                                    @endif
                                                </span>
                                            </div>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        @endforeach
                    </table>
                </div>
            </form>
        </div>
    </div>
@endsection

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Shift & Ruangan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      
      <form id="edit-form" method="POST">
            <div class="modal-body">
                @csrf
                @method('PUT')

                <input type="hidden" name="date" id="edit-date">
                <input type="hidden" name="shift" id="edit-shift">

                <div class="mb-3">
                    <label for="shift" class="form-label">Tanggal</label>
                    <div id="showDate"></div>
                </div>

                <div class="mb-3">
                    <label for="shift" class="form-label">Shift</label>
                    <div id="showShift"></div>
                </div>

                <div class="mb-3">
                    <label for="room" class="form-label">Ruang</label>
                    <select class="form-control" name="room" id="edit-room">
                        <option value="1">Putri</option>
                        <option value="2">Putra</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary" id="save-edit">Simpan</button>
            </div>
        </form>
    </div>
  </div>
</div>


<script>
    const scheduleData = @json($duty->schedulesDutyTeacher->first()->dates);

    document.addEventListener('DOMContentLoaded', function () {
        const saveBtn = document.getElementById('save-edit');
        const shiftSelect = document.getElementById('edit-shift');
        const roomSelect = document.getElementById('edit-room');
        let currentDate = null;

        function validate() {
            if (currentDate && scheduleData[currentDate] && scheduleData[currentDate][2]) {
                const shifts = scheduleData[currentDate][2];
                const selectedShift = shiftSelect.value;
                const selectedRoom = roomSelect.value;

                if (shifts[selectedShift] && String(shifts[selectedShift]) === String(selectedRoom)) {
                    saveBtn.disabled = true;
                    saveBtn.title = "Shift dan Ruang ini sudah terdaftar";
                } else {
                    saveBtn.disabled = false;
                    saveBtn.title = "";
                }
            } else {
                saveBtn.disabled = false;
                saveBtn.title = "";
            }
        }

        document.querySelectorAll('.open-modal-edit').forEach(button => {
            button.addEventListener('click', function () {
                const date  = this.getAttribute('data-date');
                const shift = this.getAttribute('data-shift');
                const room  = this.getAttribute('data-room');
                const formUrl = this.getAttribute('data-url'); // 🔹 ambil URL dari tombol

                currentDate = date;

                // Set URL ke form
                document.getElementById('edit-form').setAttribute('action', formUrl);
                // atau kalau mau pakai data-url
                // document.getElementById('edit-form').setAttribute('data-url', formUrl);

                const dateObj = new Date(date);
                const formattedTanggal = dateObj.toLocaleDateString('id-ID', {
                    weekday: 'long',   
                    day: 'numeric',   
                    month: 'long',    
                    year: 'numeric'   
                });

                document.getElementById('edit-date').value = date;
                document.getElementById('showDate').innerHTML = formattedTanggal;
                document.getElementById('showShift').innerHTML = 'shift-'+shift

                shiftSelect.value = shift;
                shiftSelect.dispatchEvent(new Event('change'));

                roomSelect.value = room;
                roomSelect.dispatchEvent(new Event('change'));

                validate();

                const modal = new bootstrap.Modal(document.getElementById('editModal'));
                modal.show();
            });
        });

        shiftSelect.addEventListener('change', validate);
        roomSelect.addEventListener('change', validate);
    });

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.open-delete-confirm').forEach(button => {
            button.addEventListener('click', async function () {
                const date  = this.getAttribute('data-date');
                const shift = this.getAttribute('data-shift');
                const url   = "{{ route('hrms::service.teacher.duty.destroy.one', ['duty' => $duty->id]) }}";

                if (!confirm(`Yakin hapus jadwal shift-${shift} pada tanggal ${date}?`)) {
                    return;
                }

                try {
                    const response = await fetch(url, {
                        method: 'POST', 
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            _method: 'DELETE',
                            date: date,
                            shift: shift
                        })
                    });

                    if (!response.ok) {
                        throw new Error('Gagal menghapus jadwal');
                    }

                    // Hapus elemen dari DOM
                    const cell = this.closest('.shift-cell');
                    if (cell) {
                        cell.innerHTML = ''; // atau bisa tampilkan teks kosong
                    }

                    alert('Jadwal berhasil dihapus');
                    location.reload(); 
                } catch (error) {
                    alert(error.message);
                }
            });
        });
    });



</script>
