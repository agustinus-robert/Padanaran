<?php

namespace Modules\Poz\Http\Controllers\API;

use Modules\Reference\Http\Controllers\Controller;
use Yajra\DataTables\DataTables as Table;
use Modules\Poz\Models\Product;
use Modules\Poz\Models\Brand;
use Modules\Poz\Models\Category;
use Illuminate\Http\Request;
use Modules\Account\Models\UserToken;

class CategoryApiController extends Controller
{
    /**
     * Show the dashboard page.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $user = $request->header('X-API-KEY');
        $userToken = UserToken::where('token', $user)->first();

        $categories = Category::where('created_by', $userToken->user_id)->paginate($perPage);
        return response()->json($categories);
    }
}
