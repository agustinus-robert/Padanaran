<?php

namespace App\Providers;

use Illuminate\Support\Str;
use Laravel\Passport\Passport;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Yajra\DataTables\Services\DataTable;
use Modules\Admin\Http\Controllers\Builder\DataTableBuilderController;
use Modules\Admin\Http\Controllers\Configure\DataTableConfigureController;
use Modules\Admin\Http\Controllers\CustomFeature\DataTableCustomController;
use Illuminate\Database\Eloquent\Model;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Passport configuration
        if (env('BUG') == 0) {
            $this->app->when(DataTableBuilderController::class)->needs(DataTable::class)->give(function () {
                $className = request()->query('class', \App\DataTables\DefaultDataTables::class);

                if (class_exists($className)) {
                    return (new $className);
                }

                return abort(403);
            });

            $this->app->when(DataTableConfigureController::class)->needs(DataTable::class)->give(function () {
                $className = request()->query('class', \App\DataTables\DefaultDataTables::class);

                if (class_exists($className)) {
                    return (new $className);
                }

                return abort(403);
            });

            $this->app->when(DataTableCustomController::class)->needs(DataTable::class)->give(
                function () {
                    $className = request()->query('class', \App\DataTables\DefaultDataTables::class);

                    if (class_exists($className)) {
                        return (new $className);
                    }

                    return abort(403);
                }
            );
        }
        // Passport::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::defaultView('vendor.pagination.bootstrap');
        Paginator::defaultSimpleView('vendor.pagination.simple-bootstrap');

        // Set local time langauge format
        setlocale(LC_ALL, 'id_ID.UTF8', 'id_ID.UTF-8', 'id_ID.8859-1', 'id_ID', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'IND', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID', 'en_US.UTF8', 'en_US.UTF-8', 'en_US.8859-1', 'en_US', 'American', 'ENG', 'English');

        // Load service provider
        $this->app->register(ModuleServiceProvider::class);
        $this->app->register(ResponseServiceProvider::class);
        $this->loadMacroables();
    }

    public function loadMacroables()
    {
        Str::macro('money', function ($number, int $decimal = 2, string $currency = 'IDR') {
            $thousand_separator = ($is_idr = in_array(strtoupper($currency), explode('|', 'IDR|RP|RUPIAH'))) ? ',' : '.';
            $decimal_separator = $is_idr ? '.' : ',';
            return is_numeric($number) ? number_format((float) $number, $decimal, $thousand_separator, $decimal_separator) : '';
        });
    }
}
