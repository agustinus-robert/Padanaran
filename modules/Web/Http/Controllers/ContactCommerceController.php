<?php

namespace Modules\Web\Http\Controllers;

use App\Http\Controllers\FrontEndController;
use Illuminate\Http\Request;
use Session;
use Redirect;
use DB;

class ContactCommerceController extends FrontEndController
{
    public function index($bahasa = 'id')
    {
        $banner = get_data_by_menu_global2('1824111764482882')->first();
        $informasi = get_data_by_menu_global2('1824111824283606')->first();

        return view('web::contact.index', compact('banner', 'informasi', 'bahasa'));
    }

    public function detail_blog() {}
}
