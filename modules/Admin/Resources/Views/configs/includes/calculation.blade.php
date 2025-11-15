<div class="mb-3">
    <label for="default_component" class="form-label">Komponen default</label>
    <input type="text" class="form-control" name="default_component" id="default_component" aria-describedby="def" data-id="{{ $default->id }}" value="{{ $default->name }}" readonly />
</div>
<div class="mb-3">
    <label for="default_component" class="form-label required">Komponen</label>
    <select name="component" id="component" class="form-select" required>
        @foreach ($components->groupBy('slip.name') as $key => $_components)
            <optgroup label="{{ $key ?: 'Lainnya' }}">
                @forelse($_components as $key => $component)
                    <option value="{{ $component->id }}">{{ $component->name }}</option>
                @empty
                @endforelse
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label for="calculation" class="form-label required">Rumus hitung</label>
    <input type="text" class="form-control" name="calculation" aria-describedby="calc" required />
    <small class="form-text text-muted">Gaji pokok dilambangkan dengan huruf <strong>"p"</strong></small><br>
    <small class="form-text text-muted">Contoh penulisan: <strong>((p/20)/8) * (135/100)</strong></small>
</div>
