<div>
    <label for="category">Category</label>
    <select id="category" wire:model="selectedCategory">
        <option value="">Select Category</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
    </select>
</div>
