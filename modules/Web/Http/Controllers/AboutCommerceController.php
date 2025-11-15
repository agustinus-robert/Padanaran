<?php

namespace Modules\Web\Http\Controllers;

use App\Http\Controllers\FrontEndController;

use Modules\Reference\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Redirect;
use DB;

class AboutCommerceController extends FrontEndController
{
    public function index($bahasa = 'id')
    {
        $bannerDown = $this->get_data_by_id(get_data_by_menu_global2('1824108594994616')->first()->id);
        foreach (get_data_by_menu_global2('1824108672409803') as $key => $value) {
            if ($key == 0) {
                $vision = $value;
            } else {
                $mission = $value;
            }
        }
        $ourTeam = get_data_by_menu_global2('1824108834048012');

        return view('web::about.index', compact('bannerDown', 'bahasa', 'vision', 'mission', 'ourTeam'));
    }
}
