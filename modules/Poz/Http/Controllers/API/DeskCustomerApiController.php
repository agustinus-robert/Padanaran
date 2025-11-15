<?php

namespace Modules\Poz\Http\Controllers\API;

use Modules\Reference\Http\Controllers\Controller;
use Yajra\DataTables\DataTables as Table;
use Modules\Poz\Models\SaleDirectCustomerDesk;
use Modules\Poz\Repositories\SaleCustomerDeskDirectRepository;
use Modules\Account\Models\UserToken;
use Illuminate\Http\Request;

class DeskCustomerApiController extends Controller
{
    use SaleCustomerDeskDirectRepository;

    /**
     * Show the dashboard page.
     */
    public function index(Request $request)
    {
        $user = $request->header('X-API-KEY');
        $userToken = UserToken::where('token', $user)->first();

        $saleDeskCustomer = SaleDirectCustomerDesk::where('created_by', $userToken->user_id)->first();

        return response()->json($saleDeskCustomer);
    }

    public function store(Request $request)
    {
        if ($customer = $this->storeCustomerDesk($request->all(), $request->header('X-API-KEY'))) {
            return $customer;
        } else {
            return $customer;
        }
    }

    public function destroy($id) {}
}
