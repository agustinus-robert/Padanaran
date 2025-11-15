<?php

namespace Modules\Administration\Http\Controllers\Database\Manage\User;

use Illuminate\Http\Request;
use Modules\Administration\Http\Controllers\Controller;

use Modules\Account\Models\User;
use Modules\Account\Models\Role;
use Modules\Administration\Http\Requests\Database\Manage\User\Role\UpdateRequest;

class RoleController extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, User $user)
    {
        if ($user->roles()->sync($request->input('roles'))) {

            return redirect()->back()->with('success', 'Peran pengguna <strong>'.$user->profile->name.' ('.$user->username.')</strong> berhasil diperbarui');

        }
    }
}
