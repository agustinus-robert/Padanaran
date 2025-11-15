<?php

namespace Modules\Core\Http\Controllers\System\User;

use Modules\Account\Models\User;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\System\User\Username\UpdateRequest;
use Modules\Account\Repositories\User\UsernameRepository;

class UsernameController extends Controller
{
    use UsernameRepository;

    /**
     * Update the specified resource in storage.
     */
    public function update(User $user, UpdateRequest $request)
    {
        if($user = $this->updateUsername($user, $request->transformed()->toArray())) {
            return redirect()->back()->with('success', 'Username pengguna <strong>'.$user->display_name.'</strong> telah berhasil diperbarui.');
        }
        return redirect()->fail();
    }
}
