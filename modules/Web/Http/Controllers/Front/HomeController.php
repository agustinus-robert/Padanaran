<?php

namespace Modules\Web\Http\Controllers\Front;

use App\Http\Controllers\FrontEndController;
use Modules\Pos\Models\Category;
use Illuminate\Http\Request;
use Session;
use Redirect;
use DB;

class HomeController extends FrontEndController
{
    public function index($bahasa)
    {
        return view('web::home.index');
    }

    public function detail_blog() {}
}
