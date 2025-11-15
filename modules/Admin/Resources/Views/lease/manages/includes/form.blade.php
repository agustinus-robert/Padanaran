<div class="mb-3">
    <label class="form-label">Penerima</label>
    <select class="form-select @error('receiver_id') is-invalid @enderror" name="receiver_id">
        <option value="">-- Pilih --</option>
        @foreach ($employees as $employee)
            <option value="{{ $employee->user->id }}">{{ $employee->user->name }}</option>
        @endforeach
    </select>
    @error('receiver_id')
        <small class="text-danger d-block"> {{ $message }} </small>
    @enderror
</div>

<div class="row">
    <div class="col-6 mb-3">
        <label class="form-label required">Tanggal peminjaman</label>
        <input type="datetime-local" class="form-control @error('received_at') is-invalid @enderror" name="received_at" value="{{ old('received_at') }}" required>
        @error('received_at')
            <small class="text-danger d-block"> {{ $message }} </small>
        @enderror
    </div>

    <div class="col-6 mb-3">
        <label class="form-label">Tanggal pengembalian</label>
        <input type="datetime-local" class="form-control @error('returned_at') is-invalid @enderror" name="returned_at" value="{{ old('returned_at') }}">
        @error('returned_at')
            <small class="text-danger d-block"> {{ $message }} </small>
        @enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label required">Keperluan</label>
    <textarea class="form-control @error('for') is-invalid @enderror" name="for">{{ old('for') }}</textarea>
    @error('for')
        <small class="text-danger d-block"> {{ $message }} </small>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label required">Daftar inventaris yang dipinjam</label>
    <div id="asset-item"></div>
</div>

<div class="mb-2">
    <div class="text-muted mb-2">Silakan klik tombol di bawah untuk menambahkan barang baru. Maksimal peminjaman sejulah 16 item.</div>
    <button type="button" class="btn btn-sm btn-soft-warning btn-add rounded px-2 py-1"><i class="mdi mdi-plus"></i> Tambah barang</button>
</div>

<div class="mb-3">
    <label class="form-label required">Catatan</label>
    <textarea class="form-control @error('clause') is-invalid @enderror" name="clause" id="clause" rows="2">
    <ol>
        <li>Peralatan hanya digunakan pada jam dan hari kerja, serta lembur.</li>
        <li>Peralatan tidak boleh dipergunakan untuk mengerjakan pekerjaan pribadi atau freelance.</li>
        <li>Karyawan bertanggung jawab melakukan perawatan seperlunya.</li>
        <li>Apabila perjanjian hubungan kerja berakhir, karyawan bertanggung jawab mengembalikan seluruh fasilitas kerja yang diberikan kepada Perusahaan (PT PéMad International Transearch).</li>
    </ol>
    </textarea>
    @error('clause')
        <small class="text-danger d-block"> {{ $message }} </small>
    @enderror
</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/tom-select/css/tom-select.bootstrap5.min.css') }}">
    <style type="text/css">
        .ts-wrapper {
            padding: 0 !important;
        }

        .ts-control {
            border: 1px solid hsla(0, 0%, 82%, .2) !important;
        }
    </style>
@endpush

@push('scripts')
    <template id="template">
        <div class="required items-row mb-3">
            <div class="row gy-2 gx-2">
                <div class="col-xl-4 col-md-4">
                    <select class="form-select form-index @error('modelable_type') is-invalid @enderror" data-name="inv.modelable_type" onchange="listBorrowableId(event)" required>
                        <option>-- Pilih --</option>
                        @foreach ($borrowables as $borrowable)
                            <option value="{{ $borrowable->value }}" data-borrowable="{{ $borrowable->items->pluck('name', 'id') }}" @if (old('modelable_type') == $borrowable->value) selected @endif>{{ $borrowable->label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xl-7 col-md-7">
                    <select class="form-select form-index @error('modelable_id') is-invalid @enderror" data-name="inv.modelable_id" required>
                        <option>-- Pilih --</option>
                    </select>
                </div>
                <div class="col-lg-auto flex-shrink-1"><button type="button" class="btn btn-secondary rounded-circle btn-remove px-2 py-1" tabindex="-1" onclick="removeRow(event)"><i class="mdi mdi-minus"></i></button></div>
            </div>
            @error('modelable_type')
                <small class="text-danger d-block"> {{ $message }} </small>
            @enderror
            @error('modelable_id')
                <small class="text-danger d-block"> {{ $message }} </small>
            @enderror
        </div>
    </template>
    <script src="{{ asset('vendor/tinymce/tinymce.min.js') }}"></script>
    <script>
        const MAX_ITEMS = 16;
        let INDEX = 0;

        const listBorrowableId = (e) => {
            let c = '';
            if (e.currentTarget.querySelector('option:checked').dataset.borrowable) {
                let borrowable = JSON.parse(e.currentTarget.querySelector('option:checked').dataset.borrowable);
                for (i in borrowable) {
                    c += '<option value="' + i + '" ' + (('{{ old('modelable_id', -1) }}' == i) ? ' selected' : '') + '>' + borrowable[i] + '</option>';
                }
            }
            e.currentTarget.parentNode.nextElementSibling.querySelector('[data-name="inv.modelable_id"]').innerHTML = c.length ? '<option value>-- Pilih --</option>' + c : '<option value>-- Pilih --</option>'
        }

        const addRow = (e) => {
            let tbody = document.getElementById('asset-item');
            if (document.getElementById('asset-item').querySelectorAll('.items-row').length < MAX_ITEMS) {
                let template = document.getElementById('template');
                Array.from(template.content.querySelectorAll('.form-index')).forEach((el) => {
                    let name = el.dataset.name.split('.');
                    el.name = `${name[0]}[${INDEX}][${name[1]}]`
                });
                tbody.insertAdjacentHTML('beforeend', template.innerHTML);
                if (e) e.currentTarget.classList.toggle('disabled', document.getElementById('asset-item').querySelectorAll('.items-row').length == MAX_ITEMS);
                INDEX++;
            }
        }

        const removeRow = (el) => {
            el.currentTarget.parentNode.parentNode.remove();
            document.querySelector('.btn-add').classList.toggle('disabled', document.getElementById('asset-item').querySelectorAll('.items-row').length > MAX_ITEMS);
        }

        window.addEventListener('DOMContentLoaded', () => {
            addRow();
            document.querySelector('.btn-add').addEventListener('click', addRow);
        });
    </script>
    <script>
        tinymce.init({
            selector: 'textarea#clause',
            height: "320",
            paste_data_images: false,
            relative_urls: false,
            plugins: 'autosave autoresize print preview paste searchreplace code fullscreen image link media table charmap hr pagebreak advlist lists wordcount imagetools noneditable charmap',
            menubar: false,
            toolbar: 'undo redo | bold italic underline | formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap | fullscreen  preview save print | insertfile image media template link'
            // readonly: 1
        });
    </script>
@endpush
