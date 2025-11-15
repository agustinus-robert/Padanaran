<?php

namespace Modules\Admin\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\Controller;
use Modules\Admin\Models\Tool;

class ToolController extends Controller
{
	 public function index(Request $request)
    {
        $this->authorize('access', Land::class);
        
        return view('admin::inventories.tool.index', [
            'tool_count' => Tool::count()
        ]);
    }
}