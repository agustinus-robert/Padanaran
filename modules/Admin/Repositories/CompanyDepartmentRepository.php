<?php

namespace Modules\Admin\Repositories;

use App\Models\Departement;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

trait CompanyDepartmentRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'kd', 'name', 'description', 'parent_id', 'is_visible'
    ];

    /**
     * Store newly created resource.
     */
    public function storeCompanyDepartment(array $data)
    {
        $department = new Departement(Arr::only($data, $this->keys));
        if ($department->save()) {
            Auth::user()->log('membuat departemen baru ' . $department->name . ' <strong>[ID: ' . $department->id . ']</strong>', Departement::class, $department->id);
            return $department;
        }
        return false;
    }

    /**
     * Update the current resource.
     */
    public function updateCompanyDepartment(Departement $department, array $data)
    {
        $department = $department->fill(Arr::only($data, $this->keys));
        if ($department->save()) {
            Auth::user()->log('memperbarui departemen ' . $department->name . ' <strong>[ID: ' . $department->id . ']</strong>', Departement::class, $department->id);
            return $department;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyCompanyDepartment(Departement $department)
    {
        if (!$department->trashed() && $department->delete()) {
            Auth::user()->log('menghapus departemen ' . $department->name . ' <strong>[ID: ' . $department->id . ']</strong>', Departement::class, $department->id);
            return $department;
        }
        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreCompanyDepartment(Departement $department)
    {
        if ($department->trashed() && $department->restore()) {
            Auth::user()->log('memulihkan departemen ' . $department->name . ' <strong>[ID: ' . $department->id . ']</strong>', Departement::class, $department->id);
            return $department;
        }
        return false;
    }
}
