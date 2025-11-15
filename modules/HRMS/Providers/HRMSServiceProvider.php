<?php

namespace Modules\HRMS\Providers;

use Modules\Account\Models\User;
use Modules\Core\Models\CompanyPosition;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeePosition;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Modules\Core\Models\CompanyInsurancePrice;

class HRMSServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'HRMS';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'hrms';

    /**
     * Register any application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/' . $this->moduleNameLower . '.php',
            'modules.' . $this->moduleNameLower
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(AuthServiceProvider::class);

        $this->loadDynamicRelationships();

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadViewsFrom(__DIR__ . '/../Resources/Views', $this->moduleNameLower);
        $this->loadViewsFrom(__DIR__ . '/../Resources/Components', 'x-' . $this->moduleNameLower);

        // $this->loadTranslationsFrom(__DIR__.'/../Resources/Lang', $this->moduleNameLower);

        Blade::componentNamespace('Modules\\' . $this->moduleName . '\\Resources\\Components', $this->moduleNameLower);
    }

    /**
     * Register dynamic relationships.
     */
    public function loadDynamicRelationships()
    {
        User::resolveRelationUsing('employee', function ($user) {
            return $user->hasOne(Employee::class, 'user_id')->withDefault();
        });

        CompanyPosition::resolveRelationUsing('employees', function ($position) {
            return $position->belongsToMany(Employee::class, 'empl_positions', 'position_id', 'empl_id')->withPivot('id');
        });

        CompanyPosition::resolveRelationUsing('employeePositions', function ($position) {
            return $position->hasMany(EmployeePosition::class, 'position_id');
        });

        CompanyInsurancePrice::resolveRelationUsing('employees', function ($position) {
            return $position->hasMany(Employee::class, 'empl_id');
        });
    }
}
