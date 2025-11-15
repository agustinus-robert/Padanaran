<?php

namespace Modules\Web\Http\Controllers;

use Modules\Reference\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Redirect;
use DB;

class DeliverCommerceController extends Controller
{
    public function index()
    {
        return view('web::deliver.index');
    }
}
