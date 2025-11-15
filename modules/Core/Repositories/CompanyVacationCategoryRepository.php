<?php

namespace Modules\Core\Repositories;

use Arr;
use Auth;
use Modules\Core\Models\CompanyVacationCategory;

trait CompanyVacationCategoryRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'name', 'type', 'meta', 'grade_id'
    ];

    /**
     * Store newly created resource.
     */
    public function storeCompanyVacationCategory(array $data)
    {
        $category = new CompanyVacationCategory(array_merge(
           Arr::only($data, $this->keys),
            ['grade_id' => userGrades()]
        ));

        if($category->save()) {
            Auth::user()->log('membuat kategori cuti baru dengan nama '.$category->name.' <strong>[ID: '.$category->id.']</strong>', CompanyVacationCategory::class, $category->id);
            return $category;
        }
        return false;
    }

    /**
     * Update the current resource.
     */
    public function updateCompanyVacationCategory(CompanyVacationCategory $category, array $data)
    {        
        $category = $category->fill(array_merge(
           Arr::only($data, $this->keys),
            ['grade_id' => userGrades()]
        ));
        
        if($category->save()) {
            Auth::user()->log('memperbarui kategori cuti '.$category->name.' <strong>[ID: '.$category->id.']</strong>', CompanyVacationCategory::class, $category->id);
            return $category;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyCompanyVacationCategory(CompanyVacationCategory $category)
    {
        if(!$category->trashed() && $category->delete()) {
            Auth::user()->log('menghapus kategori cuti '.$category->name.' <strong>[ID: '.$category->id.']</strong>', CompanyVacationCategory::class, $category->id);
            return $category;
        }
        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreCompanyVacationCategory(CompanyVacationCategory $category)
    {
        if($category->trashed() && $category->restore()) {
            Auth::user()->log('memulihkan kategori cuti '.$category->name.' <strong>[ID: '.$category->id.']</strong>', CompanyVacationCategory::class, $category->id);
            return $category;
        }
        return false;
    }
}