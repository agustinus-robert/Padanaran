<?php

namespace Modules\Poz\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Modules\Poz\Models\CashTopUp;
use Modules\Poz\Models\CashRegister;
use Modules\Account\Models\UserToken;

trait CashRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'casier_id',
        'money'
    ];

    /**
     * Store newly created resource.
     */
    public function storeTopUp(array $data, $user)
    {
        $userToken = UserToken::where('token', $user)->first();

        $data['casier_id'] = $userToken->user_id;
        $data['money'] = $data['money'];

        $cashTopUp = new CashTopUp(Arr::only($data, $this->keys));
        if ($cashTopUp->save()) {
            $cashRegister = CashRegister::where('casier_id', $userToken->user_id)->first();

            if ($cashRegister) {
                $cashRegister->money += $data['money'];
                $cashRegister->save();

                $cashRegister->logCash()->create([
                    'status' => 'plus', 
                    'money' => $data['money'],
                ]);
            } else {
                $cashRegister = CashRegister::create([
                    'casier_id' => $userToken->user_id,
                    'money' => $data['money'],
                ]);

                $cashRegister->logCash()->create([
                    'status' => 'plus', 
                    'money' => $data['money'],
                ]);
            }

            return response()->json(['status' => 'Uang Cash telah ditambahkan']);
        } else {
            return response()->json(['status' => 'Uang Cash gagal ditambahkan']);
        }
    }

    /**
     * Update the current resource.
     */
    public function updateOutlet(array $data, $id)
    {
        $outlet = Outlet::find($id);
        $location = 'file_outlet/' . uniqid();

        if (isset($data['document']) && $data['document'] instanceof \Illuminate\Http\UploadedFile) {
            $data['document']->storeAs($location, $data['document']->getFilename(), 'public');
            $data['location'] = $location;
            $data['image_name'] = $data['document']->getFilename();
        }

        if ($outlet->update(Arr::only($data, $this->keys))) {
            return true;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyOutlet($id)
    {
        if (Outlet::where('id', $id)->delete()) {
            return true;
        }

        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreOutlet($id)
    {
        if (Outlet::onlyTrashed()->find($id)->restore()) {
            return true;
        }
        return false;
    }
}
