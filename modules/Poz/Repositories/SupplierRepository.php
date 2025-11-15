<?php

namespace Modules\Poz\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Modules\Poz\Models\Supplier;
use Illuminate\Support\Str;
use Modules\Account\Models\User;
use App\Models\Team;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

trait SupplierRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'code',
        'name',
        'email',
        'phone',
        'address',
        'location',
        'image_name'
    ];

    /**
     * Store newly created resource.
     */
    public function storeSupplier(array $data, $document)
    {
        DB::beginTransaction();

        try {
            $location = 'file_supplier/' . uniqid();

            if (isset($document) && $document instanceof \Illuminate\Http\UploadedFile) {
                $fileName = $document->getClientOriginalName();
                $document->storeAs($location, $fileName, 'public');
                $data['location'] = $location;
                $data['image_name'] = $fileName;
            }

            $supplier = new Supplier(Arr::only($data, $this->keys));

            if ($supplier->save()) {

                $user = new User([
                    'name' => $data['name'],
                    'username' => $data['name'].rand(100, 999),
                    'email' => $data['email'],
                    'password' => 'password',
                    'current_team_id' => 1
                ]);

                if ($user->save()) {
                    $empl = $user->employee()->create([
                        'joined_at' => Carbon::parse(now()),
                    ]);

                    $contract = $empl->contract()->create([
                        'kd' => ($user->id+ 1) . '/AFD-SUPPLIER/'.date('Y'),
                        'contract_id' => 2,
                        'work_location' => 1,
                        'start_at' => '2021-01-01 01:00:00',
                        'end_at' => null,
                        'created_by' => User::first()->id,
                        'updated_by' => User::first()->id
                    ]);

                    $contract->position()->create([
                        'empl_id' => $contract->empl_id,
                        'position_id' => 9,
                        'start_at' => $contract->start_at,
                        'end_at' => $contract->end_at,
                    ]);
                }

                $outletId = $data['outlet'];

                if ($outletId) {
                    $supplier->outlets()->attach($outletId);
                }
            }

            DB::commit();
            return true;
        } catch (\Throwable $e) {
            DB::rollBack();
            dd($e);
            return false;
        }
    }

    /**
     * Update the current resource.
     */
    public function updateSupplier(array $data, $id, $document)
    {
        $supplier = Supplier::find($id);
        $location = 'file_supplier/' . uniqid();

        if (isset($document) && $document instanceof \Illuminate\Http\UploadedFile) {
            $fileName = $document->getClientOriginalName();
            $document->storeAs($location, $fileName, 'public');
            $data['location'] = $location;
            $data['image_name'] = $fileName;
        }

        if ($supplier->update(Arr::only($data, $this->keys))) {
            $outletId = $data['outlet'];

            if ($outletId) {
                $supplier->outlets()->syncWithoutDetaching($outletId);
            }

            return true;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroySupplier($id)
    {
        if (Supplier::where('id', $id)->delete()) {
            return true;
        }

        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreSupplier($id)
    {
        if (Supplier::onlyTrashed()->find($id)->restore()) {
            return true;
        }
        return false;
    }
}
