<?php

namespace Modules\Poz\Http\Controllers\Master;

use Modules\Reference\Http\Controllers\Controller;
use Yajra\DataTables\DataTables as Table;
use Modules\Poz\Models\Unit;
use Modules\Poz\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    /**
     * Show the dashboard page.
     */
    public function index()
    {
        $data = [];
        // dbuilder_table untuk membuat generate table pada kolom header dan pemanggilan kolom database
        $data['column'] = [
            dbuilder_table('name', 'Nama', false, true),
            dbuilder_table('location', 'Lokasi', false, true),
            dbuilder_table('action', 'Aksi')
        ];

        return view('poz::master.warehouse.index', $data);
    }

    public function create(Request $request)
    {

        return view('poz::master.warehouse.index', [
            'action' => 'Create'
        ]);
    }

    public function edit(Request $request)
    {

        return view('poz::master.warehouse.index', [
            'action' => 'Update'
        ]);
    }

    public function destroy(Request $request)
    {
        $warehouse = Warehouse::findOrFail($request->tax); // Mencari pozt berdasarkan ID
        $warehouse->delete(); // Melakukan soft delete

        return redirect(route('poz::master.warehouse.index'))->with('msg-sukses', "Data berhasil dihapus");
    }

    public function unitTable()
    {
        $tax = Warehouse::select('*')->whereNull('deleted_at');

        return Table::of($tax)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $template = '';

                $template .= view('poz::layouts_master.component.button_edit', array('id' => $row->id, 'update' => route('poz::master.warehouse.edit', ['warehouse' => $row->id])))->render();
                $template .= view('poz::layouts_master.component.button_delete', array('id' => $row->id, 'delete' => route('poz::master.warehouse.destroy', ['warehouse' => $row->id])))->render();


                return $template;
            })
            ->rawColumns(['action'])->make(true);
    }
}
