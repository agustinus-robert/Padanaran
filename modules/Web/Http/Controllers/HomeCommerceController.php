<?php

namespace Modules\Web\Http\Controllers;

use App\Http\Controllers\FrontEndController;

use Modules\Pos\Models\Product;
use Modules\Pos\Models\Category;
use Modules\Pos\Models\Outlet;
use Modules\Editor\Models\EditorStory;
use Modules\Reference\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Redirect;
use DB;

class HomeCommerceController extends FrontEndController
{
    public function index($bahasa = 'id')
    {
        // if (env('SALES') == 1 && !empty(session()->get('selected_outlet_on'))) {
        //     $category = Category::where('outlet_id', session()->get('selected_outlet_on'))->get();
        //     $product = Product::whereNull('deleted_at')->where('outlet_id', session()->get('selected_outlet_on'))->limit(4)->get();
        // } else {
        //     if (empty(session()->get('selected_outlet_on'))) {
        //         session()->put('selected_outlet_on', 1);
        //     }
        //     $category = Category::get();
        //     $product = Product::whereNull('deleted_at')->limit(4)->get();
        // }

        // $banner = get_data_by_menu_global2('1824105627881812');
        // $promo = get_data_by_menu_global2('1824107391721111');

        // $bannerDown = $this->get_data_by_id(get_data_by_menu_global2('1824107703067463')->first()->id);
        // $information = get_data_by_menu_global2('1824108073909826');

        // $partner = get_data_by_menu_global2('1824107783192578');
        // $outlet = Outlet::get();
        // compact('product', 'category', 'banner', 'bahasa', 'promo', 'bannerDown', 'information', 'partner', 'outlet')
        // $editor = EditorStory::whereNull('deleted_at')->get();
        //, compact('editor')
        return view('web::home.index');
    }
}
