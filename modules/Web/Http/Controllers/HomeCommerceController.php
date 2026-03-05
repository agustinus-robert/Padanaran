<?php

namespace Modules\Web\Http\Controllers;

use App\Http\Controllers\FrontEndController;
use Modules\Pos\Models\Product;
use Modules\Pos\Models\Category;
use Modules\Pos\Models\Outlet;
use Modules\Editor\Models\EditorStory;
use Modules\Reference\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Redirect;
use DB;

class HomeCommerceController extends Controller
{
    public function index(Request $request)
    {
        return view('web::home.index');
    }
}
