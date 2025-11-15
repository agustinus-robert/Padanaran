<?php

namespace Modules\Poz\Http\Controllers\Supplierz;

use Modules\Reference\Http\Controllers\Controller;
use Yajra\DataTables\DataTables as Table;
use Modules\Poz\Models\ProductQuotation;
use Illuminate\Http\Request;

class QuotationController extends Controller
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
            dbuilder_table('reference', 'Referensi', false, false),
            dbuilder_table('outlet_name', 'Nama Outlet'),
            dbuilder_table('status', 'Status'),
            dbuilder_table('date', 'Tanggal Pengajuan'),
            dbuilder_table('action', 'Aksi')
        ];

        return view('poz::supplierz.quotation.index', $data);
    }

    public function create(Request $request)
    {

        return view('poz::supplierz.quotation.index', [
            'action' => 'Create'
        ]);
    }

    public function show(ProductQuotation $quotation){
        return view('poz::supplierz.quotation.show', compact('quotation'));
    }

    public function edit(Request $request)
    {

        return view('poz::supplierz.quotation.index', [
            'action' => 'Update'
        ]);
    }

    public function destroy(ProductQuotation $quotation)
    {
        $quotation->delete(); // Melakukan soft delete

        return redirect()->back()->with('msg-sukses', 'Status quotation berhasil dihapus!');
    }

    public function send(ProductQuotation $quotation){
        $quotation->update([
            'status' => 1
        ]);

        return redirect()->back()->with('msg-sukses', 'Status quotation berhasil diperbarui!');
    }

    public function quotationTable(Request $request)
    {
        $outletId = $request->outlet;
        $quotation = ProductQuotation::with('outlets')->whereNull('deleted_at');

        if (!empty($search = $request->search)) {
            $quotation->where(function ($query) use ($search) {
                $query->where('name', 'ILIKE', "%{$search}%");
            });
        }

        if (!empty($order = $request->filter)) {
            if ($order === 'new') {
                $quotation->orderBy('id', 'desc');
            } elseif ($order === 'old') {
                $quotation->orderBy('id', 'asc');
            }
        }

        return Table::of($quotation)
            ->addIndexColumn()
            ->addColumn('reference', function ($row) {
               return $row->reference;
            })
            ->addColumn('outlet_name', function ($row) {
                return $row->outlets->first()->name;
            })
            ->addColumn('status', function($row){
                if($row->status == 1){
                    return '<span class="badge bg-warning">Menunggu</span>';
                } else if($row->status == 2){
                    return '<span class="badge bg-success">Diterima</span>';
                } else if($row->status == 3) {
                    return '<span class="badge bg-danger">Ditolak</span>';
                } else {
                    return '<span class="badge bg-secondary">Belum Dikirim</span>';
                }
            })
            ->addColumn('date', function($row){
                 return \Carbon\Carbon::parse($row->created_at)->translatedFormat('d F Y H:i');
            })
            ->addColumn('action', function ($row) {
                $template = '';

                if(empty($row->status)){
                    $template .= view('poz::layouts_master.component.button_notification', array('id' => $row->id, 'notify' => route('poz::supplierz.quotation.send', ['quotation' => $row->id])))->render();
                    $template .= view('poz::layouts_master.component.button_edit', array('id' => $row->id, 'update' => route('poz::supplierz.quotation.edit', ['quotation' => $row->id])))->render();
                    $template .= view('poz::layouts_master.component.button_delete', array('id' => $row->id, 'delete' => route('poz::supplierz.quotation.destroy', ['quotation' => $row->id])))->render();
                }

                //
                $template .= view('poz::layouts_master.component.button_show', array('id' => $row->id, 'show' => route('poz::supplierz.quotation.show', ['quotation' => $row->id])))->render();
                return $template;
            })
            ->rawColumns(['status', 'action'])->make(true);
    }
}
