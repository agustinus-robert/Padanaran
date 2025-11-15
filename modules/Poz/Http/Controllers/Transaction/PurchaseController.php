<?php

namespace Modules\Poz\Http\Controllers\Transaction;

use Modules\Reference\Http\Controllers\Controller;
use Yajra\DataTables\DataTables as Table;
use Modules\Poz\Models\Product;
use Modules\Poz\Models\Purchase as PurchaseData;
use Illuminate\Http\Request;

class PurchaseController extends Controller
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
            dbuilder_table('purchase_status', 'Status Pembelian', true, false),
            dbuilder_table('grand_total', 'Total'),
            dbuilder_table('action', 'Aksi')
        ];

        return view('poz::transaction.purchase', $data);
    }

    public function create(Request $request)
    {

        return view('poz::transaction.purchase', [
            'action' => 'Create'
        ]);
    }

    public function edit(Request $request)
    {

        return view('poz::transaction.purchase', [
            'action' => 'Update'
        ]);
    }

    public function destroy(Request $request)
    {
        $sale = SaleData::findOrFail($request->sale); // Mencari pozt berdasarkan ID
        $sale->delete(); // Melakukan soft delete

        return redirect(route('poz::transaction.purchase.index'))->with('msg-sukses', "Data berhasil dihapus");
    }

    public function invoice($purchase_id)
    {
        $data['purchase'] = PurchaseData::find($purchase_id);

        return view('poz::transaction.invoice_purchase', $data);
    }

    public function change_status($id, Request $request)
    {
        $purchase = PurchaseData::find($id);

        if ($request->estimatedConfirmStatus == 2) {
            $data['purchase_delivered'] = date('Y-m-d H:i:s');
            $data['purchase_status'] = 2;
        } else if ($request->estimatedConfirmStatus == 3) {
            $data['purchase_completed'] = date('Y-m-d H:i:s');
            $data['purchase_status'] = 3;
        }

        if ($purchase->update($data) == true) {
            return redirect(route('poz::transaction.purchase.index'))->with('msg-sukses', "Perubahan Status telah disimpan");
        } else {
            return redirect(route('poz::transaction.purchase.index'))->with('msg-gagal', "Perubahan Status gagal disimpan");
        }
    }

    public function purchaseTable(Request $request)
    {
        $outletId = $request->outlet;

        $purchase = PurchaseData::whereNull('deleted_at')
            ->whereHas('outlets', function ($query) use ($outletId) {
                $query->where('outlet_id', $outletId);
            });

        if (!empty($search = $request->search)) {
            $purchase->where(function ($query) use ($search) {
                $query->where('name', 'ILIKE', "%{$search}%");
            });
        }

        if (!empty($order = $request->filter)) {
            if ($order === 'new') {
                $purchase->orderBy('id', 'desc');
            } elseif ($order === 'old') {
                $purchase->orderBy('id', 'asc');
            }
        }

        return Table::of($purchase)
            ->addIndexColumn()
            ->addColumn('purchase_status', function ($row) {
                $html = '';
                if ($row->purchase_status == 1) {
                    $html .= '<span class="badge text-bg-info">ordered</span>';
                } else if ($row->purchase_status == 2) {
                    $html .= '<span class="badge text-bg-primary">delivered</span>';
                } else {
                    $html .= '<span class="badge text-bg-success">completed</span>';
                }

                return $html;
            })
            ->addColumn('grand_total', function ($row) {
                return 'Rp ' . number_format($row->grand_total, 2);
            })
            ->addColumn('action', function ($row) {
                $template = '';


                if ($row->purchase_status != 3) {
                    $template .= view('poz::layouts_master.component.button_change_status', array('id' => $row->id, 'statusUrl' => route('poz::transaction.purchase.purchase_status', ['purchase_id' => $row->id])))->render();
                }

                if ($row->purchase_status == 1) {

                    $template .= view('poz::layouts_master.component.button_edit', array('id' => $row->id, 'update' => route('poz::transaction.purchase.edit', ['purchase' => $row->id])))->render();
                    $template .= view('poz::layouts_master.component.button_delete', array('id' => $row->id, 'delete' => route('poz::transaction.purchase.destroy', ['purchase' => $row->id])))->render();
                }


                $template .= view('poz::layouts_master.component.button_invoice', array('id' => $row->id, 'invoice' => route('poz::transaction.purchase.invoice', ['purchase_id' => $row->id])))->render();


                return $template;
            })
            ->rawColumns(['gambar', 'poz', 'purchase_status', 'action'])->make(true);
    }
}
