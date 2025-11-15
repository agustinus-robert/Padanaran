<div>
    <div class="toolbar mb-5 mb-lg-7" id="kt_toolbar">
        <!--begin::Page title-->
        <div class="page-title d-flex flex-column me-3">
            <!--begin::Title-->
            <h1 class="d-flex text-gray-900 fw-bold my-1 fs-3">Role Configuration</h1>
            <!--end::Title-->
            <!--begin::Breadcrumb-->
            <ul class="breadcrumb breadcrumb-dot fw-semibold text-gray-600 fs-7 my-1">
                <!--begin::Item-->
                <li class="breadcrumb-item text-gray-600">
                    <a href="index.html" class="text-gray-600 text-hover-primary">Dashboard</a>
                </li>

                <li class="breadcrumb-item text-gray-600">Role Configuration</li>
            </ul>
            <!--end::Breadcrumb-->
        </div>
        <!--end::Page title-->
    </div>

    <div class="content flex-column-fluid" id="kt_content">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row">    
                    <div class="col-md-12">
                        <label class="form-label">Title</label>
                        <input wire:model.lazy="title" type="text" class="form-control">
                    </div>

                    <div class="col-md-12 mt-4">
                        <div class="card">

                            <div class="card-body">
                                <table class="table">
                                    <tr>
                                        <th>Menu Name</th>
                                        <th>Create</th>
                                        <th>Read</th>
                                        <th>Update</th>
                                        <th>Delete</th>
                                    </tr>
                                <?php 
                                    foreach(get_menu() as $index => $value){ 
                                        if($value->type == 2){
                                ?>
                                    <tr>
                                        <td><?=$value->title?></td>
                                        <td><input type="checkbox" wire:model="role.create.<?=$value->id?>"></td>
                                        <td><input type="checkbox" wire:model="role.read.<?=$value->id?>"></td>
                                        <td><input type="checkbox" wire:model="role.update.<?=$value->id?>"></td>
                                        <td><input type="checkbox" wire:model="role.delete.<?=$value->id?>"></td>
                                    </tr>
                                <?php 
                                        } else { ?>
                                    <tr>
                                        <td><?=$value->title?> <i>(Just Read as Menu)</i></td>
                                        <td></td>
                                        <td><input type="checkbox" wire:model="role.read.<?=$value->id?>"></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                 <?php    } 
                                    }
                                ?>
                                </table>
                            </div>

                            <div class="card-footer text-center">
                                <input type="submit" class="btn btn-primary" wire:click="submit" value="save">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  
</div>