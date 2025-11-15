<?php
namespace Modules\Account\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Admin\Models\Category;
use DataTables;
use Session;
use Redirect;
use DB;

class RegistrationAppController extends Controller
{

   public function index(Request $request)
    {
        return view('account::member.index');
    }
}