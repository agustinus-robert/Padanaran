<x-regular-modal id="applyClass" title="Pembayaran Per Kelas">
    <form id="formApply" method="POST" action="{{ route('administration::bill.students.store') }}">
        @csrf
        <input type="hidden" name="status" value="2" />
        <input type="hidden" name="education" value="{{ request()->education }}" />

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

        {{-- Masukkan Gelombang --}}
        <x-input-group :isRow="true">
            <x-col size="4">
                <x-label value="Masukkan Gelombang" />
            </x-col>
            <x-col size="8">
                <x-select id="batch_id" name="batch_id" placeholder="Pilih" :options="[]" required />
            </x-col>
        </x-input-group>

        {{-- Masukkan Paket Pembayaran --}}
        <x-input-group :isRow="true">
            <x-col size="4">
                <x-label value="Masukkan Paket Pembayaran" />
            </x-col>
            <x-col size="8">
                <x-select id="reference_id" name="package" placeholder="Pilih" :options="[]" required />
            </x-col>
        </x-input-group>

        {{-- Tombol Aksi --}}
        <div class="d-flex justify-content-end gap-2 mt-3">
            <x-btn type="button" variant="secondary" data-bs-dismiss="modal">Tutup</x-btn>
            <x-btn type="submit" variant="success">Proses</x-btn>
        </div>
    </form>
</x-regular-modal>