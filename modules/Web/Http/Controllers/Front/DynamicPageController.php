<?php
namespace Modules\Web\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  Modules\Editor\Models\EditorWebPages;
use Session;
use Redirect;
use DB;

class AboutController extends Controller
{
    public function index(Request $request){

        return view('web::dynamic-page');
    }
}
