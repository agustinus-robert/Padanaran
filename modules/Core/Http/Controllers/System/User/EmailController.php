<?php

namespace Modules\Core\Http\Controllers\System\User;

use Modules\Account\Models\User;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\System\User\Email\UpdateRequest;
use Modules\Account\Repositories\User\EmailRepository;

class EmailController extends Controller
{
    use EmailRepository;

    /**
     * Update the specified resource in storage.
     */
    public function update(User $user, UpdateRequest $request)
    {
        if($user = $this->updateEmail($user, $request->transformed()->toArray())) {
            return redirect()->back()->with('success', 'Alamat surel pengguna <strong>'.$user->display_name.'</strong> telah berhasil diperbarui.');
        }
        return redirect()->fail();
    }
}
