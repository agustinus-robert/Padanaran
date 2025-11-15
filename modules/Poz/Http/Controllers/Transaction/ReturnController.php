<?php

namespace Modules\Poz\Http\Controllers\Transaction;

use Modules\Reference\Http\Controllers\Controller;
use Yajra\DataTables\DataTables as Table;
use Modules\Poz\Models\Product;
use Modules\Poz\Models\ReturnGoods as ReturnData;
use Illuminate\Http\Request;

class ReturnController extends Controller
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
            dbuilder_table('name', 'Customer', false, false),
            dbuilder_table('grand_total', 'Total'),
            dbuilder_table('action', 'Aksi')
        ];

        return view('poz::transaction.return', $data);
    }

    public function create(Request $request)
    {

        return view('poz::transaction.return', [
            'action' => 'Create'
        ]);
    }

    public function edit(Request $request)
    {

        return view('poz::transaction.return', [
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

        return view('poz::transaction.invoice_poz', $data);
    }

    public function returnTable(Request $request)
    {
        $outletId = $request->outlet;
        $sale = ReturnData::whereNull('deleted_at')
            ->whereHas('outlets', function ($query) use ($outletId) {
                $query->where('outlet_id', $outletId);
            });

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

        return Table::of($sale)
            ->addIndexColumn()
            ->addColumn('poz', function ($row) {
                $html = '';

                if ($row->poz == 1) {
                    $html .= '<span class="badge text-bg-success">Penjualan poz</span>';
                } else {
                    $html .= '<span class="badge text-bg-primary">Penjualan Reguler</span>';
                }

                return $html;
            })
            ->addColumn('sale_status', function ($row) {
                $html = '';
                if ($row->sale_status == 1) {
                    $html .= '<span class="badge text-bg-success">completed</span>';
                }

                return $html;
            })
            ->addColumn('grand_total', function ($row) {
                return 'Rp ' . number_format($row->grand_total, 2);
            })
            ->addColumn('action', function ($row) {
                $template = '';

                $template .= view('poz::layouts_master.component.button_invoice', array('id' => $row->id, 'invoice' => route('poz::transaction.sale.poz-invoice', ['sale_id' => $row->id])))->render();
                $template .= view('poz::layouts_master.component.button_edit', array('id' => $row->id, 'update' => route('poz::transaction.sale.edit', ['sale' => $row->id])))->render();
                $template .= view('poz::layouts_master.component.button_delete', array('id' => $row->id, 'delete' => route('poz::transaction.sale.destroy', ['sale' => $row->id])))->render();


                return $template;
            })
            ->rawColumns(['gambar', 'poz', 'sale_status', 'action'])->make(true);
    }
}
