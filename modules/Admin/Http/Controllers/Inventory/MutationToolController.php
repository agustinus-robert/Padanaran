<?php

namespace Modules\Admin\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\Controller;
use Modules\Admin\Models\MutationTool;

class MutationToolController extends Controller
{
	 public function index(Request $request)
    {
        $this->authorize('access', MutationTool::class);
        
        return view('admin::inventories.tool.mutation.index', [
            'mutation_tool_count' => MutationTool::count()
        ]);
    }
}