<?php

namespace Modules\Poz\Http\Controllers\Transaction;

use Modules\Reference\Http\Controllers\Controller;
use Yajra\DataTables\DataTables as Table;
use Modules\Poz\Models\Product;
use Modules\Poz\Models\Transfer as TransferData;
use Illuminate\Http\Request;

class TransferController extends Controller
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
            dbuilder_table('transfer_status', 'Status Transfer', true, false),
            dbuilder_table('action', 'Aksi')
        ];

        return view('poz::transaction.transfer', $data);
    }

    public function create(Request $request)
    {

        return view('poz::transaction.transfer', [
            'action' => 'Create'
        ]);
    }

    public function edit(Request $request)
    {

        return view('poz::transaction.transfer', [
            'action' => 'Update'
        ]);
    }

    public function destroy(Request $request)
    {
        $sale = SaleData::findOrFail($request->sale); // Mencari pozt berdasarkan ID
        $sale->delete(); // Melakukan soft delete

        return redirect(route('poz::transaction.transfer.index'))->with('msg-sukses', "Data berhasil dihapus");
    }

    public function invoice($purchase_id)
    {
        $data['transfer'] = PurchaseData::find($purchase_id);

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

    public function transferTable()
    {
        $transfer = TransferData::select('*')->whereNull('deleted_at');

        return Table::of($transfer)
            ->addIndexColumn()
            ->addColumn('transfer_status', function ($row) {
                $html = '';
                if ($row->transfer_status == 1) {
                    $html .= '<span class="badge text-bg-info">ordered</span>';
                } else if ($row->transfer_status == 2) {
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


                // if($row->transfer_status != 3){
                //     $template .= view('poz::layouts_master.component.button_change_status', array('id' => $row->id, 'statusUrl' => route('poz::transaction.purchase.transfer_status', ['purchase_id' => $row->id])))->render();
                // }

                if ($row->transfer_status == 1) {

                    $template .= view('poz::layouts_master.component.button_edit', array('id' => $row->id, 'update' => route('poz::transaction.transfer.edit', ['transfer' => $row->id])))->render();
                    $template .= view('poz::layouts_master.component.button_delete', array('id' => $row->id, 'delete' => route('poz::transaction.transfer.destroy', ['transfer' => $row->id])))->render();
                }


                $template .= view('poz::layouts_master.component.button_invoice', array('id' => $row->id, 'invoice' => route('poz::transaction.purchase.invoice', ['purchase_id' => $row->id])))->render();


                return $template;
            })
            ->rawColumns(['gambar', 'poz', 'transfer_status', 'action'])->make(true);
    }
}
