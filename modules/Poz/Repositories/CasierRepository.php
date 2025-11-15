<?php

namespace Modules\Poz\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Events\SignedUp;
use Modules\Account\Models\User;
use Modules\Account\Models\UserRole;
use Modules\Account\Models\UserToken;
use Modules\Poz\Models\UserOutlet;
use Modules\Poz\Models\Outlet;
use DB;

trait CasierRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'name',
        'username',
        'email_address',
        'password',
        'location',
        'image_name'
    ];

    private $keyCasierOutlets = [
        'user_id',
        'outlet_id'
    ];

    /**
     * Store newly created resource.
     */
    public function storeCasier(array $data)
    {

        DB::beginTransaction();

        try {
            $location = 'file_casier/' . uniqid();

            $data['username'] = $data['username'];
            $data['email_address'] = $data['email_address'];
            $data['password'] = $data['password'];

            if (isset($data['document']) && $data['document'] instanceof \Illuminate\Http\UploadedFile) {
                $data['document']->storeAs($location, $data['document']->getFilename(), 'public');
                $data['location'] = $location;
                $data['image_name'] = $data['document']->getFilename();
            }

            $user = new User(Arr::only($data, $this->keys));

            if ($user->save()) {

                $dataCasierOutlet['user_id'] = $user->id;
                $dataCasierOutlet['outlet_id'] = $data['outlet_id'];
                $casierOutlet = new UserOutlet(Arr::only($dataCasierOutlet, $this->keyCasierOutlets));
                $casierOutlet->save();

                $userRole = new UserRole();
                $userRole->user_id = $user->id;
                $userRole->role_id = 2;

                $userToken = new UserToken();
                $userToken->user_id = $user->id;
                $userToken->token = str()->random(8);


                if ($userRole->save() && $userToken->save()) {
                    event(new SignedUp($user));
                }

                DB::commit();
                return true;
            }
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }

    /**
     * Update the current resource.
     */
    public function updateCasier(array $data, $id)
    {
        $user = User::find($id);
        $location = 'file_casier/' . uniqid();

        $data['username'] = $data['username'];
        $data['email_address'] = $data['email_address'];
        $data['password'] = $data['password'];

        if (isset($data['document']) && $data['document'] instanceof \Illuminate\Http\UploadedFile) {
            $data['document']->storeAs($location, $data['document']->getFilename(), 'public');
            $data['location'] = $location;
            $data['image_name'] = $data['document']->getFilename();
        }

        $dataCasierOutlet['user_id'] = $id;
        $dataCasierOutlet['outlet_id'] = $data['outlet_id'];

        UserOutlet::where('user_id', $id)
            ->update(Arr::only($dataCasierOutlet, $this->keyCasierOutlets));

        if ($user->update(Arr::only($data, $this->keys))) {
            return true;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyCasier($id)
    {
        if (User::where('id', $id)->delete()) {
            return true;
        }

        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreCasier($id)
    {
        if (User::onlyTrashed()->find($id)->restore()) {
            return true;
        }
        return false;
    }
}
