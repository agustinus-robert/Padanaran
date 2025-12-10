<?php

namespace Modules\Web\Http\Controllers;

use Modules\Reference\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Pos\Models\Category;
use App\Models\Setting;
use Session;
use Redirect;
use DB;

class RequestorController extends Controller
{
    public function index()
    {
        return Setting::first();
    }

    public function show($id) {}
}
