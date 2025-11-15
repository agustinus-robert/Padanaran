<?php

namespace Modules\Core\Providers;

use App\Models\User;
use Modules\Core\Models\CompanyInventory;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Core';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'core';

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../Config/'.$this->moduleNameLower.'.php', 'modules.'.$this->moduleNameLower
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(AuthServiceProvider::class);

        $this->loadDynamicRelationships();

        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->loadViewsFrom(__DIR__.'/../Resources/Views', $this->moduleNameLower);
        $this->loadViewsFrom(__DIR__.'/../Resources/Components', 'x-'.$this->moduleNameLower);

        // $this->loadTranslationsFrom(__DIR__.'/../Resources/Lang', $this->moduleNameLower);

        Blade::componentNamespace('Modules\\'.$this->moduleName.'\\Resources\\Components', $this->moduleNameLower);
    }

    /**
     * Register dynamic relationships.
     *
     * @return void
     */
    public function loadDynamicRelationships()
    {
        User::resolveRelationUsing('inventories', function ($user) {
            return $user->morphMany(CompanyInventory::class, 'placeable');
        });
    }
}
