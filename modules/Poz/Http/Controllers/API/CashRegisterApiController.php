<?php

namespace Modules\Poz\Http\Controllers\API;

use Modules\Reference\Http\Controllers\Controller;
use Yajra\DataTables\DataTables as Table;
use Modules\Poz\Models\CashRegister;
use Modules\Poz\Repositories\CashRepository;
use Modules\Account\Models\UserToken;
use Illuminate\Http\Request;

class CashRegisterApiController extends Controller
{
    use CashRepository;
    /**
     * Show the dashboard page.
     */
    public function index(Request $request)
    {
        $getTokenUser = $request->header('X-API-KEY');
        $userToken = UserToken::where('token', $getTokenUser)->first();

        $cashRegister = CashRegister::where('casier_id', $userToken->user_id)->first();
        return response()->json($cashRegister);
    }

    public function store(Request $request)
    {
        $response = $this->storeTopUp($request->all(), $request->header('X-API-KEY'));

        if ($response instanceof \Illuminate\Http\JsonResponse) {
            return $response; // Kembalikan langsung respons JSON dari storeSale
        }

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan'
        ], 500);
    }
}
