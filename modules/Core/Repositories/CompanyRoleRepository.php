<?php

namespace Modules\Core\Repositories;

use Arr;
use Auth;
use Modules\Core\Models\CompanyRole;

trait CompanyRoleRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'kd', 'name', 'grade_id'
    ];

    /**
     * Store newly created resource.
     */
    public function storeCompanyRole(array $data)
    {
        $role = new CompanyRole(array_merge(Arr::only($data, $this->keys), ['grade_id' => userGrades()]));
        if($role->save()) {
            Auth::user()->log('membuat peran baru '.$role->name.' <strong>[ID: '.$role->id.']</strong>', CompanyRole::class, $role->id);
            return $role;
        }
        return false;
    }

    /**
     * Update the current resource.
     */
    public function updateCompanyRole(CompanyRole $role, array $data)
    {        
        $role = $role->fill(array_merge(Arr::only($data, $this->keys), ['grade_id' => userGrades()]));
        if($role->save()) {
            Auth::user()->log('memperbarui peran '.$role->name.' <strong>[ID: '.$role->id.']</strong>', CompanyRole::class, $role->id);
            return $role;
        }
        return false;
    }

    /**
     * Sync many-to-many relationships the current resource.
     */
    public function syncCompanyRolePermissions(CompanyRole $role, array $data)
    {        
        if($role->permissions()->sync($data['permissions'] ?? [])) {
            Auth::user()->log('memperbarui hak akses peran '.$role->name.' <strong>[ID: '.$role->id.']</strong>', CompanyRole::class, $role->id);
            return $role;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyCompanyRole(CompanyRole $role)
    {
        if(!$role->trashed() && $role->delete()) {
            Auth::user()->log('menghapus peran '.$role->name.' <strong>[ID: '.$role->id.']</strong>', CompanyRole::class, $role->id);
            return $role;
        }

        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreCompanyRole(CompanyRole $role)
    {
        if($role->trashed() && $role->restore()) {
            Auth::user()->log('memulihkan peran '.$role->name.' <strong>[ID: '.$role->id.']</strong>', CompanyRole::class, $role->id);
            return $role;
        }
        return false;
    }
}