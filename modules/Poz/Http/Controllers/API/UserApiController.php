<?php

namespace Modules\Poz\Http\Controllers\API;

use Modules\Reference\Http\Controllers\Controller;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Modules\Auth\Events\SignedIn;
use Modules\Auth\Http\Requests\SignIn\StoreRequest;
use Modules\Account\Models\User;
use Modules\Account\Models\UserRole;
use Modules\Account\Models\UserToken;
use Modules\Poz\Models\UserOutlet;
use Modules\Poz\Models\Outlet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserApiController extends Controller
{
    public function store(StoreRequest $request)
    {
        $request->ensureIsNotRateLimited();

        $credentials = $request->only('username', 'password');
        $user = \Modules\Account\Models\User::where('username', $credentials['username'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {

            RateLimiter::hit($request->throttleKey());

            throw ValidationException::withMessages([
                'username' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($request->throttleKey());
        $user = Auth::user();

        event(new SignedIn($user, $request->has('remember')));

        $getUserNameId = User::where('username', $request->transformed()['username'])->first();

        $getRoleId = UserRole::where('user_id', $getUserNameId->id)->first();
        $getTokenId = UserToken::where('user_id', $getUserNameId->id)->first();
        $userOutlet = UserOutlet::where('user_id', $getUserNameId->id)->with('outlet')->get()->unique('outlet_id');
        //$outlet = Outlet::find($userOutlet->outlet_id)->first();

        $outletList = [];
        foreach ($userOutlet as $value) {
            $outletList[] = $value->outlet->name;
        }

        return response()->json([
            'name' => $getUserNameId->name,
            'email' => $getUserNameId->email_address,
            'outlet' => $outletList,
            'token' => $getTokenId->token,
            'location' => $getUserNameId->location,
            'image_name' => $getUserNameId->image_name
        ]);
    }
}
