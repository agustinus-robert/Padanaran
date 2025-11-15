<div>
    <div class="toolbar mb-5 mb-lg-7" id="kt_toolbar">
        <!--begin::Page title-->
        <div class="page-title d-flex flex-column me-3">
            <!--begin::Title-->
            <h1 class="d-flex text-gray-900 fw-bold my-1 fs-3">Menu Order</h1>
            <!--end::Title-->
            <!--begin::Breadcrumb-->
            <ul class="breadcrumb breadcrumb-dot fw-semibold text-gray-600 fs-7 my-1">
                <!--begin::Item-->
                <li class="breadcrumb-item text-gray-600">
                    <a href="index.html" class="text-gray-600 text-hover-primary">Dashboard</a>
                </li>

                <li class="breadcrumb-item text-gray-600">Menu Order</li>
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

            @if (Session::has('msg-gagal'))
                <div x-data="{show: true}" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                    <div class="alert alert-danger">
                        {{ Session::get('msg-gagal') }}
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


            <div class="card card-flush">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="dd" id="nestable2" wire:ignore>
                                <ol class="dd-list">
                                    <?php 
                                    //dd($data['order_menu']);

                                    if(gettype($data['order_menu']) == 'array' && count($data['order_menu']) > 0){ 
                                        foreach($data['order_menu'] as $index => $value){
                                            if(isset($value['id'])){
                                        ?>
                                   
                                    <li class="dd-item" data-id="<?=$value['id']?>">
                                        <div class="dd-handle">
                                            <div class="row">
                                                    <div class="col-md-10">
                                                        <?=get_needed($value['id'])[0]->title?></div>
                                                    <div class="col-md-2 text-right">
                                                        <?php 
                                                            if(get_needed($value['id'])[0]->type == '1'){
                                                                echo '<span class="badge badge-primary">'.'general'.'</span>';
                                                            } else if(get_needed($value['id'])[0]->type == '2'){
                                                                 echo '<span class="badge badge-success">'.'master menu'.'</span>';
                                                            } else if(get_needed($value['id'])[0]->type == '3'){
                                                                echo '<span class="badge badge-warning">'.'category'.'</span>';
                                                            } else if(get_needed($value['id'])[0]->type == '5'){
                                                                echo '<span class="badge badge-secondary">'.'Featured'.'</span>';
                                                            }
                                                        ?>
                                                    </div>
                                            </div></div>
                                        <?php if(isset($value['children'])){ ?>
                                        <ol class="dd-list">
                                            <?php 
                                            foreach($value['children'] as $index2 => $value2){  ?>
                                            <li class="dd-item" data-id="{{$index2}}">

                                                    
                                                    @if(is_array($value2))
                                                        <div class="dd-handle">
                                                            <div class="row">
                                                                <div class="col-md-10">
                                                                    {{isset(get_needed($index2)[0]) ? get_needed($index2)[0]->title : $index2}}
                                                                </div>

                                                                <div class="col-md-2">
                                                                    <?php 
                                                                        if(get_needed($index2)[0]->type == '1'){
                                                                            echo '<span class="badge badge-primary">'.'general'.'</span>';
                                                                        } else if(get_needed($index2)[0]->type == '2'){
                                                                            echo '<span class="badge badge-success">'.'master menu'.'</span>';
                                                                        } else if(get_needed($index2)[0]->type == '3'){
                                                                            echo '<span class="badge badge-warning">'.'category'.'</span>';
                                                                        }  else if(get_needed($index2)[0]->type == '5'){
                                                                            echo '<span class="badge badge-secondary">'.'Featured'.'</span>';
                                                                        }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @foreach($value2 as $index3 => $value3)
                                                            <ol class="dd-list">
                                                                <li class="dd-item" data-id="{{$value3['id']}}"><div class="dd-handle">
                                                                        <div class="row">
                                                                            <div class="col-md-10">
                                                                                {{get_needed($value3['id'])[0]->title}}
                                                                            </div>

                                                                            <div class="col-md-2">
                                                                                <?php 
                                                                                    if(get_needed($value3['id'])[0]->type == '1'){
                                                                                        echo '<span class="badge badge-primary">'.'general'.'</span>';
                                                                                    } else if(get_needed($value3['id'])[0]->type == '2'){
                                                                                        echo '<span class="badge badge-success">'.'master menu'.'</span>';
                                                                                    } else if(get_needed($value3['id'])[0]->type == '3'){
                                                                                        echo '<span class="badge badge-warning">'.'category'.'</span>';
                                                                                    }  else if(get_needed($value3['id'])[0]->type == '5'){
                                                                                        echo '<span class="badge badge-secondary">'.'Featured'.'</span>';
                                                                                    }
                                                                                ?>
                                                                            </div>
                                                                        </div>
                                                                    </div></li>
                                                            </ol>
                                                        @endforeach
                                                    @else
                                                    <div class="dd-handle">
                                                        <div class="row">
                                                            <div class="col-md-10">
                                                            <?php

                                                                echo get_needed($value2)[0]->title;
                                                               
                                                             ?>
                                                            </div>

                                                            <div class="col-md-2 text-right">
                                                                <?php 
                                                                    if(get_needed($value2)[0]->type == '1'){
                                                                        echo '<span class="badge badge-primary">'.'general'.'</span>';
                                                                    } else if(get_needed($value2)[0]->type == '2'){
                                                                        echo '<span class="badge badge-success">'.'master menu'.'</span>';
                                                                    } else if(get_needed($value2)[0]->type == '3'){
                                                                        echo '<span class="badge badge-warning">'.'category'.'</span>';
                                                                    }  else if(get_needed($value2)[0]->type == '5'){
                                                                        echo '<span class="badge badge-secondary">'.'Featured'.'</span>';
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                
                                            </li>
                                            <?php } ?>
                                        </ol>
                                        <?php }
                                        } ?>
                                    </li>
                                    <?php } 
                                        }
                                    ?>
                                </ol>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <textarea class="form-control" id="nestable2-output"></textarea>
                        </div>


                    </div>
                </div>

                <div class="card-footer text-center">
                    <input type="button" class="btn btn-primary" wire:click="submitForm" value="save">
                </div>
            </div>
    </div>
<script>
$(document).ready(function(){
     var updateOutput = function(e) {
        var list = e.length ? e : $(e.target),
            output = list.data('output');
        if(window.JSON) {
            output.val(window.JSON.stringify(list.nestable('serialize')));
            @this.set('order_menu', window.JSON.stringify(list.nestable('serialize')))
        }
        else {
            output.val('JSON browser support required for this demo.');
        }
    };

    $('#nestable2').nestable({
        group: 1
    }).on('change', updateOutput);

    updateOutput($('#nestable2').data('output', $('#nestable2-output')));

})
</script>
</div>
