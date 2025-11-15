<?php

namespace Modules\Portal\Providers;

use Modules\Account\Models;
use Modules\Account\Policies;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\Account\Models\User;
use Illuminate\Support\Facades\Gate;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
    ];
    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

         Gate::before(function (User $user, $ability) {
            if(isset($user->roles->first()->id) && $user->roles->first()->id == 1){
                return true;
            } 
            
            if ($user->level == 1) {
                return true;
            }
        });

        Gate::define('is-casier', function(User $user){
            $employee = $user->regularEmp;


            if ($employee && $employee->contract) {
                $positionType = $employee->position->position->type;

                $allowedTypes = [
                    \Modules\Core\Enums\PositionTypeEnum::KASIRTOKO,
                    \Modules\Core\Enums\PositionTypeEnum::KASIRSWALAYAN,
                ];

                return in_array($positionType, $allowedTypes, true);
            }

            return false;
        });

        Gate::define('is-supplier', function(User $user){
            $employee = $user->regularEmp;

            if ($employee && $employee->contract) {
                $positionType = $employee->position->position->type;

                $allowedTypes = [
                    \Modules\Core\Enums\PositionTypeEnum::SUPPLIER
                ];

                return in_array($positionType, $allowedTypes, true);
            }

            return false;
        });


        Gate::define('portal::access', function (User $user) {
            if ($user->student) {
                return false;
            }  

            return true;
        });
    }
}
