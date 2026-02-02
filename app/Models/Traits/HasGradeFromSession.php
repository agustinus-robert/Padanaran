<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait HasGradeFromSession
{
    /**
     * Boot the trait.
     * Secara otomatis dipanggil oleh Laravel saat Model menggunakan trait ini.
     */
    protected static function bootHasGradeFromSession()
    {
        static::addGlobalScope('grade_auto_filter', function (Builder $builder) {
            $grades = userGrades();
            if ($grades) {
                $tableName = $builder->getQuery()->from;
                $builder->whereIn($tableName . '.grade_id', (array) $grades);
            }
        });

        static::saving(function (Model $model) {
            $grades = userGrades();
            if ($grades) {
                $value = is_array($grades) ? ($grades[0] ?? null) : $grades;

                $model->grade_id = $value;
            }
        });
    }
}
