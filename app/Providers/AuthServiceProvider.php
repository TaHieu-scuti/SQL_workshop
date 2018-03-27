<?php

namespace App\Providers;

use App\User;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Services\Auth\SharingSessionGuard;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $this->app['auth']->provider(
            'adgainer',
            function () {
                return new AdGainerUserProvider(new User);
            }
        );

        $this->app['auth']->extend(
            'custom',
            function () {
                return new SharingSessionGuard(new User);
            }
        );
    }
}
