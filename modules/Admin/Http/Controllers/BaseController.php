<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Helper;

class BaseController extends Controller
{
    protected $page_title                      = '';
    protected $page_header                     = false;
    protected $output_return_code              = 200;
    protected $output_return_data              = [
        'status'  => false,
        'errors'  => null,
        'data'    => null,
        'message' => null
    ];
    protected $output_validator_rules          = [];
    protected $output_validator_message        = [
        'required' => ':attribute field is required.',
    ];
    protected $output_validator_message_custom = [

    ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected function __construct()
    {
        $this->middleware('auth');
    }

    protected function check_user_session()
    {
        return dd(Session::get('success'));
    }

    protected function set_theme($parameter_page = null, $parameter_option = null)
    {

        /** Validation option menu id for roles permission */
        $parameter_permission_flag = '';
        if (!empty($parameter_option)) {
            if (!empty($parameter_option['menu_id'])) {
                $parameter_permission_flag = $parameter_option['menu_id'];
            }
        }

        $request        = Request();
        $request_prefix = $request->route()->getPrefix();

        $parameter_main['output_data'] =
            [
                'page_title'   => $this->page_title,
                'page_header'  => $this->page_header,
                'toastr'       => true,
                'swal'         => false,
                'datatables'   => false,
                'content_js'   => "{$parameter_page }_js",
                'contents_php' => "{$parameter_page}",
                'route_prefix' => str_replace('/', '', $request_prefix),
             //   'user' => User::find(\Auth::user()->id)
            ];

        /** Add additional / custom data to variable */
        if (!empty($parameter_option)) {
            $parameter_main['output_data'] += $parameter_option;
        }

        //$data = array_merge($opsi, $main);
        if(!empty(\Auth::user()->id)){
            if(Session::get('tema') == 'bootstrap'){
                return view('components.layouts.app', $parameter_main);
            } else {
                return view('components.layouts.app_t2', $parameter_main);    
            }
        } else {
            return view('components.layouts.app_db_builder', $parameter_main);   
        }
        //
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    protected function output_response_json()
    {
        return response()->json($this->output_return_data, $this->output_return_code, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }

    protected function submit_validator($parameter_request = null)
    {
        $output = Validator::make(
            $parameter_request->all(),
            $this->output_validator_rules,
            $this->output_validator_message_custom,
        );
        return $output;
    }
}
