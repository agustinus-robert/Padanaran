<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="font-size-18 mb-0">Kategori</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Kategori</a></li>
                        <li class="breadcrumb-item active">{{ $action }} Kategori</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="card-primary card-outline card mb-4"> <!--begin::Header-->
        <div class="card-header">
            <div class="card-title">{{ $action }} Kategori</div>
        </div> <!--end::Header--> <!--begin::Form-->
        <form wire:submit="save" enctype="multipart/form-data"> <!--begin::Body-->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Code</label>
                            <input disabled wire:model="form.code" type="text" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" wire:change="categoryChanged($event.target.value)" wire:model="form.name">
                            @error('form.name')
                                <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Induk Kategori</label>

                            <select wire:model="form.parent_id" class="form-select mb-2">
                                <option value="">Pilih Induk Kategori</option>
                                @foreach ($category as $key => $value)
                                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                                @endforeach
                            </select>

                            <b>(*)</b> Silahkan kosongan jika tidak memilih parent category
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Upload Gambar Kategori</label>
                            <input type="file" class="form-control" wire:model="form.image" accept="image/*" onchange="previewImage(event)" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" wire:model="form.description"></textarea>
                            @error('form.description')
                                <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4" wire:ignore>
                        <div class="col-md-12 d-flex justify-content-center align-items-center position-relative mt-2 rounded border p-2" style="height: 200px; background: rgba(0, 0, 255, 0.1); border: 2px solid rgba(0, 0, 255, 0.5);">
                            <i id="cover-icon" class="mdi mdi-image text-primary" style="font-size: 48px;"></i>
                            <img id="cover-preview" src="#" alt="Preview" class="img-thumbnail d-none position-absolute" style="max-width: 100%; max-height: 100%;">
                        </div>
                    </div>
                </div>
            </div> <!--end::Body--> <!--begin::Footer-->
            <div class="card-footer"> <button type="submit" class="btn btn-primary">Submit</button> </div> <!--end::Footer-->
        </form> <!--end::Form-->
    </div>

    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('cover-preview');
                var icon = document.getElementById('cover-icon');
                output.src = reader.result;
                output.classList.remove('d-none');
                icon.classList.add('d-none');
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        function previewVideo(event) {
            var file = event.target.files[0];
            var video = document.getElementById('video-preview');
            var icon = document.getElementById('video-icon');
            if (file) {
                var objectURL = URL.createObjectURL(file);
                video.src = objectURL;
                video.classList.remove('d-none');
                icon.classList.add('d-none');
            }
        }
    </script>
</div>
