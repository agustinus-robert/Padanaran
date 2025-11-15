<?php

namespace Modules\Editor\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Editor\Models\EditorWebPages;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->query('live-edit');
        return view('editor::page', compact('page'));
    }

    public function store(Request $request){
        $data = $request->all();

        $page = EditorWebPages::where(['page' => $request->page, 'lang' => $request->language])->first();

        if ($page) {
            unset($data['page']);
            unset($data['language']);
            $page->update([
                'web_json' => $data
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Page updated successfully',
                'page' => $page,
            ]);
        } else {
            unset($data['page']);
            $page = EditorWebPages::create(['web_json' => $data]);

            return response()->json([
                'status' => false,
                'message' => 'Page created successfully',
                'page' => $page,
            ]);
        }
    }

    public function show(){

    }
}
