<div>
    <div class="toolbar mb-5 mb-lg-7" id="kt_toolbar">
        <!--begin::Page title-->
        <div class="page-title d-flex flex-column me-3">
            <!--begin::Title-->
            <h1 class="d-flex text-gray-900 fw-bold my-1 fs-3">Site Configuration</h1>
            <!--end::Title-->
            <!--begin::Breadcrumb-->
            <ul class="breadcrumb breadcrumb-dot fw-semibold text-gray-600 fs-7 my-1">
                <!--begin::Item-->
                <li class="breadcrumb-item text-gray-600">
                    <a href="index.html" class="text-gray-600 text-hover-primary">Dashboard</a>
                </li>

                <li class="breadcrumb-item text-gray-600">Site Configuration</li>
            </ul>
            <!--end::Breadcrumb-->
        </div>
        <!--end::Page title-->
    </div>

    <div class="content flex-column-fluid" id="kt_content">     
                @if (Session::has('msg'))
                    <div x-data="{show: true}" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                        <div class="alert alert-success">
                            {{ Session::get('msg') }}
                        </div>
                    </div>
                @endif

                @if (Session::has('msg-server'))
                    <div x-data="{show: true}" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                        <div class="alert alert-danger">
                            {{ Session::get('msg') }}
                        </div>
                    </div>
                @endif

                <div class="card shadow border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-5">
                                <div class="form-group">
                                    <label>Site Name</label>
                                    <input class="form-control" type="text" wire:model.lazy="sites_name" />
                                </div>
                            </div>

                            <div class="col-md-12 mb-5">
                                <div class="form-group">
                                    <label>Location</label>
                                    <textarea class="form-control" type="text" wire:model.lazy="location"></textarea>
                                </div>
                            </div>

                            <div class="col-md-12 mb-5">
                                <div class="form-group">
                                    <label>Coordinate</label>
                                    <textarea class="form-control" type="text" wire:model.lazy="coordinate"></textarea>
                                </div>
                            </div>

                            <div class="col-md-12 mb-5">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input class="form-control" type="text" wire:model.lazy="email" />
                                </div>
                            </div>


                            <div class="col-md-12 mb-5">
                                <div class="form-group">
                                    <label>Call</label>
                                    <input class="form-control" type="text" wire:model.lazy="calle" />
                                </div>
                            </div>

                            <div class="col-md-12 mb-5">
                                <div class="form-group">
                                    <label>Twitter</label>
                                    <input class="form-control" type="text" wire:model.lazy="twitter" />
                                </div>
                            </div>

                            <div class="col-md-12 mb-5">
                                <div class="form-group">
                                    <label>Facebook</label>
                                    <input class="form-control" type="text" wire:model.lazy="facebook" />
                                </div>
                            </div>

                            <div class="col-md-12 mb-5">
                                <div class="form-group">
                                    <label>Instagram</label>
                                    <input class="form-control" type="text" wire:model.lazy="instagram" />
                                </div>
                            </div>

                            <div class="col-md-12 mb-5">
                                <div class="form-group">
                                    <label>Skype</label>
                                    <input class="form-control" type="text" wire:model.lazy="skype" />
                                </div>
                            </div>

                            <div class="col-md-12 mb-5">
                                <div class="form-group">
                                    <label>Linkedin</label>
                                    <input class="form-control" type="text" wire:model.lazy="linkedin" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="form-group">
                            <button class="btn btn-primary" type="text" wire:click="submitForm">Simpan</button>
                        </div>
                    </div>
                </div>
        
    </div>
</div>
