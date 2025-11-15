<?php

namespace Modules\HRMS\Repositories;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use Modules\HRMS\Models\EmployeeContract;
use Modules\HRMS\Models\EmployeePosition;

trait EmployeePositionRepository
{
    /**
     * Store newly created resource.
     */
    public function storeEmployeePosition(EmployeeContract $contract, array $data)
    {
        $position = new EmployeePosition(array_merge(
            Arr::only($data, ['position_id', 'start_at', 'end_at']),
            [
                'empl_id' => $contract->empl_id
            ]
        ));

        if ($contract->positions()->save($position)) {
            Auth::user()->log('menambahkan jabatan ' . $position->name . ' ke perjanjian kerja dengan nomor ' . $contract->kd . ' <strong>[ID: ' . $position->id . ']</strong>', EmployeePosition::class, $position->id);
            return $position;
        }
        return false;
    }

    /**
     * Update newly created resource.
     */
    public function updateEmployeePosition(EmployeePosition $position, array $data)
    {
        $position = $position->fill(Arr::only($data, ['position_id', 'start_at', 'end_at']));

        if ($position->save()) {
            Auth::user()->log('memperbarui jabatan ' . $position->name . ' ke perjanjian kerja dengan nomor ' . $position->contract->kd . ' <strong>[ID: ' . $position->id . ']</strong>', EmployeePosition::class, $position->id);
            return $position;
        }
        return false;
    }

    /**
     * Update newly created resource.
     */
    public function removeEmployeePosition(EmployeePosition $position)
    {
        if ($position->delete()) {
            Auth::user()->log('menghapus jabatan ' . $position->name . ' ke perjanjian kerja dengan nomor ' . $position->contract->kd . ' <strong>[ID: ' . $position->id . ']</strong>', EmployeePosition::class, $position->id);
            return $position;
        }
        return false;
    }
}
