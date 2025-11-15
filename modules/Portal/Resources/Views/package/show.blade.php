<div>
    <h6 class="fw-bold">Nama Paket:</h6>
    <p>{{ $package->name }}</p>

    <h6 class="fw-bold">Status:</h6>
    <p>{{ ucfirst($package->status) }}</p>

    <h6 class="fw-bold">Siswa:</h6>
    <select class="form-select" disabled>
        @foreach ($students as $student)
            <option value="{{ $student->id }}" {{ $package->student_id == $student->id ? 'selected' : '' }}>
                {{ $student->name }}
            </option>
        @endforeach
    </select>
</div>
