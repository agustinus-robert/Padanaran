<div>
    <div class="toolbar mb-5 mb-lg-7" id="kt_toolbar">
        <!--begin::Page title-->
        <div class="page-title d-flex flex-column me-3">
            <!--begin::Title-->
            <h1 class="d-flex text-gray-900 fw-bold my-1 fs-3">Category Name</h1>
            <!--end::Title-->
            <!--begin::Breadcrumb-->
            <ul class="breadcrumb breadcrumb-dot fw-semibold text-gray-600 fs-7 my-1">
                <!--begin::Item-->
                <li class="breadcrumb-item text-gray-600">
                    <a href="index.html" class="text-gray-600 text-hover-primary">Dashboard</a>
                </li>

                <li class="breadcrumb-item text-gray-600">Category Name</li>
            </ul>
            <!--end::Breadcrumb-->
        </div>
        <!--end::Page title-->
    </div>

    <div class="content flex-column-fluid" id="kt_content">
        <div class="row">
                
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <label>Icon</label>
                            <div class="input-group col-md-12" wire:ignore>    
                                <div class="input-group-prepend">
                                    <span class="input-group-text h-100 selected-icon"></span>
                                </div>
                                
                                <input type="text" class="form-control iconpicker">

                                {{-- <input type="hidden" id="lz" type="text" wire:model.lazy="icon" />   --}}
                            </div>
                        </div>

                        <div class="col-md-10">
                            <div class="form-group">
                                <label>Title</label>
                                <input class="form-control" type="text" wire:model.lazy="title" />
                            </div>
                        </div>

                        
                    </div>
                  

                    
                </div>
                <div class="card-footer text-center">
                    <input type="button" class="btn btn-primary" wire:click="submitForm" value="save">
                </div>
            </div>

            <hr>

            @if (Session::has('msg'))
                <div x-data="{show: true}" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                    <div class="alert alert-success">
                        {{ Session::get('msg') }}
                    </div>
                </div>
            @endif
        </div>
    </div>

@push('scripts')
    <script>
        
         (async () => {
                const response = await fetch('https://unpkg.com/codethereal-iconpicker@1.2.1/dist/iconsets/bootstrap5.json')
                const result = await response.json()

                 var get_icon = "{{$icon}}"

                const iconpicker = new Iconpicker(document.querySelector(".iconpicker"), {
                    icons: result,
                    searchable: true,
                    showSelectedIn: document.querySelector(".selected-icon"),
                    selectedClass: "selected",
                    containerClass: "my-picker",
                    hideOnSelect: true,
                    fade: true,
                    defaultValue: (get_icon ? get_icon : 'bi bi-alarm'),
                    valueFormat: val => `bi ${val}`
                });

                 // Set as empty

                if(get_icon){
                    iconpicker.set('')  
                    iconpicker.set(get_icon)
                } else {
                    iconpicker.set('')    
                }
            })()

             // $('.iconpicker-dropdown ul li').click(function(e) {
             //    alert('ok')
             // })

            // $(document).on('change', '.iconpicker', function(e){
            //     @this.set('icon', e.icon)
            // })

        document.addEventListener('DOMContentLoaded', () => {
            $(document).on('click', '.my-picker li', function(e){  
                @this.set('icon', $(e.target).attr('value'))
            })
        })
    </script>
    @endpush
</div>

