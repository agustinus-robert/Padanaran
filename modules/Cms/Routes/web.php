<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

Route::middleware('auth')->group(function () {
    Route::get('/builder-api/{slug}', function ($slug) {
        $response = Http::get(base_path('modules/Cms/Pb/wp-json/wp/v2/pages'), [
            'slug' => $slug,
            '_fields' => 'id'
        ]);
        return $response->json();
    });
});
