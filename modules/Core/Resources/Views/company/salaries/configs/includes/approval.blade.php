<div class="mb-3">
    <table class="table">
        <thead>
            <tr>
                <th>Index urutan</th>
                <th>Karyawan</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr class="form-index has-add-button">
                <td>
                    <div class="input-group">
                        <input type="number" class="form-control @error('az') is-invalid @enderror" data-name="az" value="{{ old('az', 1) }}" required>
                        <div class="input-group-text">#</div>
                    </div>
                    @error('az')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </td>
                <td>
                    <select class="form-select @error('user_id') is-invalid @enderror" data-name="user_id" value="{{ old('user_id') }}" required>
                        <option value="">Pilih</option>
                        @foreach ($employees as $key => $employee)
                            <option value="{{ $employee->user->id }}">{{ $employee->user->name }} ({{ $employee->position->position->name }})</option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </td>
                <td style="width: 4%">
                    <button type="button" class="btn btn-light text-danger rounded-circle btn-add px-2 py-1" onclick="addRow(event)"><i class="mdi mdi-plus"></i></button>
                    <button type="button" class="btn btn-secondary rounded-circle btn-remove d-none px-2 py-1" onclick="removeRow(event)"><i class="mdi mdi-minus"></i></button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

@push('scripts')
    <script>
        const addRow = (e) => {
            let tr = e.currentTarget.parentNode.parentNode;
            let el = tr.cloneNode(true)
            el.classList.remove('has-add-button');
            tr.parentNode.insertAdjacentHTML('beforeend', `<tr class="form-index">${el.innerHTML}</tr>`);
            Array.from(tr.parentNode.children).forEach((el, i) => {
                if (i > 0 && !el.classList.contains('has-add-button')) {
                    el.querySelector('.btn-remove').classList.remove('d-none');
                    el.querySelector('.btn-add').classList.add('d-none');
                }
                if (i == tr.parentNode.children.length - 1) {
                    el.querySelector('[data-name="az"]').value = i + 1;
                    el.querySelector('[data-name="user_id"]').selectedIndex = 0;
                }
            });
            renderNameAttribute();
        }

        const removeRow = (e) => {
            e.target.parentNode.closest('tr').remove();
            renderNameAttribute();
        }

        const renderNameAttribute = () => {
            Array.from(document.querySelectorAll('.form-index')).map((tr, index) => {
                Array.from(tr.querySelectorAll('select,input')).map(input => {
                    if (input.dataset.name) {
                        input.name = `approvable_step[${index}][${input.dataset.name}]`;
                    }
                })
            })
        }

        document.addEventListener('DOMContentLoaded', () => {
            Array.from(document.querySelectorAll('[data-name="component_id"]')).forEach(el => {
                if (el.value) {
                    renderUnitComponent(el);
                }
            });
            renderNameAttribute();
        })
    </script>
@endpush
