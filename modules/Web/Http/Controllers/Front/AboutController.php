<?php
namespace Modules\Web\Http\Controllers\Front;

use App\Http\Controllers\FrontEndController;
use Illuminate\Http\Request;
use Session;
use Redirect;
use DB;

class AboutController extends FrontEndController
{
    public function index($bahasa){
        $data['lang'] = $bahasa;
        $data['about'] = $this->get_data_by_id('33');
        $data['caption_vision_mission'] = $this->get_data_by_id('113');
        $data['vision'] = $this->get_data_by_id('34');
        $data['mission'] = $this->get_data_by_id('35');

        $data['caption_program_and_event'] = $this->get_data_by_id('57');
        $p_category = DB::table('post_has_category')
        ->where('tags_id', '1811731122059639')
        ->orWhere('tags_id', '1811731104397737')->get();
        $data['arr_category'] = [];
        foreach($p_category as $key => $value){
            $data['arr_category'][$value->post_id] = $value->tags_id;
        } 

        $data['program_event'] = $this->get_data_by_menu('1811038194284839');
        $data['count_partnership'] = $this->get_data_by_menu('1811676095202373');
        $data['target_data'] = $this->get_data_by_id('47');
        $data['source_founding_section'] = $this->get_data_by_id('48');
        $data['source_founding_content'] = $this->get_data_by_menu('1811677495554972');

        $data['fund_1'] = $this->get_data_by_id('49');
        $data['fund_2'] = $this->get_data_by_id('50');
        $data['fund_3'] = $this->get_data_by_id('51');
        

        $data['our_team'] = $this->get_data_by_id('22');
        $data['our_team_pembina'] = $this->get_data_by_menu_category('1811605096312946','1812987469574170');
        $data['our_team_pengurus'] = $this->get_data_by_menu_category('1811605096312946','1812987494697150');
        $data['our_team_pengawas'] = $this->get_data_by_menu_category('1811605096312946','1812987521673452');

        $data['section_company'] = $this->get_data_by_id('29');
        $data['content_company'] = $this->get_data_by_menu('1811606636368529');

        $data['section_transformasi'] = $this->get_data_by_id('37');
        $data['content_transformasi'] = $this->get_data_by_menu('1811719663421545');

        $data['section_sejarah'] = $this->get_data_by_id('36');
        $data['content_sejarah'] = $this->get_data_by_id('43');


        $data['source_founding_section'] = $this->get_data_by_id('48');
        $data['source_founding_content'] = $this->get_data_by_menu('1811677495554972');

        $data['fund_1'] = $this->get_data_by_id('49');
        $data['fund_2'] = $this->get_data_by_id('50');
        $data['fund_3'] = $this->get_data_by_id('51');

        $data['section_report'] = $this->get_data_by_id('53');
        $data['content_report'] = $this->get_data_by_menu('1811683412333297');


        return view('web::about.index', $data);
    }

    public function vision_mission($bahasa){
        $data['lang'] = $bahasa;
        $data['caption_vision_mission'] = $this->get_data_by_id('113');
        $data['vision'] = $this->get_data_by_id('34');
        $data['mission'] = $this->get_data_by_id('35');

        return view('web::about.vission-n-mission', $data);
    }

    public function our_team($bahasa){
        $data['lang'] = $bahasa;
        $data['about'] = $this->get_data_by_id('33');
        $data['vision'] = $this->get_data_by_id('34');
        $data['mission'] = $this->get_data_by_id('35');
        $data['our_team'] = $this->get_data_by_id('22');
        $data['our_team_pembina'] = $this->get_data_by_menu_category('1811605096312946','1812987469574170');
        $data['our_team_pengurus'] = $this->get_data_by_menu_category('1811605096312946','1812987494697150');
        $data['our_team_pengawas'] = $this->get_data_by_menu_category('1811605096312946','1812987521673452');

        return view('web::about.our-team', $data);
    }

    public function target_objectives($bahasa){
        $data['lang'] = $bahasa;
        $data['caption_transformasi'] = $this->get_data_by_id('37');
        $data['transforamsi_content'] = $this->get_data_by_menu('1811719663421545');

        return view('web::about.targets-n-objectives', $data);
    }

    public function history($bahasa){
        $data['lang'] = $bahasa;

        $data['section_company'] = $this->get_data_by_id('29');
        $data['content_company'] = $this->get_data_by_menu('1811606636368529');

        $data['section_transformasi'] = $this->get_data_by_id('37');
        $data['content_transformasi'] = $this->get_data_by_menu('1811719663421545');

        $data['section_sejarah'] = $this->get_data_by_id('36');
        $data['content_sejarah'] = $this->get_data_by_id('43');
        // dd($data['section_transformasi']);
       // dd($data['section_company']);

        return view('web::about.history', $data);
    }

    public function program($bahasa){
        $data['lang'] = $bahasa;
        
        $data['caption_program_and_event'] = $this->get_data_by_id('57');
        $p_category = DB::table('post_has_category')
        ->where('tags_id', '1811731122059639')
        ->orWhere('tags_id', '1811731104397737')->get();
        $data['arr_category'] = [];
        foreach($p_category as $key => $value){
            $data['arr_category'][$value->post_id] = $value->tags_id;
        } 

        $data['program_event'] = $this->get_data_by_menu('1811038194284839');

        return view('web::about.programs', $data);
    }

    public function call_us($bahasa){
        $data['lang'] = $bahasa;
        $data['content_call_us'] = $this->get_data_by_id('52');
        $data['join_us'] = $this->get_data_by_id('69');
        
        return view('web::about.contact', $data);
    }

    public function report($bahasa){
        $data['lang'] = $bahasa;
        $data['source_founding_section'] = $this->get_data_by_id('48');
        $data['source_founding_content'] = $this->get_data_by_menu('1811677495554972');

        $data['fund_1'] = $this->get_data_by_id('49');
        $data['fund_2'] = $this->get_data_by_id('50');
        $data['fund_3'] = $this->get_data_by_id('51');

        $data['section_report'] = $this->get_data_by_id('53');
        $data['content_report'] = $this->get_data_by_menu('1811683412333297');

        return view('web::about.reports', $data);
    }

    public function gallery($bahasa){
        $data['lang'] = $bahasa;
        
        $data['galeri'] = $this->get_data_by_menu('1811704627407786');
        return view('web::about.gallery', $data);
    }

	public function donation($invoice = null)
    {
        $data = [];
        if(!empty($invoice)){
            $data['invoice'] = $invoice;
        }
        return view('web::about.donation', $data);
    }

    public function funding($bahasa){
        $data['lang'] = $bahasa;

        $data['source_founding_section'] = $this->get_data_by_id('48');
        $data['source_founding_content'] = $this->get_data_by_menu('1811677495554972');
        $data['fund_1'] = $this->get_data_by_id('49');
        $data['fund_2'] = $this->get_data_by_id('50');
        $data['fund_3'] = $this->get_data_by_id('51');

        return view('web::about.funding', $data);
    }

    public function partnership($bahasa){
        $data['lang'] = $bahasa;

        return view('web::about.partnership', $data);
    }
}	

?>