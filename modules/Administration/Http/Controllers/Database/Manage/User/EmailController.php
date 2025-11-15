<?php

namespace Modules\Administration\Http\Controllers\Database\Manage\User;

use Illuminate\Http\Request;
use Modules\Administration\Http\Controllers\Controller;

use Modules\Account\Models\User;
use Modules\Administration\Http\Requests\Database\Manage\User\Email\UpdateRequest;

class EmailController extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, User $user)
    {
        $data = [
            'address'        => $request->input('email'),
            'verified_at'    => null
        ];

        if($data['address'] != $user->email->address) {
            $user->email()->updateOrCreate(['user_id' => $user->id], $data);
        }

        return redirect()->back()->with('success', 'Alamat e-mail pengguna <strong>'.$user->profile->name.' ('.$user->username.')</strong> berhasil diperbarui');
    }
}
