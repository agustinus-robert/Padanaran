<?php

namespace Modules\Docs\Providers;

use Modules\Docs\Models;
use Modules\Docs\Policies;
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
        Models\Document::class => Policies\DocumentPolicy::class,
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
            'docs::access',
            fn (User $user) => count(array_filter(array_map(fn ($policy) => (new $policy())->access($user), $this->policies))) || $user->can('access', User::class)
        );
    }
}
