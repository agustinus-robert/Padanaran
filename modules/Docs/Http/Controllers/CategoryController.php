<?php

namespace Modules\Docs\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Docs\Http\Controllers\Controller;
use Modules\Docs\Models\Document;

class CategoryController extends Controller
{
    /**
     * Verify document
     */
    public function index(Request $request)
    {
        return redirect()->fail();
    }

    public function show(Request $request, $type)
    {
        return view('docs::categories.show', [
            'documents' => Document::whereType($type)
                ->when($request->get('search'), fn ($lb) => $lb->where('label', 'like', '%' . $request->get('search') . '%'))
                ->when($request->get('trashed'), fn ($tr) => $lb->onlyTrashed())
                ->paginate($request->get('limit', 10)),
            'document_count' => Document::whereType($type)->count()
        ]);
    }
}
