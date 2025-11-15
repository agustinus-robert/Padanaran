<?php

namespace Modules\Core\Models\Traits;

use Arr;
use Modules\Core\Models\CompanyRole;

trait HasCompanyRole
{
	/**
	 * This belongs to many roles.
	 */
	public function roles()
	{
	    return $this->belongsToMany(CompanyRole::class, 'cmp_role_users', 'user_id', 'role_id');
	}

	/**
	 * This belongs to many roles.
	 */
	public function rolesWithPermissions()
	{
	    return $this->roles()->with('permissions');
	}

	/**
	 * This has any permissions to.
	 */
	public function hasAnyPermissionsTo($lists, $matchAll = false)
	{
		$result = [];

		foreach($lists as $list) {
			$result[] = $this->rolesWithPermissions->pluck('permissions')->flatten()->contains('key', $list);
		}

		return $matchAll ? count($lists) == count(array_filter($result)) : count(array_filter($result));
	}
}
