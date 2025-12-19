
<form method="POST" action="{{ route('hrms::service.teacher.duty.store') }}" enctype="multipart/form-data">
    @csrf
    <p class="d-none"><strong>Hari:</strong> <span id="modalDate"></span></p>
    <p class="d-none"><strong>Shift ID:</strong> <span id="modalShiftId"></span></p>

    <input type="hidden" name="empl_type" value="guru">
    <input type="hidden" class="form-control d-none" name="schedule_month" value="{{ request()->get('month', $month->format('Y-m')) }}">
    <input type="hidden" name="shift_id" id="inputShiftId">
    <input type="hidden" name="date" id="inputDate">
    <input type="hidden" name="emp_check" value="{{ request('is_active') ? 'checked' : '' }}" />
    <input type="hidden" id="show_json" name="dates"></textarea>

    <div class="form-group">
        <x-label value="Pilih Shift" />
        <x-select
            id="emp_shift"
            name="shift"
            class="mb-2"
            placeholder="Pilih Shift"
            :required="true"
            :options="$shiftDatabs->map(fn($shift) => [
                'value' => $shift->id,
                'label' => $shift->name,
            ])"
        />
    </div>

    <div class="form-group">
        <x-label value="Pilih Guru" />
        <x-select
            id="emp_change"
            name="empl_id"
            class="mb-2"
            placeholder="Pilih Guru"
            :required="true"
            :options="$employees->map(fn($emp) => [
                'value' => $emp->id,
                'label' => $emp->user->name,
            ])"
        />
    </div>


    <div class="form-group">
        <x-label value="Lokasi Piket" />
        <div class="p-2" id="showRoom"></div>
        <input type="hidden" name="room_id" />
    </div>

    <div class="mt-3 text-center">
        <x-btn type="dark" variant="success">Simpan</x-btn>
    </div>
</form>
