<?php

namespace Modules\Poz\Providers;

use Modules\Admin\Models;
use Modules\Admin\Policies;
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
    protected $policies = [];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        // Gate::define(
        //     'admin::access',
        //     fn(User $user) => count(array_filter(array_map(fn($policy) => (new $policy())->access($user), $this->policies)))
        // );

        Gate::define('poz::access', function (User $user) {
            $employee = $user->regularEmp;

            if ($employee && $employee->contract && $employee->contract->position && $employee->contract->position->position) {
                $positionType = $employee->contract->position->position->type;

                if ($positionType === \Modules\Core\Enums\PositionTypeEnum::KASIRTOKO) {
                    return true;
                }
            }

            return false;
        });


        Gate::define('supplier::access', function (User $user) {
            $employee = $user->regularEmp;

            if ($employee && $employee->contract) {
                $positionType = $employee->position->position->type;

                if ($positionType === \Modules\Core\Enums\PositionTypeEnum::SUPPLIER) {
                    return true;
                }
            }

            return false;
        });
        //poz::supplier.dashboard

        //  Gate::define('poz::access', function (User $user) {
        //     // if ($user->student) {
        //     //     return false;
        //     // }  
            
        //     return true;
        // });
        // Gate::define(
        //     'poz::access',
        //     fn (User $user) => count(array_filter(array_map(fn ($policy) => (new $policy())->access($user), $this->policies)))
        // );
    }
}
