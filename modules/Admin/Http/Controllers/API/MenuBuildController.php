<?php

namespace Modules\Admin\Http\Controllers\API;

use Illuminate\Http\Request;
use Modules\Admin\Models\Menu;
use Modules\Reference\Http\Controllers\Controller;

class MenuBuildController extends Controller{

    public function index(){
        $menu = Menu::where('id', '1811605897030811')->get();
        return response()->json($menu);
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
