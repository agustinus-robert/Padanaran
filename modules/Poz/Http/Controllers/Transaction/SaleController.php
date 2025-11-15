<?php

namespace Modules\Poz\Http\Controllers\Transaction;

use Modules\Reference\Http\Controllers\Controller;
use Yajra\DataTables\DataTables as Table;
use Modules\Poz\Models\Product;
use Modules\Poz\Models\Sale as SaleData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
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
            dbuilder_table('reference', 'Nomor Referensi', false, true),
          //  dbuilder_table('poz', 'poz Status', false, true),
            dbuilder_table('sale_status', 'Status Penjualan', true, false),
            dbuilder_table('grand_total', 'Total'),
            // dbuilder_table('action', 'Aksi')
        ];

        return view('poz::transaction.sale', $data);
    }

    public function create(Request $request)
    {

        return view('poz::transaction.sale', [
            'action' => 'Create'
        ]);
    }

    public function edit(Request $request)
    {

        return view('poz::transaction.sale', [
            'action' => 'Update'
        ]);
    }

    public function destroy(Request $request)
    {
        $sale = SaleData::findOrFail($request->sale); // Mencari pozt berdasarkan ID
        $sale->delete(); // Melakukan soft delete

        return redirect(route('poz::transaction.sale.index'))->with('msg-sukses', "Data berhasil dihapus");
    }

    public function invoice($sale_id)
    {
        $data['sale'] = SaleData::find($sale_id);

        return view('poz::transaction.invoice_pos', $data);
    }

    public function saleTable(Request $request)
    {
        // ->whereHas('outlets', function ($query) use ($outletId) {
        //     $query->where('outlet_id', $outletId);
        // });

        $outletId = $request->outlet;
        $sale = $saleData = SaleData::select('id','reference', 'sale_status', 'grand_total');
        $saledirect = DB::table('sale_direct')
            ->select('id', 'reference', 'sale_status', 'grand_total')
            ->whereNull('deleted_at');


        if (!empty($search = $request->search)) {
            $sale->where(function ($query) use ($search) {
                $query->where('name', 'ILIKE', "%{$search}%");
            });
        }

        if (!empty($order = $request->filter)) {
            if ($order === 'new') {
                $sale->orderBy('id', 'desc');
            } elseif ($order === 'old') {
                $sale->orderBy('id', 'asc');
            }
        }

        $combined = $sale->unionAll($saledirect);
        $query = DB::query()->fromSub($combined, 'combined')->orderBy('id', 'desc');

        return Table::of($query)
            ->addIndexColumn()
            // ->addColumn('poz', function ($row) {
            //     $html = '';

            //     if ($row->poz == 1) {
            //         $html .= '<span class="badge text-bg-success">Penjualan poz</span>';
            //     } else {
            //         $html .= '<span class="badge text-bg-primary">Penjualan Reguler</span>';
            //     }

            //     return $html;
            // })
            ->addColumn('sale_status', function ($row) {
                $html = '';
                if ($row->sale_status == 3) {
                    $html .= '<span class="badge text-bg-success">completed</span>';
                } else {
                    $html .= '<span class="badge text-bg-warning">Waiting</span>';
                }

                return $html;
            })
            ->addColumn('grand_total', function ($row) {
                return 'Rp ' . number_format($row->grand_total, 2);
            })
            ->addColumn('action', function ($row) {
                $template = '';

                // $template .= view('poz::layouts_master.component.button_invoice', array('id' => $row->id, 'invoice' => route('poz::transaction.sale.pos-invoice', ['sale_id' => $row->id])))->render();
                // $template .= view('poz::layouts_master.component.button_edit', array('id' => $row->id, 'update' => route('poz::transaction.sale.edit', ['sale' => $row->id])))->render();
                // $template .= view('poz::layouts_master.component.button_delete', array('id' => $row->id, 'delete' => route('poz::transaction.sale.destroy', ['sale' => $row->id])))->render();


                return $template;
            })
            ->rawColumns(['gambar', 'poz', 'sale_status', 'action'])->make(true);
    }
}
