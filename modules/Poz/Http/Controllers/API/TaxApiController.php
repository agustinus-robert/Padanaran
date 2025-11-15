<?php

namespace Modules\Poz\Http\Controllers\API;

use Modules\Reference\Http\Controllers\Controller;
use Yajra\DataTables\DataTables as Table;
use Modules\Poz\Models\Tax;
use Modules\Poz\Repositories\TaxDirectRepository;
use Illuminate\Http\Request;

class TaxApiController extends Controller
{
    use TaxDirectRepository;
    /**
     * Show the dashboard page.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $brand = Tax::paginate($perPage);
        return response()->json($brand);
    }

    public function cekPajak(Request $request)
    {
        $taxDirect = Tax::where('actived_on', 2)->first();
        return response()->json($taxDirect);
    }

    // public function update(Request $request){
    //     if(updateTax::find($transaction_id)->sale_status == 1){
    //         $response = $this->addDirectItems($request->all(), $transaction_id, $request->header('X-API-KEY'));

    //         if ($response instanceof \Illuminate\Http\JsonResponse) {
    //             return $response;
    //         }
    //     }

    //     return response()->json([
    //         'success' => false,
    //         'message' => 'Terjadi kesalahan'
    //     ], 500);
    // }
}
