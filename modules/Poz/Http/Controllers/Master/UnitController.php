<?php

namespace Modules\Poz\Http\Controllers\Master;

use Modules\Reference\Http\Controllers\Controller;
use Yajra\DataTables\DataTables as Table;
use Modules\Poz\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnitController extends Controller
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
            dbuilder_table('action', 'Aksi')
        ];

        return view('poz::master.unit.index', $data);
    }

    public function create(Request $request)
    {

        return view('poz::master.unit.index', [
            'action' => 'Create'
        ]);
    }

    public function edit(Request $request)
    {

        return view('poz::master.unit.index', [
            'action' => 'Update'
        ]);
    }

    public function destroy(Request $request)
    {
        $tax = Unit::findOrFail($request->tax); // Mencari pozt berdasarkan ID
        $tax->delete(); // Melakukan soft delete

        return redirect(route('poz::master.unit.index'))->with('msg-sukses', "Data berhasil dihapus");
    }

    public function unitTable(Request $request)
    {
        $outletId = $request->outlet;
        $unit = Unit::with('user', 'outlets')
            ->whereNull('deleted_at')
            ->whereHas('outlets', function ($query) use ($outletId) {
                $query->where('outlet_id', $outletId);
            });

        if (!empty($search = $request->search)) {
            $unit->where(function ($query) use ($search) {
                $query->where('name', 'ILIKE', "%{$search}%");
            });
        }

        if (!empty($order = $request->filter)) {
            if ($order === 'new') {
                $unit->orderBy('id', 'desc');
            } elseif ($order === 'old') {
                $unit->orderBy('id', 'asc');
            }
        }

        return Table::of($unit)
            ->addIndexColumn()
            ->addColumn('action', function ($row) use ($request) {
                $template = '';
                $outletId = $request->outlet;

                $template .= view('poz::layouts_master.component.button_edit', array('id' => $row->id, 'update' => route('poz::master.unit.edit', ['unit' => $row->id]) . '?outlet=' . $outletId))->render();
                $template .= view('poz::layouts_master.component.button_delete', array('id' => $row->id, 'delete' => route('poz::master.unit.destroy', ['unit' => $row->id]) . '?outlet=' . $outletId))->render();


                return $template;
            })
            ->rawColumns(['action'])->make(true);
    }
}
