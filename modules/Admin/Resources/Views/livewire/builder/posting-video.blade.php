<div>
    <main>
        <div class="container-fluid px-4">

            <h1 class="mt-4">Post Video of <?=$data->title?></h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                <li class="breadcrumb-item active">Video Embed</li>
            </ol>
            
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="font-weight-bold text-primary"><i class="fas fa-camera" aria-hidden="true"></i> Your Post Image</h6>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            
                            <div class="form-group">
                                <label class="form-label">Video Title</label>
                                <input class="form-control" type="text" wire:model.lazy="title" />
                            </div>

                            <div class="form-group">
                                <label class="form-label">Video Description</label>
                                <textarea class="form-control" type="text" wire:model.lazy="content"></textarea>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Embed video</label>
                                <textarea class="form-control" wire:model.lazy="embedvid"></textarea>
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
{{-- 
<script>
    $('#tagBox').tagging({
        'no-spacebar': true, // default - false
        'forbidden-chars': ["," , ".", "_", "?", "  "] // double space added
    });
</script>
 --}}