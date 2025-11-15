<?php

namespace Modules\Editor\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Editor\Models\EditorPage;
use Modules\Editor\Models\EditorStory;

class EditorController extends Controller
{
    public function store(Request $request)
    {

        $html = $request->input('html');
        $rawFilename = $request->input('filename');
        $filename = str_replace(' ', '', $rawFilename);

        $path = public_path('story/' . $filename);

        if (!is_dir(public_path('story'))) {
            mkdir(public_path('story'), 0755, true);
        }

        $file = fopen($path, 'w');
        fwrite($file, $html);
        fclose($file);

        EditorStory::create([
            'name_file' => $filename,
            'location' => 'story'
        ]);

        return response()->json([
            'message' => 'File berhasil dibuat!',
            'filename' => strtolower($filename),
            'url' => asset("story/$filename")
        ]);
    }
}
