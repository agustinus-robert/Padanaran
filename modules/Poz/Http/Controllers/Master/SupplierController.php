<?php

namespace Modules\Poz\Http\Controllers\Master;

use Modules\Reference\Http\Controllers\Controller;
use Yajra\DataTables\DataTables as Table;
use Modules\Poz\Models\Supplier;
use Modules\Poz\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
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
            dbuilder_table('image', 'Gambar', false, false, 'w10'),
            dbuilder_table('name', 'Nama'),
            dbuilder_table('email', 'Email', false, true),
            dbuilder_table('phone', 'Nomor HP', false, true),
            dbuilder_table('action', 'Aksi')
        ];

        $data['title'] = 'Daftar Supplier';

        return view('poz::master.supplier.index', $data);
    }

    public function create(Request $request)
    {
        return view('poz::master.supplier.index', [
            'action' => 'Create'
        ]);
    }

    public function edit(Request $request)
    {

        return view('poz::master.supplier.index', [
            'action' => 'Update'
        ]);
    }

    public function destroy(Request $request)
    {
        $id = request()->query('outlet', auth()->user()->current_outlet_id);

        $supplier = Supplier::findOrFail($request->supplier);
        $supplier->delete();

        return redirect(route('poz::master.supplier.index', ['outlet' => $id]))->with('msg-sukses', "Data berhasil dihapus");
    }

    public function supplierTable(Request $request)
    {
        $outletId = $request->outlet;
        $supplier = Supplier::with('outlets')
            ->whereNull('deleted_at')
            ->whereHas('outlets', function ($query) use ($outletId) {
                $query->where('outlet_id', $outletId);
            });

        if (!empty($search = $request->search)) {
            $supplier->where(function ($query) use ($search) {
                $query->where('name', 'ILIKE', "%{$search}%");
            });
        }

        if (!empty($order = $request->filter)) {
            if ($order === 'new') {
                $supplier->orderBy('id', 'desc');
            } elseif ($order === 'old') {
                $supplier->orderBy('id', 'asc');
            }
        }


        return Table::of($supplier)
            ->addIndexColumn()
            ->addColumn('image', function ($row) {
                if (!empty($row->location) && !empty($row->image_name)) {
                    $image = $row->location . '/' . $row->image_name;

                    return "<img width='50' height='50' src='" . asset('uploads/' . $image) . "' />";
                } else {
                    return "<img src='https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg?20200913095930' width='50' height='50' />";
                }
            })
            ->addColumn('action', function ($row) use ($request) {
                $template = '';
                $outletId = $request->outlet;

                $template .= view('poz::layouts_master.component.button_edit', array('id' => $row->id, 'update' => route('poz::master.supplier.edit', ['supplier' => $row->id]) . '?outlet=' . $outletId))->render();
                $template .= view('poz::layouts_master.component.button_delete', array('id' => $row->id, 'delete' => route('poz::master.supplier.destroy', ['supplier' => $row->id]) . '?outlet=' . $outletId))->render();


                return $template;
            })
            ->rawColumns(['image', 'action'])->make(true);
    }
}
