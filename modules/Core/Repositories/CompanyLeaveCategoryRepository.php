<?php

namespace Modules\Core\Repositories;

use Arr;
use Auth;
use Modules\Core\Models\CompanyLeaveCategory;

trait CompanyLeaveCategoryRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'name', 'grade_id', 'parent_id', 'meta'
    ];

    /**
     * Store newly created resource.
     */
    public function storeCompanyLeaveCategory(array $data)
    {
        $category = new CompanyLeaveCategory(array_merge(
            Arr::only($data, $this->keys),
            ['grade_id' => userGrades()]
        ));
        
        if($category->save()) {
            Auth::user()->log('membuat kategori izin baru dengan nama '.$category->name.' <strong>[ID: '.$category->id.']</strong>', CompanyLeaveCategory::class, $category->id);
            return $category;
        }
        return false;
    }

    /**
     * Update the current resource.
     */
    public function updateCompanyLeaveCategory(CompanyLeaveCategory $category, array $data)
    {
        $category = $category->fill(array_merge(
           Arr::only($data, $this->keys),
            ['grade_id' => userGrades()]
        ));

        if($category->save()) {
            Auth::user()->log('memperbarui kategori izin '.$category->name.' <strong>[ID: '.$category->id.']</strong>', CompanyLeaveCategory::class, $category->id);
            return $category;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyCompanyLeaveCategory(CompanyLeaveCategory $category)
    {
        if(!$category->trashed() && $category->delete()) {
            Auth::user()->log('menghapus kategori izin '.$category->name.' <strong>[ID: '.$category->id.']</strong>', CompanyLeaveCategory::class, $category->id);
            return $category;
        }
        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreCompanyLeaveCategory(CompanyLeaveCategory $category)
    {
        if($category->trashed() && $category->restore()) {
            Auth::user()->log('memulihkan kategori izin '.$category->name.' <strong>[ID: '.$category->id.']</strong>', CompanyLeaveCategory::class, $category->id);
            return $category;
        }
        return false;
    }
}
