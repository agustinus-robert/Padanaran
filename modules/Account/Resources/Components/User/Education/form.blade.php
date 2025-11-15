<div class="mb-3">
    <label class="form-label required">Tingkat pendidikan</label>
    <select class="form-select @error('edu_grade') is-invalid @enderror flex-grow-0" name="edu_grade" required></select>
    @error('edu_grade')
        <small class="text-danger d-block"> {{ $message }} </small>
    @enderror
</div>
<div class="mb-3">
    <label class="form-label required">Nama instansi</label>
    <input type="text" class="form-control @error('edu_university') is-invalid @enderror flex-grow-0" name="edu_university" value="{{ old('edu_university', $user->getMeta('edu_university') ?? '') }}" required>
    @error('edu_university')
        <small class="text-danger d-block"> {{ $message }} </small>
    @enderror
</div>
<div class="mb-3">
    <label class="form-label required">Tahun lulus</label>
    <input type="text" class="form-control @error('edu_year') is-invalid @enderror flex-grow-0" name="edu_year" value="{{ old('edu_year', $user->getMeta('edu_year') ?? '') }}" required>
    @error('edu_year')
        <small class="text-danger d-block"> {{ $message }} </small>
    @enderror
</div>
<div>
    <button class="btn btn-soft-danger" type="submit"><i class="mdi mdi-check"></i> Simpan</button>
    @isset($back)
        <a class="btn btn-ghost-light text-dark" href="{{ request('next', route('account::index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
    @endisset
</div>

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", async () => {
            let {
                data
            } = await axios.get('{{ route('api::references.grade.index') }}');
            let opt = data.data;
            opt.forEach(element => {
                let number = element.id;
                let text = element.name;
                let option = document.createElement('option');
                option.value = number;
                option.innerHTML = text;
                document.querySelector('[name="edu_grade"]').appendChild(option);
                if (number == '{{ old('edu_grade', $user->getMeta('edu_grade', '')) }}') {
                    option.selected = 'selected'
                }
            });
        });
    </script>
@endpush
