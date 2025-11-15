<?php

namespace Modules\Admin\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\Controller;
use Modules\Admin\Models\ToolSell;

class ToolSellController extends Controller
{
	 public function index(Request $request)
    {
        $this->authorize('access', ToolSell::class);
        
        return view('admin::inventories.tool.tool_sell.index', [
            'tool_sell_count' => ToolSell::count()
        ]);
    }
}