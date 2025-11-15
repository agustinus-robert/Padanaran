<?php

namespace Modules\Core\Repositories;

use Arr;
use Auth;
use Modules\Core\Models\CompanyDepartment;

trait CompanyDepartmentRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'kd', 'name', 'description', 'parent_id', 'is_visible', 'grade_id'
    ];

    /**
     * Store newly created resource.
     */
    public function storeCompanyDepartment(array $data)
    {
        $department = new CompanyDepartment(array_merge(Arr::only($data, $this->keys), ['grade_id' => userGrades()]));
        if($department->save()) {
            Auth::user()->log('membuat departemen baru '.$department->name.' <strong>[ID: '.$department->id.']</strong>', CompanyDepartment::class, $department->id);
            return $department;
        }
        return false;
    }

    /**
     * Update the current resource.
     */
    public function updateCompanyDepartment(CompanyDepartment $department, array $data)
    {        
        $department = $department->fill(array_merge(Arr::only($data, $this->keys), ['grade_id' => userGrades()]));
            if($department->save()) {
                Auth::user()->log('memperbarui departemen '.$department->name.' <strong>[ID: '.$department->id.']</strong>', CompanyDepartment::class, $department->id);
                return $department;
            }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyCompanyDepartment(CompanyDepartment $department)
    {
        if(!$department->trashed() && $department->delete()) {
            Auth::user()->log('menghapus departemen '.$department->name.' <strong>[ID: '.$department->id.']</strong>', CompanyDepartment::class, $department->id);
            return $department;
        }
        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreCompanyDepartment(CompanyDepartment $department)
    {
        if($department->trashed() && $department->restore()) {
            Auth::user()->log('memulihkan departemen '.$department->name.' <strong>[ID: '.$department->id.']</strong>', CompanyDepartment::class, $department->id);
            return $department;
        }
        return false;
    }
}