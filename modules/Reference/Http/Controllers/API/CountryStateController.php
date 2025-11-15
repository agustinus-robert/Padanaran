<?php

namespace Modules\Reference\Http\Controllers\API;

use Illuminate\Http\Request;
use Modules\Reference\Http\Controllers\Controller;
use Modules\Reference\Models\CountryState;

class CountryStateController extends Controller
{
    /**
     * Search a listing of the resources..
     */
    public function search(Request $request)
    {
        return response()->success([
            'message' => 'Berikut adalah data hasil pencarian provinsi dengan query "'.$request->get('q', '').'"',
            'data' => CountryState::with('country')->search($request->get('q', ''))->limit(8)->get()
                            ->map(fn ($v) => [
                                'id' => $v->id,
                                'text' => $v->full
                            ])
        ]);
    }
}
