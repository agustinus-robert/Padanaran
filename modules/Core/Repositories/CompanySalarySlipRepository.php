<?php

namespace Modules\Core\Repositories;

use Arr;
use Auth;
use Illuminate\Http\Request;
use Modules\Core\Models\CompanySalarySlip;

trait CompanySalarySlipRepository
{
    public function getCompanySalarySlip($request)
    {
        return $slips = CompanySalarySlip::whenTrashed($request->get('trash'))
            ->where('grade_id', userGrades())
            ->search($request->get('search'))
            ->orderBy('az')
            ->paginate($request->get('limit', 10));
    }
    /**
     * Store newly created resource.
     */
    public function storeCompanySalarySlip(array $data)
    {
        $slip = new CompanySalarySlip(array_merge(Arr::only($data, ['az', 'name']), ['grade_id' => userGrades()]));
        if ($slip->save()) {
            Auth::user()->log('membuat slip gaji baru ' . $slip->name . ' <strong>[ID: ' . $slip->id . ']</strong>', CompanySalarySlip::class, $slip->id);
            return $slip;
        }
        return false;
    }

    /**
     * Update the current resource.
     */
    public function updateCompanySalarySlip(CompanySalarySlip $slip, array $data)
    {
        $slip = $slip->fill(array_merge(Arr::only($data, ['az', 'name']), ['grade_id' => userGrades()]));
        if ($slip->save()) {
            Auth::user()->log('memperbarui slip gaji ' . $slip->name . ' <strong>[ID: ' . $slip->id . ']</strong>', CompanySalarySlip::class, $slip->id);
            return $slip;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyCompanySalarySlip(CompanySalarySlip $slip)
    {
        if (!$slip->trashed() && $slip->delete()) {
            Auth::user()->log('menghapus slip gaji ' . $slip->name . ' <strong>[ID: ' . $slip->id . ']</strong>', CompanySalarySlip::class, $slip->id);
            return $slip;
        }
        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreCompanySalarySlip(CompanySalarySlip $slip)
    {
        if ($slip->trashed() && $slip->restore()) {
            Auth::user()->log('memulihkan slip gaji ' . $slip->name . ' <strong>[ID: ' . $slip->id . ']</strong>', CompanySalarySlip::class, $slip->id);
            return $slip;
        }
        return false;
    }
}
