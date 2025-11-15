<?php

namespace Modules\Admin\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\Controller;
use Modules\Admin\Models\ToolLendItem;

class LendToolItemController extends Controller
{
	 public function index(Request $request)
    {
        $this->authorize('access', MutationTool::class);
        
        return view('admin::inventories.tool.lend_item.index', [
            'lend_tool_count' => ToolLendItem::count()
        ]);
    }
}