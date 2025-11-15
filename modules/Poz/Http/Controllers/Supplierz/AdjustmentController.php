<?php

namespace Modules\Poz\Http\Controllers\Supplierz;

use Modules\Reference\Http\Controllers\Controller;
use Yajra\DataTables\DataTables as Table;
use Modules\Poz\Models\Adjustment;
use Modules\Poz\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdjustmentController extends Controller
{
    /**
     * Show the dashboard page.
     */
    public function index()
    {
        $data = [];
        // dbuilder_table untuk membuat generate table pada kolom header dan pemanggilan kolom database
        $data['column'] = [
            //DT_RowIndex usahakan false karena tidak ada secara fisik pada database
            dbuilder_table('product_id', 'Nama Produk', false, true),
            dbuilder_table('supplier_id', 'Supplier', false, false),
            dbuilder_table('status', 'Status'),
            dbuilder_table('qty', 'Jumlah Stock')
        ];

        $data['title'] = 'Daftar Adjustment';

        return view('poz::supplierz.adjustment', $data);
    }

    public function create(Request $request)
    {

        return view('poz::supplierz.adjustment', [
            'action' => 'Create'
        ]);
    }

    public function edit(Request $request)
    {

        return view('poz::master.brand.index', [
            'action' => 'Update'
        ]);
    }

    public function destroy(Request $request)
    {
        $brand = Brand::findOrFail($request->brand); // Mencari pozt berdasarkan ID
        $brand->delete(); // Melakukan soft delete

        return redirect(route('poz::master.brand.index'))->with('msg-sukses', "Data berhasil dihapus");
    }

    public function adjustmentTable(Request $request)
    {
        $outletId = $request->outlet;
        $sup = Supplier::where('user_id', auth()->id())->first();

        $today = Carbon::today();

        $adjustment = Adjustment::with('outlets')
            ->whereHas('product.productStockAdjustItems', function ($query) use ($today) {
              $query->whereDate('created_at', $today);
            })
            ->whereNull('deleted_at')
            ->where('supplier_id', $sup->id);

        if (!empty($search = $request->search)) {
            $adjustment->where(function ($query) use ($search) {
                $query->where('name', 'ILIKE', "%{$search}%");
            });
        }

        if (!empty($order = $request->filter)) {
            if ($order === 'new') {
                $adjustment->orderBy('id', 'desc');
            } elseif ($order === 'old') {
                $adjustment->orderBy('id', 'asc');
            }
        }


        return Table::of($adjustment)
            ->addIndexColumn()
            ->addColumn('product_id', function ($row){
                return $row->product->name;
            })
            ->addColumn('supplier_id', function ($row){
                return $row->supplier->name;
            })
            ->addColumn('status', function($row){
                if($row->status == 'plus'){
                    return 'Penambahan Stok';
                } else {
                    return 'Pengurangan Stok';
                }
            })
            ->addColumn('qty', function($row){
                return $row->qty;
            })
            ->make(true);
    }
}
