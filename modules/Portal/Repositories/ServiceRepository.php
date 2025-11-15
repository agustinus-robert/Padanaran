<?php

namespace Modules\Portal\Repositories;

use Modules\HRMS\Models\Employee;

trait ServiceRepository
{
    /**
     * Current employee vacation this month.
     */
    public function getCurrentEmployeeVacation(Employee $employee)
    {
        return $vacations = $employee->vacations()->with('quota.category', 'approvables')->whereExtractedDatesBetween(now()->copy()->startOfMonth()->format("Y-m-d"), now()->copy()->endOfMonth()->format("Y-m-d"))->get()
            ->filter(fn ($vacation) => $vacation->hasAllApprovableResultIn('APPROVE'))
            ->pluck('dates')
            ->filter(fn ($date) => empty($date->first()['cashable']))->flatten(1)->groupBy('d')->flatten(1)->unique();
    }

    /**
     * Current employee holiday this month.
     */
    public function getCurrentEmployeeHoliday(Employee $employee)
    {
        return $holidays = $employee->holidays()->with('approvables')->whereExtractedDatesBetween(now()->copy()->startOfMonth()->format("Y-m-d"), now()->copy()->endOfMonth()->format("Y-m-d"))->get()
            ->filter(fn ($vacation) => $vacation->hasAllApprovableResultIn('APPROVE'))
            ->pluck('dates')
            ->filter(fn ($date) => empty($date->first()['cashable']))->flatten(1)->groupBy('d')->flatten(1)->unique();
    }
}
