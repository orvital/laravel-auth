<?php

namespace Orvital\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\AggregateServiceProvider;
use Orvital\Auth\AuthManager;

/**
 * @property-read \Illuminate\Foundation\Application $app
 *
 * @see \Illuminate\Auth\AuthServiceProvider
 */
class AuthServiceProvider extends AggregateServiceProvider
{
    public function register()
    {
        parent::register();

        $this->app->offsetUnset(Authenticatable::class);

        // Singleton / Not Deferred
        $this->app->extend('auth', function ($authManager, $app) {
            return new AuthManager($app);
        });
    }

    /**
     * Boot services.
     */
    public function boot(): void
    {
        //
    }
}
