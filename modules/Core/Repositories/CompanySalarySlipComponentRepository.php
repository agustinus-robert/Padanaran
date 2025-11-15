<?php

namespace Modules\Core\Repositories;

use Arr;
use Auth;
use Illuminate\Http\Request;
use Modules\Core\Models\CompanySalarySlipComponent;

trait CompanySalarySlipComponentRepository
{
    public function getCompanySalarySlipComponent($request)
    {
        return $salaries = CompanySalarySlipComponent::whenTrashed($request->get('trash'))
            ->where('grade_id', userGrades())
            ->with('category', 'slip')
            ->search($request->get('search'))
            ->orderBy('id')
            ->paginate($request->get('limit', 10));
    }
    /**
     * Store newly created resource.
     */
    public function storeCompanySalarySlipComponent(array $data)
    {
        $component = new CompanySalarySlipComponent(array_merge(
            Arr::only($data, ['kd', 'slip_id', 'ctg_id', 'name', 'unit', 'operate']), 
        ['grade_id' => userGrades()]));

        if ($component->save()) {
            Auth::user()->log('membuat komponen gaji baru ' . $component->name . ' <strong>[ID: ' . $component->id . ']</strong>', CompanySalarySlipComponent::class, $component->id);
            return $component;
        }
        return false;
    }

    /**
     * Update the current resource.
     */
    public function updateCompanySalarySlipComponent(CompanySalarySlipComponent $component, array $data)
    {
        $component = $component->fill(array_merge(
            Arr::only($data, ['kd', 'slip_id', 'ctg_id', 'name', 'unit', 'operate']), 
        ['grade_id' => userGrades()]));
        
        if ($component->save()) {
            Auth::user()->log('memperbarui komponen gaji ' . $component->name . ' <strong>[ID: ' . $component->id . ']</strong>', CompanySalarySlipComponent::class, $component->id);
            return $component;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyCompanySalarySlipComponent(CompanySalarySlipComponent $component)
    {
        if (!$component->trashed() && $component->delete()) {
            Auth::user()->log('menghapus komponen gaji ' . $component->name . ' <strong>[ID: ' . $component->id . ']</strong>', CompanySalarySlipComponent::class, $component->id);
            return $component;
        }
        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreCompanySalarySlipComponent(CompanySalarySlipComponent $component)
    {
        if ($component->trashed() && $component->restore()) {
            Auth::user()->log('memulihkan komponen gaji ' . $component->name . ' <strong>[ID: ' . $component->id . ']</strong>', CompanySalary::class, $component->id);
            return $component;
        }
        return false;
    }
}
