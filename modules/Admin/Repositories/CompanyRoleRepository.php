<?php

namespace Modules\Admin\Repositories;

use App\Models\Role;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

trait CompanyRoleRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'kd', 'name'
    ];

    /**
     * Store newly created resource.
     */
    public function storeCompanyRole(array $data)
    {
        $role = new Role(Arr::only($data, $this->keys));
        if ($role->save()) {
            Auth::user()->log('membuat peran baru ' . $role->name . ' <strong>[ID: ' . $role->id . ']</strong>', Role::class, $role->id);
            return $role;
        }
        return false;
    }

    /**
     * Update the current resource.
     */
    public function updateCompanyRole(Role $role, array $data)
    {
        $role = $role->fill(Arr::only($data, $this->keys));
        if ($role->save()) {
            Auth::user()->log('memperbarui peran ' . $role->name . ' <strong>[ID: ' . $role->id . ']</strong>', Role::class, $role->id);
            return $role;
        }
        return false;
    }

    /**
     * Sync many-to-many relationships the current resource.
     */
    public function syncCompanyRolePermissions(Role $role, array $data)
    {
        if ($role->permissions()->sync($data['permissions'] ?? [])) {
            Auth::user()->log('memperbarui hak akses peran ' . $role->name . ' <strong>[ID: ' . $role->id . ']</strong>', Role::class, $role->id);
            return $role;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyCompanyRole(Role $role)
    {
        if (!$role->trashed() && $role->delete()) {
            Auth::user()->log('menghapus peran ' . $role->name . ' <strong>[ID: ' . $role->id . ']</strong>', Role::class, $role->id);
            return $role;
        }

        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreCompanyRole(Role $role)
    {
        if ($role->trashed() && $role->restore()) {
            Auth::user()->log('memulihkan peran ' . $role->name . ' <strong>[ID: ' . $role->id . ']</strong>', Role::class, $role->id);
            return $role;
        }
        return false;
    }
}
