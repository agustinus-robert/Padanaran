<?php

namespace Modules\Docs\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class DocsServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Docs';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'docs';

    /**
     * Register any application services.
     *
     * @return void
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
     *
     * @return void
     */
    public function boot()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(AuthServiceProvider::class);

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadViewsFrom(__DIR__ . '/../Resources/Views', $this->moduleNameLower);
        $this->loadViewsFrom(__DIR__ . '/../Resources/Components', 'x-' . $this->moduleNameLower);

        // $this->loadTranslationsFrom(__DIR__.'/../Resources/Lang', $this->moduleNameLower);

        // Blade::componentNamespace('Modules\\'.$this->moduleName.'\\Resources\\Components', $this->moduleNameLower);
    }
}
