<?php

namespace Modules\Academic\Models\Traits;

use Modules\Academic\Models\EmployeeTeacher;
use Modules\Academic\Models\AcademicSemester;
use Modules\HRMS\Models\Employee;

trait AcademicSubjectTrait
{
    /**
     * Find by nip, .
     */
    public function scopeInTeacherAndSemester($query, Employee $teacher, AcademicSemester $acsem)
    {
        return $query->whereIn('id', $teacher->meets->pluck('subject_id')->toArray())
                     ->where('semester_id', $acsem->id);
    }
}