<?php

namespace Modules\Admin\Http\Controllers\API;

use Illuminate\Http\Request;
use Modules\Admin\Models\Post;
use Modules\Reference\Http\Controllers\Controller;

class PostingBuildController extends Controller{

    public function index(){
        $arr = [
            'oke'
        ];
        return response()->json($arr);
    }

    public function create(){

    }

    public function show($id){

    }

    public function edit($id){

    }

    public function destroy($id){

    }
}
