<?php

namespace Modules\Core\Http\Controllers\System\User;

use Modules\Account\Models\User;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\System\User\Role\UpdateRequest;

class RoleController extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function update(User $user, UpdateRequest $request)
    {
        $role = $user->roles()->sync($request->transformed()->toArray());

        if ($role) {
            return redirect()->back()->with('success', 'Peran pengguna <strong>' . $user->display_name . '</strong> telah berhasil diperbarui.');
        }
        return redirect()->fail();
    }
}
