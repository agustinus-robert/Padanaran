<?php

namespace Modules\Poz\Http\Controllers\API;

use Modules\Reference\Http\Controllers\Controller;
use Yajra\DataTables\DataTables as Table;
use Modules\Poz\Models\Product;
use Modules\Poz\Models\Brand;
use Modules\Poz\Models\Category;
use Illuminate\Http\Request;

class BrandApiController extends Controller
{
    /**
     * Show the dashboard page.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $brand = Brand::paginate($perPage);
        return response()->json($brand);
    }
}
