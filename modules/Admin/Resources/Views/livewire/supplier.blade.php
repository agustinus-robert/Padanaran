<div>
    <div class="card border-0">
        <div class="card-body border-bottom">
            <div class="d-flex justify-content-between">
                <div>
                    <i class="mdi mdi-format-list-bulleted"></i> Daftar supplier
                </div>

                <div class="flex-end flex-grow">
                    @if (!$isCreating)
                        @can('store', Modules\Admin\Models\Supplier::class)
                            <button class="btn btn-sm btn-outline-secondary" wire:click="showCreateForm"><i class="mdi mdi-plus-circle-outline"></i> Tambah Supplier</button>
                        @endcan
                    @endif
                </div>
            </div>
        </div>

         @if (Session::has('msg-sukses'))
            <div x-data="{show: true}" x-init="setTimeout(() => show = false, 4000)" x-show="show">
                <div class="alert alert-success">
                    {{ Session::get('msg-sukses') }}
                </div>
            </div>
        @endif

         @if (Session::has('msg-gagal'))
            <div x-data="{show: true}" x-init="setTimeout(() => show = false, 4000)" x-show="show">
                <div class="alert alert-danger">
                    {{ Session::get('msg-gagal') }}
                </div>
            </div>
        @endif

        @if ($isCreating)
            <div class="card-body">
                <form wire:submit.prevent="{{ $isEditing ? 'update' : 'save' }}">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" id="name" class="form-control" wire:model="form.name">
                        @error('form.name')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" class="form-control" wire:model="form.email">
                        @error('form.email')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">+62</span>
                            <input type="text" id="phone" class="form-control" wire:model="form.phone">
                        </div>
                        @error('form.phone')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Address</label>
                        <textarea id="address" class="form-control" wire:model="form.address"></textarea>
                        @error('form.address')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <button class="btn btn-soft-primary" type="submit"><i class="mdi mdi-content-save"></i> {{ $isEditing ? 'Perbarui' : 'Simpan' }}</button>
                    <button class="btn btn-soft-danger" type="button" wire:click="hideCreateForm"><i class="mdi mdi-sync"></i> Batalkan</button>
                </form>
            </div>
        @endif
        @if (!$isCreating)
            @livewire('admin::datatables.supplier-datatable')
        @endif 
    </div>
</div>