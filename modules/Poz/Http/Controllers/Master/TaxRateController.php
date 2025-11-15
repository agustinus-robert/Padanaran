<?php

namespace Modules\Poz\Http\Controllers\Master;

use Modules\Reference\Http\Controllers\Controller;
use Yajra\DataTables\DataTables as Table;
use Modules\Poz\Models\Tax;
use Illuminate\Http\Request;

class TaxRateController extends Controller
{
    /**
     * Show the dashboard page.
     */
    public function index()
    {
        $data = [];
        // dbuilder_table untuk membuat generate table pada kolom header dan pemanggilan kolom database
        $data['column'] = [
            dbuilder_table('code', 'Code'),
            dbuilder_table('name', 'Nama', false, true),
            dbuilder_table('sale_active', 'Aktif', false, false),
            dbuilder_table('action', 'Aksi')
        ];

        return view('poz::master.tax.index', $data);
    }

    public function create(Request $request)
    {

        return view('poz::master.tax.index', [
            'action' => 'Create'
        ]);
    }

    public function edit(Request $request)
    {

        return view('poz::master.tax.index', [
            'action' => 'Update'
        ]);
    }

    public function destroy(Request $request)
    {
        $tax = Tax::findOrFail($request->tax); // Mencari pozt berdasarkan ID
        $tax->delete(); // Melakukan soft delete

        return redirect(route('poz::master.tax.index'))->with('msg-sukses', "Data berhasil dihapus");
    }

    public function taxTable(Request $request)
    {
        $outletId = $request->outlet;
        $tax = Tax::select('*')->whereNull('deleted_at')->whereHas('outlets', function ($query) use ($outletId) {
            $query->where('outlet_id', $outletId);
        });;

        if (!empty($search = $request->search)) {
            $tax->where(function ($query) use ($search) {
                $query->where('name', 'ILIKE', "%{$search}%");
            });
        }

        if (!empty($order = $request->filter)) {
            if ($order === 'new') {
                $tax->orderBy('id', 'desc');
            } elseif ($order === 'old') {
                $tax->orderBy('id', 'asc');
            }
        }

        return Table::of($tax)
            ->addIndexColumn()
            ->addColumn('sale_active', function ($row) {
                $template = '';
                if ($row->sale_active == 1) {
                    $template .= '<span class="badge text-bg-primary">Transaksi Aktif</span>';
                } else {
                    $template .= '-';
                }

                return $template;
            })
            ->addColumn('action', function ($row) use ($request) {
                $template = '';
                $outletId = $request->outlet;


                $template .= view('poz::layouts_master.component.button_edit', array('id' => $row->id, 'update' => route('poz::master.tax.edit', ['tax' => $row->id]) . '?outlet=' . $outletId))->render();
                $template .= view('poz::layouts_master.component.button_delete', array('id' => $row->id, 'delete' => route('poz::master.tax.destroy', ['tax' => $row->id]) . '?outlet=' . $outletId))->render();


                return $template;
            })
            ->rawColumns(['action', 'sale_active'])->make(true);
    }
}
