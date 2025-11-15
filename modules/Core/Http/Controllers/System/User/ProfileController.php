<?php

namespace Modules\Core\Http\Controllers\System\User;

use Modules\Account\Models\User;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\System\User\Profile\UpdateRequest;
use Modules\Account\Repositories\User\ProfileRepository;

class ProfileController extends Controller
{
    use ProfileRepository;

    /**
     * Update the specified resource in storage.
     */
    public function update(User $user, UpdateRequest $request)
    {
        if($user = $this->updateProfile($user, $request->transformed()->toArray())) {
            return redirect()->back()->with('success', 'Informasi profil <strong>'.$user->display_name.'</strong> telah berhasil diperbarui.');
        }
        return redirect()->fail();
    }
}
