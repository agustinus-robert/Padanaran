<?php

namespace Modules\Poz\Http\Controllers\API;

use Modules\Reference\Http\Controllers\Controller;
use Modules\Poz\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Modules\Account\Models\UserToken;
use Modules\Poz\Models\Casier;

class SupplierApiController extends Controller
{
    /**
     * Show the dashboard page.
     */
    private $keys = [
        'code',
        'name',
        'email',
        'phone',
        'address',
        'location',
        'image_name'
    ];

    private $user, $userToken, $casier;

    // public function __construct(Request $request){
    //     $this->user = $request->header('X-API-KEY');
    //     $this->userToken = UserToken::where('token', $this->user)->first();
    //     $this->casier = Casier::where('user_id', $this->userToken->user_id)->first();
    // }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $suppliers = Supplier::paginate($perPage);

        if ($suppliers->total() === 0) {
            return response()->json([
                'message' => 'Supplier belum ada'
            ], 404);
        }

        return response()->json($suppliers);
    }

    public function store(Request $request){
        $this->user = $request->header('X-API-KEY');
        $this->userToken = UserToken::where('token', $this->user)->first();
        $this->casier = Casier::where('user_id', $this->userToken->user_id)->first();

        $location = 'file_supplier/' . uniqid();
        $digits = '0123456789';
        $randomNumbers = substr(str_shuffle(str_repeat($digits, 10)), 0, 10);

        $data = [
            'code' => $randomNumbers,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'created_by' => $this->userToken->user_id
        ];

        if (isset($data['document']) && $data['document'] instanceof \Illuminate\Http\UploadedFile) {
            $data['document']->storeAs($location, $data['document']->getFilename(), 'public');
            $data['location'] = $location;
            $data['image_name'] = $data['document']->getFilename();
        }

        $supplier = new Supplier(Arr::only($data, $this->keys));
        if ($supplier->save()) {
            $outletId = $this->casier->outlet_id;

            if ($outletId) {
                $supplier->outlets()->attach($outletId);
            }

            return response()->json([
                'message' => 'Supplier telah disimpan'
            ], 200);
        }

        return response()->json([
            'message' => 'Supplier gagal disimpan'
        ], 500);
    }

    public function show($supplier_api, Request $request){
        $this->user = $request->header('X-API-KEY');
        $this->userToken = UserToken::where('token', $this->user)->first();
        $this->casier = Casier::where('user_id', $this->userToken->user_id)->first();

        $supplierShow = Supplier::find($supplier_api);

        if (!$supplierShow) {
            return response()->json([
                'message' => 'Supplier tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'message' => 'Supplier Berhasil ditemukan',
            'data' => $supplierShow
        ], 200);
    }

    public function update($supplier_api, Request $request){
        $this->user = $request->header('X-API-KEY');
        $this->userToken = UserToken::where('token', $this->user)->first();
        $this->casier = Casier::where('user_id', $this->userToken->user_id)->first();

        $location = 'file_supplier/' . uniqid();
        $supplier = Supplier::find($supplier_api);
        if (!$supplier) {
            return false;
        }

        $data = [
            'code' => $supplier->code,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'updated_by' => $this->userToken->user_id
        ];

        if ($request->hasFile('document') && $request->file('document') instanceof \Illuminate\Http\UploadedFile) {
            $request->file('document')->storeAs($location, $request->file('document')->getClientOriginalName(), 'public');
            $data['location'] = $location;
            $data['image_name'] = $request->file('document')->getClientOriginalName();
        }

        if ($supplier->update(Arr::only($data, $this->keys))) {
            $outletId = $this->casier->outlet_id;

            if ($outletId) {
                $supplier->outlets()->syncWithoutDetaching([$outletId]);
            }

            return response()->json([
                'message' => 'Supplier telah disimpan'
            ], 200);
        }

        return response()->json([
            'message' => 'Supplier gagal disimpan'
        ], 500);
    }

    public function destroy($supplier_api, Request $request){
        if (Supplier::where('id', $supplier_api)->delete()) {
            return response()->json([
                'message' => 'Supplier telah dihapus'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Supplier gagal dihapus'
            ], 500);
        }
    }
}
