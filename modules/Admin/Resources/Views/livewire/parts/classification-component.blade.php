<div>
    <label for="classification">Classification</label>
    <select id="classification" wire:model="selectedClassification">
        <option value="">Select Classification</option>
        @foreach ($classifications as $classification)
            <option value="{{ $classification->id }}">{{ $classification->name }}</option>
        @endforeach
    </select>
</div>
