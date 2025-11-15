<?php

namespace Modules\Web\Http\Controllers;

use App\Http\Controllers\FrontEndController;
use Illuminate\Http\Request;
use Session;
use Redirect;
use DB;

class PBuilderController extends FrontEndController
{
    public function index()
    {
        return view('web::pbuilder.index');
    }

    public function detail_blog() {}
}
