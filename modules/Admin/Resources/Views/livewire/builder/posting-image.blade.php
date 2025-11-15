<div>
    <main>
        <div class="container-fluid px-4">
            
            <h1 class="mt-4">Post Photo of <?=$data->title?></h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                <li class="breadcrumb-item active">Photo <?=$data->title?></li>
            </ol>

            
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="font-weight-bold text-primary"><i class="fas fa-camera" aria-hidden="true"></i> Your Post Image</h6>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                        
                        <div class="form-group">
                            <label class="form-label">Photo Title</label>
                            <input class="form-control" type="text" wire:model.lazy="title" />
                        </div>

                        <div class="form-group">
                            <label class="form-label">Photo Description</label>
                            <textarea class="form-control" type="text" wire:model.lazy="content"></textarea>
                        </div>
                        
                        <div class="mt-3">
                            <label class="form-label">Category Photo</label>
                            <select class="form-control">
                                <option value="">Choose Category Photo</option>
                            </select>
                        </div>
                            
                        </div>

                        <div class="col-md-4">
                            <div class="form-group text-center" wire:ignore>
                                    <input type="file"  data-allowed-file-extensions="jpg jpeg png" class="dropify" name="dropify_data" data-default-file="{{!empty($data_id) ? Storage::url($sh_photo) : ''}}" />
                                    
                                <div class="row">
                                    @error('photo') <span class="error">{{ $message }}</span> @enderror
                                </div>
                            </div>

                         
                        </div>
                    </div>
                   
                </div>

                <div class="card-footer">
                    <input type="button" class="btn btn-primary" wire:click="submitForm" value="save">
                </div>
            </div>
        </div>
    </main>
</div>

<script>

    $(document).ready(function(){
        $('.dropify').dropify({
                messages: {
                    default: 'Upload Image',
                    replace: 'Replace Image',
                    remove: 'Remove Image',
                    error: 'Error Image'
                }
            }
        );

    })


    $(document).on('change', '.dropify', function(event) {
        //console.log(event.target.files[0])
        
        @this.upload('dropify', event.target.files[0])
    })
</script>
</div>
