<div class="col-5">
    <select class="form-control" name="classroom" type="text" required>
        <option value="">Pilih rombel</option>
        @foreach ($acsem->classrooms as $classroom)
            <option value="{{ $classroom->id }}" @if (request('classroom') == $classroom->id) selected @endif>{{ $classroom->full_name }}</option>
        @endforeach
    </select>
</div>
