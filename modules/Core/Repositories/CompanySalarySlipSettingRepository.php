<?php

namespace Modules\Core\Repositories;

use Illuminate\Support\Arr;
use Modules\Account\Models\User;
use Modules\Core\Models\CompanyPayrollSetting;

trait CompanySalarySlipSettingRepository
{
    /**
     * Store newly created resource.
     */
    public function storePayrollConfig(array $data, User $user)
    {
        $setting = new CompanyPayrollSetting(
            array_merge(
                Arr::only($data, ['key', 'az', 'meta']),
                ['grade_id' => userGrades()]
            )
        );
        
        if ($setting->save()) {
            $user->log('membuat pengaturan gaji baru <strong>[ID: ' . $setting->id . ']</strong>', CompanyPayrollSetting::class, $setting->id);
            return $setting;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyPayrollConfig(CompanyPayrollSetting $setting, User $user)
    {
        if (!$setting->trashed() && $setting->delete()) {
            $user->log('menghapus kategori gaji ' . $setting->name . ' <strong>[ID: ' . $setting->id . ']</strong>', CompanyPayrollSetting::class, $setting->id);
            return $setting;
        }
        return false;
    }
}
