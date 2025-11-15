<div class="modal-body">
      

    <div class="row">
        <div class="col-md-6">
            
            <div class="col-md-12 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                    Tags Registered</div>
                                <div class="mb-0 font-weight-bold text-gray-800">
                                    <?php if(count($tags) > 0){ 
                                            foreach($tags as $k => $v){
                                        ?>
                                        <a href="javascript:void(0)" class="btn btn-info btn-sm">
                                            <span class="text"><?=$v->title?></span>    
                                        </a>
                                    <?php } 
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-comments fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                    Free Tags on Post</div>
                                <div class="mb-0 font-weight-bold text-gray-800">
                                    <?php if(count($free_tags) > 0){ 
                                        foreach($free_tags as $k => $v){
                                    ?>
                                    <a href="javascript:void(0)" class="btn btn-danger btn-sm">
                                        <span class="text"><?=$v?></span>
                                    </a>
                                    <?php } 
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-comments fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-12 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                    Used Tags on Post</div>
                                <div class="mb-0 font-weight-bold text-gray-800">
                                    <?php if(count($used_tags) > 0){ 
                                        foreach($used_tags as $k => $v){
                                    ?>
                                    <a href="javascript:void(0)" class="btn btn-success btn-sm">
                                        <span class="text"><?=$v?></span>
                                    </a>
                                    <?php } 
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-comments fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-12 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                    Category Used by post</div>
                                <div class="mb-0 font-weight-bold text-gray-800">
                                     <?php if(count($category_used) > 0){ 
                                        foreach($category_used as $k => $v){
                                    ?>
                                    <a href="javascript:void(0)" class="btn btn-warning btn-sm">
                                        <span class="text"><?=$v->title?></span>
                                    </a>
                                    <?php } 
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-comments fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Post Title:</label>
                <input disabled type="text" class="form-control no-borders" value="<?=$first_post->title?>" />
            </div>

            <div class="form-group">
                <label>Post Slug:</label>
                <input disabled type="text" class="form-control no-borders" value="<?=$first_post->slug?>" />
            </div>

            <div class="form-group">
                <label>Count Comments on this post:</label>
                <p class="ml-2"><a href="javascript:void(0)">0</a></p>
            </div>

            <div class="form-group">
                <label>Created on:</label>
                <input disabled type="text" class="form-control no-borders" value="<?=date('Y-m-d H:i:s', strtotime($first_post->created_at))?>" />
            </div>


            <div class="form-group">
                <label>Scheduled Post on:</label>
                <input disabled type="text" class="form-control no-borders" />
                <p>Note: <i>Scheduled post is the last time the post was schedule</i></p> 
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>