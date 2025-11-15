<?php

namespace Modules\Web\Http\Controllers;

use Modules\Reference\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Pos\Models\Category;
use Session;
use Redirect;
use DB;

class ProductCommerceController extends Controller
{
    public function index()
    {
        return view('web::products.index');
    }

    public function show($id) {}
}
