<?php

namespace Modules\Admin\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The module namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected $moduleNamespace = 'Modules\Admin\Http\Controllers';
    protected $moduleLivewire = 'Modules\Admin\Http\Livewire';

    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        Route::middleware('web')
            ->namespace($this->moduleNamespace)
            ->name('admin::')
            ->prefix('admin')
            ->group(__DIR__ . '/../Routes/web.php');

        // Route::middleware('web')
        //     ->namespace($this->moduleNamespace)
        //     ->name('admin::')
        //     ->prefix('admin')
        //     ->group(__DIR__ . '/../Routes/web-embark.php');

        Route::middleware('api')
            ->namespace($this->moduleNamespace . '\API')
            ->prefix('api/admin')
            ->name('api::admin.')
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
