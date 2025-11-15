<?php

namespace Modules\Poz\Http\Controllers\Master;

use Modules\Reference\Http\Controllers\Controller;
use Yajra\DataTables\DataTables as Table;
use Modules\Account\Models\User;
use Modules\Poz\Models\Outlet;
use Illuminate\Http\Request;

class CasierController extends Controller
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
            dbuilder_table('username', 'Username'),
            dbuilder_table('name', 'Nama', false, true),
            dbuilder_table('outlet', 'Outlet', false, true),
            dbuilder_table('action', 'Aksi')
        ];

        return view('poz::master.casier.index', $data);
    }

    public function create(Request $request)
    {

        return view('poz::master.casier.index', [
            'action' => 'Create'
        ]);
    }

    public function edit(Request $request)
    {

        return view('poz::master.casier.index', [
            'action' => 'Update'
        ]);
    }

    public function destroy(Request $request)
    {
        $outlet = Outlet::findOrFail($request->brand); // Mencari pozt berdasarkan ID
        $outlet->delete(); // Melakukan soft delete

        return redirect(route('poz::master.casier.index'))->with('msg-sukses', "Data berhasil dihapus");
    }

    public function casierTable()
    {
        $user = User::select('*')->whereNull('deleted_at');

        return Table::of($user)
            ->addIndexColumn()
            ->addColumn('image', function ($row) {
                if (!empty($row->location) && !empty($row->image_name)) {
                    $image = $row->location . '/' . $row->image_name;

                    return "<img width='50' height='50' src='" . asset('uploads/' . $image) . "' />";
                } else {
                    return "<img src='https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg?20200913095930' width='50' height='50' />";
                }
            })
            ->addColumn('outlet', function ($row) {
                return '';
            })
            ->addColumn('action', function ($row) {
                $template = '';

                $template .= view('poz::layouts_master.component.button_edit', array('id' => $row->id, 'update' => route('poz::master.casier.edit', ['casier' => $row->id])))->render();
                $template .= view('poz::layouts_master.component.button_delete', array('id' => $row->id, 'delete' => route('poz::master.casier.destroy', ['casier' => $row->id])))->render();


                return $template;
            })
            ->rawColumns(['image', 'action'])->make(true);
    }
}
