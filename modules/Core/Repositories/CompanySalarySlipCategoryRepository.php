<?php

namespace Modules\Core\Repositories;

use Arr;
use Auth;
use Illuminate\Http\Request;
use Modules\Core\Models\CompanySalarySlipCategory;

trait CompanySalarySlipCategoryRepository
{
    public function getCompanySalaryCategories($request)
    {
        return $categories = CompanySalarySlipCategory::with('slip')->whenTrashed($request->get('trash'))
            ->whereHas('slip', function($query){
                $query->where('grade_id', userGrades());
            })
            ->search($request->get('search'))
            ->orderBy('slip_id')
            ->orderBy('az')
            ->paginate($request->get('limit', 10));
    }
    /**
     * Store newly created resource.
     */
    public function storeCompanySalarySlipCategory(array $data)
    {
        $category = new CompanySalarySlipCategory(
            array_merge(
                Arr::only($data, ['slip_id', 'az', 'name']),
                ['grade_id' => userGrades()]
            )
        );

        if ($category->save()) {
            Auth::user()->log('membuat kategori gaji baru ' . $category->name . ' <strong>[ID: ' . $category->id . ']</strong>', CompanySalarySlipCategory::class, $category->id);
            return $category;
        }
        return false;
    }

    /**
     * Update the current resource.
     */
    public function updateCompanySalarySlipCategory(CompanySalarySlipCategory $category, array $data)
    {
        $category = $category->fill(
            array_merge(
                Arr::only($data, ['slip_id', 'az', 'name']),
                ['grade_id' => userGrades()]
            )
        );
        
        if ($category->save()) {
            Auth::user()->log('memperbarui kategori gaji ' . $category->name . ' <strong>[ID: ' . $category->id . ']</strong>', CompanySalarySlipCategory::class, $category->id);
            return $category;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyCompanySalarySlipCategory(CompanySalarySlipCategory $category)
    {
        if (!$category->trashed() && $category->delete()) {
            Auth::user()->log('menghapus kategori gaji ' . $category->name . ' <strong>[ID: ' . $category->id . ']</strong>', CompanySalarySlipCategory::class, $category->id);
            return $category;
        }
        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreCompanySalarySlipCategory(CompanySalarySlipCategory $category)
    {
        if ($category->trashed() && $category->restore()) {
            Auth::user()->log('memulihkan kategori gaji ' . $category->name . ' <strong>[ID: ' . $category->id . ']</strong>', CompanySalarySlipCategory::class, $category->id);
            return $category;
        }
        return false;
    }
}
