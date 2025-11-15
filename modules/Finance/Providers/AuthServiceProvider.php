<?php

namespace Modules\Finance\Providers;

use Modules\HRMS\Policies as HRMSPolicy;
use Modules\Core\Policies as CorePolicy;
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
        HRMSPolicy\EmployeeSalaryPolicy::class,
        HRMSPolicy\EmployeeOvertimePolicy::class,
        HRMSPolicy\EmployeeOutworkPolicy::class,
        HRMSPolicy\EmployeeInsurancePolicy::class,
        HRMSPolicy\EmployeeDataRecapitulationPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        Gate::define(
            'finance::access',
            fn (User $user) => count(array_filter(array_map(fn ($policy) => (new $policy())->access($user), $this->policies)))
        );

     // Gate::define('finance::access', fn(User $user) => true);
    }
}
