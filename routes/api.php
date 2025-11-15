<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Account\Models\User;
use App\Models\References\ProvinceRegencyDistrict;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/getDistricts', function (Request $request) {
    return ProvinceRegencyDistrict::with('regency.province')->where('name', 'like', '%' . $request->get('q', '') . '%')
        ->limit(6)
        ->get()
        ->map(function ($v) {
            return [
                'id' => $v->id,
                'text' => $v->regional
            ];
        })
        ->toJson();
})->name('api.getDistricts');

Route::get('/getUsers', function (Request $request) {
    $q = $request->get('q', '');

    $users = User::where('username', 'ILIKE', "%{$q}%")
        ->orWhereHas('profile', function ($query) use ($q) {
            $query->where('name', 'ILIKE', "%{$q}%");
        })
        ->limit(6)
        ->get()
        ->map(function ($v) {
            return [
                'id' => $v->id,
                'text' => $v->profile->name . ' (' . $v->username . ')'
            ];
        });

    return response()->json($users);
})->name('api.getUsers');
