<?php

namespace Modules\Admin\Http\Controllers\System\User;

use Modules\Account\Models\User;
use Modules\Admin\Http\Controllers\Controller;
use Modules\Admin\Http\Requests\System\User\Phone\UpdateRequest;
use Modules\Account\Repositories\User\PhoneRepository;

class PhoneController extends Controller
{
    use PhoneRepository;

    /**
     * Update the specified resource in storage.
     */
    public function update(User $user, UpdateRequest $request)
    {
        if ($user = $this->updatePhone($user, $request->transformed()->toArray())) {
            return redirect()->back()->with('success', 'Nomor ponsel pengguna <strong>' . $user->display_name . '</strong> telah berhasil diperbarui.');
        }
        return redirect()->fail();
    }
}
