<?php

namespace Modules\Poz\Http\Controllers\API;

use Modules\Reference\Http\Controllers\Controller;
use Yajra\DataTables\DataTables as Table;
use Modules\Poz\Models\UserOutlet;
use Modules\Poz\Models\Outlet;
use Modules\Account\Models\User;
use Modules\Account\Models\UserToken;
use Illuminate\Http\Request;

class OutletApiController extends Controller
{
    /**
     * Show the dashboard page.
     */
    public function index(Request $request)
    {
        $user = $request->header('X-API-KEY');
        $userToken = UserToken::where('token', $user)->first();

        $userOutlet = UserOutlet::where('user_id', $userToken->user_id)->first();

        $userName = User::find($userToken->user_id)->name;
        $outlets = Outlet::find($userOutlet->outlet_id);

        return response()->json(['casier_name' => $userName, 'outlet_name' => $outlets->name, 'location' => $outlets->description]);
    }
}
