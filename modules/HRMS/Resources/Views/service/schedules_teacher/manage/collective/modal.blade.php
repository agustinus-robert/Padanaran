
    {{-- Tampilkan ke user --}}
<form method="POST" action="{{ route('hrms::service.teacher.duty.store') }}" enctype="multipart/form-data">
    @csrf
 <div class="modal-body">
    <p class="d-none"><strong>Hari:</strong> <span id="modalDate"></span></p>
    <p class="d-none"><strong>Shift ID:</strong> <span id="modalShiftId"></span></p>

    {{-- Simpan ke backend --}}
    <input type="hidden" name="empl_type" value="guru">
    <input type="hidden" class="form-control d-none" name="schedule_month" value="{{ request()->get('month', $month->format('Y-m')) }}">
    <input type="hidden" name="shift_id" id="inputShiftId">
    <input type="hidden" name="date" id="inputDate">
    <input type="hidden" name="emp_check" value="{{ request('is_active') ? 'checked' : '' }}" />
    <input type="hidden" id="show_json" name="dates"></textarea>

    <div class="form-group">
        <label>Pilih Shift</label>
        <select id="emp_shift" name="shift" class="form-select mb-2" required>
            <option value="">Pilih Shift</option>
            @foreach($shiftDatabs as $kshift => $vshift)
                <option value="{{ $vshift->id }}">{{$vshift->name}}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Pilih Guru</label>
        <select id="emp_change" name="empl_id" class="form-select mb-2" required>
            <option value="">Pilih Guru</option>
            @foreach($employees as $value)
                <option value="{{ $value->id }}">{{ $value->user->name }}</option>
            @endforeach
        </select> 
    </div>


    <div class="form-group">
        <label>Lokasi Piket</label>
        <div id="showRoom"></div>
        <input type="hidden" name="room_id" />
    </div>                        
 </div>

 <div class="modal-footer">
    <button type="submit" class="btn btn-soft-danger">
        <i class="mdi mdi-content-save-move-outline"></i> Simpan
    </button>
 </div>
</form>