<?php

namespace Modules\Administration\Http\Controllers\Database\Manage\User;

use Illuminate\Http\Request;
use Modules\Administration\Http\Controllers\Controller;

use Modules\Account\Models\User;
use Modules\Administration\Http\Requests\Database\Manage\User\Phone\UpdateRequest;

class PhoneController extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, User $user)
    {
        $user->phone()->updateOrCreate([
            'user_id'       => $user->id
        ], [
            'number'        => $request->input('number'),
            'whatsapp'      => (bool) $request->input('whatsapp')
        ]);

        return redirect()->back()->with('success', 'Nomor HP pengguna <strong>'.$user->profile->name.' ('.$user->username.')</strong> berhasil diperbarui');
    }
}
