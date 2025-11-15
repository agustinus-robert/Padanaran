<?php

namespace Modules\Poz\Http\Controllers\Master;

use Modules\Reference\Http\Controllers\Controller;
use Yajra\DataTables\DataTables as Table;
use Modules\Poz\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Show the dashboard page.
     */
    public function index()
    {
        $data = [];
        // dbuilder_table untuk membuat generate table pada kolom header dan pemanggilan kolom database
        $data['column'] = [
            dbuilder_table('image', 'Gambar', false, false, 'w10'),
            dbuilder_table('code', 'Code'),
            dbuilder_table('name', 'Nama', false, true),
            dbuilder_table('parent', 'Induk Kategori'),
            dbuilder_table('action', 'Aksi')
        ];

        return view('poz::master.category.index', $data);
    }

    public function create(Request $request)
    {

        return view('poz::master.category.index', [
            'action' => 'Create'
        ]);
    }

    public function edit(Request $request)
    {

        return view('poz::master.category.index', [
            'action' => 'Update'
        ]);
    }

    public function destroy(Request $request)
    {
        $category = Category::findOrFail($request->category); // Mencari pozt berdasarkan ID
        $category->delete(); // Melakukan soft delete

        return redirect(route('poz::master.category.index'))->with('msg-sukses', "Data berhasil dihapus");
    }

    public function categoryTable(Request $request)
    {
        $outletId = $request->outlet;
        $category = Category::with('user', 'outlets')
            ->whereNull('deleted_at')
            ->whereHas('outlets', function ($query) use ($outletId) {
                $query->where('outlet_id', $outletId);
            });

        if (!empty($search = $request->search)) {
            $category->where(function ($query) use ($search) {
                $query->where('name', 'ILIKE', "%{$search}%");
            });
        }

        if (!empty($order = $request->filter)) {
            if ($order === 'new') {
                $category->orderBy('id', 'desc');
            } elseif ($order === 'old') {
                $category->orderBy('id', 'asc');
            }
        }

        return Table::of($category)
            ->addIndexColumn()
            ->addColumn('image', function ($row) {
                if (!empty($row->location) && !empty($row->image_name)) {
                    $image = $row->location . '/' . $row->image_name;

                    return "<img width='50' height='50' src='" . asset('uploads/' . $image) . "' />";
                } else {
                    return "<img src='https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg?20200913095930' width='50' height='50' />";
                }
            })
            ->addColumn('parent', function ($row) {
                return (isset(Category::find($row->parent_id)->name) && !empty(Category::find($row->parent_id)->name) ? Category::find($row->parent_id)->name : 'Tidak ada parent');
            })
            ->addColumn('action', function ($row) use ($request) {
                $template = '';
                $outletId = $request->outlet;

                $template .= view('poz::layouts_master.component.button_edit', array('id' => $row->id, 'update' => route('poz::master.category.edit', ['category' => $row->id]) . '?outlet=' . $outletId))->render();
                $template .= view('poz::layouts_master.component.button_delete', array('id' => $row->id, 'delete' => route('poz::master.category.destroy', ['category' => $row->id]) . '?outlet=' . $outletId))->render();


                return $template;
            })
            ->rawColumns(['image', 'action'])->make(true);
    }
}
