<?php

namespace Modules\Administration\Http\Controllers\Database\Manage\User;

use Illuminate\Http\Request;
use Modules\Administration\Http\Controllers\Controller;

use Modules\Account\Models\User;
use Modules\Administration\Http\Requests\Database\Manage\User\Profile\UpdateRequest;

class ProfileController extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, User $user)
    {
        $data = $request->validated();

        $data['dob'] = date('Y-m-d', strtotime($data['dob']));

        if ($user->profile()->update($data)) {

            return redirect()->back()->with('success', 'Profil pengguna <strong>'.$user->profile->name.' ('.$user->username.')</strong> berhasil diperbarui');

        }
    }
}
