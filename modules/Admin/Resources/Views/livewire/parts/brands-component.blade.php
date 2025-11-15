<div>
    <label for="brand">Brand</label>
    <select id="brand" wire:model="selectedBrand">
        <option value="">Select Brand</option>
        @foreach ($brands as $brand)
            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
        @endforeach
    </select>
</div>
