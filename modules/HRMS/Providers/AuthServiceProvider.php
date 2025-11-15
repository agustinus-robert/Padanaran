<?php

namespace Modules\HRMS\Providers;

use Modules\HRMS\Models;
use Modules\HRMS\Policies;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\Account\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Models\EmployeeContract::class => Policies\EmployeeContractPolicy::class,
        Models\EmployeeDataRecapitulation::class => Policies\EmployeeDataRecapitulationPolicy::class,
        Models\EmployeeSchedule::class => Policies\EmployeeSchedulePolicy::class,
        Models\EmployeeScheduleSubmission::class => Policies\EmployeeScheduleSubmissionPolicy::class,
        Models\EmployeeInsurance::class => Policies\EmployeeInsurancePolicy::class,
        Models\EmployeeLeave::class => Policies\EmployeeLeavePolicy::class,
        Models\EmployeeOutwork::class => Policies\EmployeeOutworkPolicy::class,
        Models\EmployeeOvertime::class => Policies\EmployeeOvertimePolicy::class,
        Models\Employee::class => Policies\EmployeePolicy::class,
        Models\EmployeePosition::class => Policies\EmployeePositionPolicy::class,
        Models\EmployeeSalary::class => Policies\EmployeeSalaryPolicy::class,
        Models\EmployeeSalaryTemplateItem::class => Policies\EmployeeSalaryTemplateItemPolicy::class,
        Models\EmployeeSalaryTemplate::class => Policies\EmployeeSalaryTemplatePolicy::class,
        Models\EmployeeScanLog::class => Policies\EmployeeScanLogPolicy::class,
        Models\EmployeeTeacherScanLog::class => Policies\EmployeeTeacherScanLogPolicy::class,
        Models\EmployeeVacation::class => Policies\EmployeeVacationPolicy::class,
        Models\EmployeeVacationQuota::class => Policies\EmployeeVacationQuotaPolicy::class,
        Models\EmployeeRecapSubmission::class => Policies\EmployeeRecapSubmissionPolicy::class,
        Models\EmployeeTax::class => Policies\EmployeeTaxPolicy::class,
        Models\EmployeeLoan::class => Policies\EmployeeLoanPolicy::class,
        Models\EmployeeDeduction::class => Policies\EmployeeDeductionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define(
            'hrms::access',
            fn(User $user) => count(array_filter(array_map(fn($policy) => (new $policy())->access($user), $this->policies)))
        );
    }
}
