<?php

namespace Modules\Auth\Http\Controllers\OAuth;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Modules\Account\Models\User;
use Modules\Auth\Events\SignedIn;
use Modules\Auth\Http\Controllers\Controller;

class EmpowerController extends Controller
{
    /**
     * Redirect to oauth server url.
     */
    public function redirect(Request $request)
    {
        $request->session()->put('state', $state = Str::random(40));

        $query = http_build_query([
            'client_id' => env('EPA_OAUTH_ID'),
            'redirect_uri' => route('auth::empower.callback'),
            'response_type' => 'code',
            'scope' => '',
            'state' => $state,
        ]);

        // return $query;

        return redirect(env('EPA_OAUTH_URL') . '/oauth/authorize?' . $query);
    }

    /**
     * Accept authorization code.
     */
    public function callback(Request $request)
    {
        $state = $request->session()->pull('state');

        if (strlen($state) > 0 && $state === $request->state) {
            $response = Http::asForm()->post(env('EPA_OAUTH_URL') . '/oauth/token', [
                'grant_type' => 'authorization_code',
                'client_id' => env('EPA_OAUTH_ID'),
                'client_secret' => env('EPA_OAUTH_SECRET'),
                'redirect_uri' => route('auth::empower.callback'),
                'code' => $request->code,
            ]);


            if ($token = $response->object()?->access_token ?? false) {
                $response = Http::withToken($token)->get(env('EPA_OAUTH_URL') . '/api/account/user');
                if ($empower_user = $response->object()?->user) {
                    $request->session()->put('empower_user', $empower_user);
                    $user = User::firstOrCreate([
                        'email_address' => $empower_user->email_address,
                        'pemad_id' => $empower_user->id
                    ], [
                        'name' => $empower_user->name,
                        'email_verified_at' => $empower_user->email_verified_at
                    ]);

                    event(new SignedIn(['access_token' => $user->createToken('ViaEmpowerApplication')->accessToken], true));

                    return redirect()->intended($request->get('next', route('account::home')))->with('success', 'Hi <strong>:name</strong>! Welcome to ' . config('app.name') . '!');
                }
                return redirect()->route('auth::signin')->with('danger', 'User not found!');
            }
            return redirect()->route('auth::signin')->with('danger', 'Sorry, We can\'t accept this request at this moment, please contact IT right now!');
        }
        return redirect()->route('auth::signin')->with('danger', 'Sorry your request is not available right now, please try again!');
    }
}
