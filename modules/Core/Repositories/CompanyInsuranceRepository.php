<?php

namespace Modules\Core\Repositories;

use Arr;
use Auth;
use Illuminate\Http\Request;
use Modules\Core\Models\CompanyInsurance;

trait CompanyInsuranceRepository
{
    /**
     * Store newly created resource.
     */
    public function storeCompanyInsurance(array $data)
    {
        $insurance = new CompanyInsurance(array_merge(
            Arr::only($data, ['kd', 'name', 'meta']),
            ['grade_id' => userGrades()]
        ));

        if ($insurance->save()) {
            Auth::user()->log('membuat asuransi baru ' . $insurance->name . ' <strong>[ID: ' . $insurance->id . ']</strong>', CompanyInsurance::class, $insurance->id);
            return $insurance;
        }
        return false;
    }

    /**
     * Update the current resource.
     */
    public function updateCompanyInsurance(CompanyInsurance $insurance, array $data)
    {
        $insurance = $insurance->fill(array_merge(
           Arr::only($data, ['kd', 'name', 'meta']),
            ['grade_id' => userGrades()]
        ));
        
        if ($insurance->save()) {
            Auth::user()->log('memperbarui asuransi ' . $insurance->name . ' <strong>[ID: ' . $insurance->id . ']</strong>', CompanyInsurance::class, $insurance->id);
            return $insurance;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyCompanyInsurance(CompanyInsurance $insurance)
    {
        if (!$insurance->trashed() && $insurance->delete()) {
            Auth::user()->log('menghapus asuransi ' . $insurance->name . ' <strong>[ID: ' . $insurance->id . ']</strong>', CompanyInsurance::class, $insurance->id);
            return $insurance;
        }
        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreCompanyInsurance(CompanyInsurance $insurance)
    {
        if ($insurance->trashed() && $insurance->restore()) {
            Auth::user()->log('memulihkan asuransi ' . $insurance->name . ' <strong>[ID: ' . $insurance->id . ']</strong>', CompanyInsurance::class, $insurance->id);
            return $insurance;
        }
        return false;
    }
}
