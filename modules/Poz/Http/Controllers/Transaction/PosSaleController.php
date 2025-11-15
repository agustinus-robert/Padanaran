<?php

namespace Modules\Poz\Http\Controllers\Transaction;

use Modules\Reference\Http\Controllers\Controller;
use Yajra\DataTables\DataTables as Table;
use Modules\Poz\Models\Product;
use Modules\Poz\Models\Brand;
use Modules\Poz\Models\Category;
use Illuminate\Http\Request;

class PosSaleController extends Controller
{
    /**
     * Show the dashboard page.
     */
    public function index()
    {
        $data = [];
        return view('poz::transaction.pos_sale', $data);
    }

    public function create(Request $request)
    {

        return view('poz::transaction.pos_sale', [
            'action' => 'Create'
        ]);
    }

    public function edit(Request $request)
    {

        return view('poz::transaction.pos_sale', [
            'action' => 'Update'
        ]);
    }
}
