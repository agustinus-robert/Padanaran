<?php
namespace Modules\Admin\Http\Controllers\CustomFeature;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Admin\Models\Contact;
use DataTables;
use Session;
use Redirect;
use DB;

class ContactController extends Controller
{

   public function index(Request $request)
    {
        $this->authorize('access', Contact::class);
        $data['contact'] = Contact::count();

        return view('admin::custom_feature.contact.index', $data);
    }
}

?>