<?php

namespace Modules\Core\Providers;

use Modules\Core\Models;
use Modules\Core\Policies;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Modules\Account\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Models\CompanyApprovable::class => Policies\CompanyApprovablePolicy::class,
        Models\CompanyBuilding::class => Policies\CompanyBuildingPolicy::class,
        Models\CompanyBuildingRoom::class => Policies\CompanyBuildingRoomPolicy::class,
        Models\CompanyContract::class => Policies\CompanyContractPolicy::class,
        Models\CompanyDepartment::class => Policies\CompanyDepartmentPolicy::class,
        Models\CompanyInsurance::class => Policies\CompanyInsurancePolicy::class,
        Models\CompanyInsurancePrice::class => Policies\CompanyInsurancePricePolicy::class,
        Models\CompanyLeaveCategory::class => Policies\CompanyLeaveCategoryPolicy::class,
        Models\CompanyStudentLeaveCategory::class => Policies\CompanyStudentLeaveCategoryPolicy::class,
        Models\CompanyMoment::class => Policies\CompanyMomentPolicy::class,
        Models\CompanyOutworkCategory::class => Policies\CompanyOutworkCategoryPolicy::class,
        Models\CompanyPosition::class => Policies\CompanyPositionPolicy::class,
        Models\CompanyRole::class => Policies\CompanyRolePolicy::class,
        Models\CompanySalarySlip::class => Policies\CompanySalarySlipPolicy::class,
        Models\CompanySalarySlipCategory::class => Policies\CompanySalarySlipCategoryPolicy::class,
        Models\CompanySalarySlipComponent::class => Policies\CompanySalarySlipComponentPolicy::class,
        Models\CompanySalaryTemplate::class => Policies\CompanySalaryTemplatePolicy::class,
        Models\CompanyVacationCategory::class => Policies\CompanyVacationCategoryPolicy::class,
        Models\CompanyPtkp::class => Policies\CompanyPtkpPolicy::class,
        Models\CompanyPayrollSetting::class => Policies\CompanyPayrollSettingPolicy::class,
        Models\CompanyLoanCategory::class => Policies\CompanyLoanCategoryPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('core::access', function(User $user){
            $employee = $user->regularEmp;

            if ($employee && $employee->contract) {
                $positionType = $employee->position->position->type;

                $allowedTypes = [
                    \Modules\Core\Enums\PositionTypeEnum::HUMAS,
                    \Modules\Core\Enums\PositionTypeEnum::KEPALASEKOLAH,
                ];

                return in_array($positionType, $allowedTypes, true);
            }

            return false;
        });

        // Gate::define(
        //     'core::access',
        //     fn(User $user) => count(array_filter(array_map(fn($policy) => (new $policy())->access($user), $this->policies))) || $user->can('access', User::class)
        // );
    }
}
