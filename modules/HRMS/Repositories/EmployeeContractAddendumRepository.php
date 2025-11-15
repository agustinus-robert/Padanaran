<?php

namespace Modules\HRMS\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Modules\HRMS\Models\EmployeeContract;
use Modules\HRMS\Models\EmployeePosition;

trait EmployeeContractAddendumRepository
{
    /**
     * Define the primary meta keys for resource
     */
    private $metaKeys = [
        'addendum'
    ];

    /**
     * Update the specified resource in storage.
     */
    public function storeAdendum(EmployeeContract $contract, array $array)
    {
        if ($contract) {
            // get current addendums on databases
            $addendums = $contract->getMeta('addendum', []);

            $data = Arr::only($array, $this->metaKeys)['addendum'];

            // merge existing with new addendum
            array_push($addendums, $data);

            // $contract->setManyMeta(Arr::only($array, $this->metaKeys));
            $contract->setMeta('addendum', array_values($addendums));

            Auth::user()->log('menambahkan adendum pada kontrak nomor ' . $contract->kd . ' <strong>[ID: ' . $contract->id . ']</strong>', EmployeeContract::class, $contract->id);

            return $contract;
        }
        return false;
    }

    /**
     * Update the specified resource in storage.
     */
    public function updatePosition(EmployeeContract $contract, array $array)
    {
        if ($contract && isset($array)) {
            $position = EmployeePosition::where('empl_id', $array['empl_id'])->where('contract_id', $contract->id)->where('position_id', $array['position_id'])->first();
            if ($position) {
                $position->update(['start_at' => $array['start_at'], 'end_at' => $array['end_at']]);
            } else {
                $position                = new EmployeePosition;
                $position->empl_id       = $array['empl_id'];
                $position->position_id   = $array['position_id'];
                $position->contract_id   = $contract->id;
                $position->start_at      = $array['start_at'];
                $position->end_at        = $array['end_at'];

                $position->save();
            }
            return $position;
        }
        return false;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function removeAdendum(EmployeeContract $contract)
    {
        if ($contract) {

            $contract->removeManyMeta($this->metaKeys);

            Auth::user()->log('menghapus adendum dari kontrak ' . $contract->kd . ' <strong>[ID: ' . $contract->id . ']</strong>', EmployeeContract::class, $contract->id);

            return $contract;
        }

        return false;
    }
}
