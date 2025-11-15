<?php
namespace Modules\Web\Http\Controllers\Builder;

use Modules\Reference\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Redirect;
use DB;

class PageBuilderController extends Controller
{
	public function index(){
		return view('web::builder.index');
	}
}
