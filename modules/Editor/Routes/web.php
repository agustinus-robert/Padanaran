<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Modules\Editor\Models\EditorWebPages;


Route::middleware('auth')->group(function () {

    // Route::prefix('editor')->namespace('Editor')->name('editor.')->group(function () {
    Route::resource('editorManage', 'EditorController')->parameters(['editorzs' => 'editorz']);
    Route::resource('pages', 'PageController')->parameters(['pages' => 'page']);

    Route::get('page/content', function(Request $request){
        $liveEdit = $request->query('live-edit');
        $lang = $request->query('lang');

        return EditorWebPages::where(['page' => $liveEdit, 'lang' => $lang])->first()->web_json;
    });
    // });

    Route::get('/components/content', function () {
        return view('editor::components.content');
    })->name('content');

    Route::get('/preview', function () {
        return view('editor::preview');
    });

    Route::get('/editor', function () {
        return view('editor::editor');
    })->name('editor.editor');

    Route::get('/block', function () {
        return view('editor::block');
    })->name('editor.block');
});
