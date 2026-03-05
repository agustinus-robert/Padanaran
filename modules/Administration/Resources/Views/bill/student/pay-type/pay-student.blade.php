<x-regular-modal size="xl" id="applyStudent" title="Pembayaran Per Murid">
    <form id="formApply" method="POST" action="{{ route('administration::bill.students.store') }}">
        @csrf
        <input type="hidden" name="status" value="2" />
        <input type="hidden" name="education" value="{{ request()->education }}" />

        <div class="row">
            {{-- KIRI 4 --}}
            <div class="col-md-6">
                {{-- Pilih Kelas --}}
                <x-input-group :isRow="true">
                    <x-col size="4">
                        <x-label value="Pilih Kelas" />
                    </x-col>
                    <x-col size="8">
                        <x-select id="class_id" name="class_id" placeholder="Pilih" :options="[]" required />
                    </x-col>
                </x-input-group>

                {{-- Pilih Rombel --}}
                <x-input-group :isRow="true">
                    <x-col size="4">
                        <x-label value="Pilih Rombel" />
                    </x-col>
                    <x-col size="8">
                        <x-select id="classroom_id" name="classroom_id" placeholder="Pilih" :options="[]" required />
                    </x-col>
                </x-input-group>

                {{-- Masukkan Semester --}}
                <x-input-group :isRow="true">
                    <x-col size="4">
                        <x-label value="Masukkan Semester" />
                    </x-col>
                    <x-col size="8">
                        <x-select id="semester_id" name="semester_id" placeholder="Pilih" :options="[]" required />
                    </x-col>
                </x-input-group>
            </div>

            {{-- KANAN 8 --}}
            <div class="col-md-6">
                <div class="mb-3" :isRow="true">
                    <label>Pilih Siswa</label>
                    <x-select id="student_id" name="student_id" placeholder="Pilih" :options="[]" required />
                </div>

                <div class="mb-3">
                    <label>Kegiatan</label>
                    <input type="text" name="activity" class="form-control" placeholder="Masukkan kegiatan" required>
                </div>

                <div class="mb-3">
                    <label>Biaya</label>
                    <input type="number" name="cost" class="form-control" placeholder="Masukkan biaya" required>
                </div>
            </div>
        </div>

        {{-- Tombol --}}
        <div class="d-flex justify-content-end gap-2 mt-3">
            <x-btn type="button" variant="secondary" data-bs-dismiss="modal">Tutup</x-btn>
            <x-btn type="submit" variant="success">Proses</x-btn>
        </div>
    </form>
</x-regular-modal>
