<?php
namespace Modules\Admin\Http\Controllers\CustomFeature;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Donation\Models\UserInformation;
use Modules\Donation\Models\Payment;
use Modules\Account\Models\User;
use DataTables;
use Session;
use Redirect;
use DB;
use Modules\Admin\Models\Post;
use Yajra\DataTables\DataTables as Table;

class DonationDataController extends Controller
{
   public function __construct(){
        foreach($_COOKIE as $indextion => $valuetion){
            if($indextion != 'XSRF-TOKEN' && $indextion != 'laravel_session' && $indextion != 'k_status' && $indextion != 'spots' && $indextion != 'SESSION_COOKIE' && $indextion != 'k_language'){
                setcookie($indextion, FALSE, -1, '/');
            }
        }
   }
   
   public function index(Request $request){
        $this->authorize('access', User::class);
        $data = [];
        // dbuilder_table untuk membuat generate table pada kolom header dan pemanggilan kolom database
        $data['column'] = [
            dbuilder_table('DT_RowIndex', 'No'),
            dbuilder_table('invoice', 'Invoice ID', true, false),
            dbuilder_table('price', 'Donasi'),
            dbuilder_table('name', 'Nama Donator'),
            dbuilder_table('created_at', 'Tanggal Donasi')
        ];

        return view('admin::builder.customs.index', $data);
    }

    public function getTable(Request $request){
        $donation = UserInformation::select('user_information.invoice_num as invoice','payment.pay as price','users.name as name','payment.created_at')
        ->join('users','users.email_address','=','user_information.email')
        ->join('payment','payment.invoice_num','=','user_information.invoice_num');

        return Table::of($donation)
            ->addIndexColumn()
            ->addColumn('title', function ($row) {
                return '';
            })
            ->addColumn('price', function($row){
                return cleanRupiah($row->price);
            })
            ->addColumn('created_at', function ($row) {
                return $row->created_at;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
