<x-regular-modal id="applyAll" title="Pembayaran Keseluruhan Murid">
    <form id="formAll" method="POST" action="{{ route('administration::bill.students.store') }}">
        @csrf
        <input type="hidden" name="status" value="1" />
        <input type="hidden" name="education" value="{{ request()->education }}" />

        {{-- Semester --}}
        <x-input-group :isRow="true">
            <x-col size="6">
                <x-label value="Masukkan Semester" />
            </x-col>
            <x-col size="6">
                <x-select id="semester_id" name="semester_id" placeholder="Pilih" :options="[]" required />
            </x-col>
        </x-input-group>

        {{-- Gelombang --}}
        <x-input-group :isRow="true">
            <x-col size="6">
                <x-label value="Masukkan Gelombang" />
            </x-col>
            <x-col size="6">
                <x-select id="batch_id" name="batch_id" placeholder="Pilih" :options="[]" required />
            </x-col>
        </x-input-group>

        {{-- Paket Pembayaran --}}
        <x-input-group :isRow="true">
            <x-col size="6">
                <x-label value="Masukkan Paket Pembayaran" />
            </x-col>
            <x-col size="6">
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