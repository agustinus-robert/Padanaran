<?php

namespace Modules\Docs\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Modules\Docs\Enums\DocumentTypeEnum;
use Modules\Docs\Http\Controllers\Controller;
use Modules\Docs\Models\Document;

class HomeController extends Controller
{
    /**
     * Verify document
     */
    public function index(Request $request)
    {
        $employee = Auth::user()->employee;

        $param = array_filter([
            DocumentTypeEnum::GUIDE,
            DocumentTypeEnum::NOTE,
            DocumentTypeEnum::COMPANY,
            $employee->position->position->level->value <= 4 ? DocumentTypeEnum::SPECIAL : '',
        ]);

        return view('docs::home.index', [
            'types' => collect($param),
            'document_count' => Document::where('type', '!=', DocumentTypeEnum::SYSTEM)->count(),
            'documents' => Document::orderByDesc('id')->whereIn('type', $param)->when($request->get('search'), fn ($lb) => $lb->where('label', 'like', '%' . $request->get('search') . '%'))->when($request->get('trashed'), fn ($q) => $q->onlyTrashed())->paginate($request->get('limit', 10)),
        ]);
    }

    /**
     * preview document
     */
    public function show(Document $document)
    {
        return view('docs::home.show', [
            'doc' => $document
        ]);
    }
}
