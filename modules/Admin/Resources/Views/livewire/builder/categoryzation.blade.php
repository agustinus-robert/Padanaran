<div>       
    <div class="toolbar mb-5 mb-lg-7" id="kt_toolbar">
        <!--begin::Page title-->
        <div class="page-title d-flex flex-column me-3">
            <!--begin::Title-->
            <h1 class="d-flex text-gray-900 fw-bold my-1 fs-3">Category</h1>
            <!--end::Title-->
            <!--begin::Breadcrumb-->
            <ul class="breadcrumb breadcrumb-dot fw-semibold text-gray-600 fs-7 my-1">
                <!--begin::Item-->
                <li class="breadcrumb-item text-gray-600">
                    <a href="index.html" class="text-gray-600 text-hover-primary">Dashboard</a>
                </li>

                <li class="breadcrumb-item text-gray-600">Category</li>
            </ul>
            <!--end::Breadcrumb-->
        </div>
        <!--end::Page title-->
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="row mb-5">
                        <div class="col-md-2">
                            <label class="form-label"><b>Title</b></label>
                        </div>

                        <div class="col-md-10">
                            <input wire:model.lazy="title" type="text" class="form-control">
                        </div>
                    </div>

                   {{--  <div class="row mb-5">
                        <div class="col-md-2">
                           <label class="form-label"><b>Parent</b></label> 
                        </div>

                        <div class="col-md-10">
                            <input wire:model="is_parent" class="form-check-input" type="checkbox">
                        </div>
                    </div>
 --}}
                    <div class="row mb-5">
                        <div class="col-md-2">
                            <label class="form-label"><b>Description</b></label>
                        </div>

                        <div class="col-md-10">
                            <textarea class="form-control" wire:model.lazy="description"></textarea>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="card-footer">        
            <input class="btn btn-primary" type="button" wire:click="submitForm" value="save" />
        </div>
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
        
        @this.upload('photo', event.target.files[0])
    })
</script>
</div>