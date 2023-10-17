<?php

namespace Orvital\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\AggregateServiceProvider;
use Orvital\Auth\Passwords\PasswordProvider;
use Orvital\Auth\Providers\EventProvider;

/**
 * @property-read \Illuminate\Foundation\Application $app
 */
class AuthServiceProvider extends AggregateServiceProvider
{
    protected $providers = [
        EventProvider::class,
        PasswordProvider::class,
    ];

    public function register()
    {
        parent::register();

        $this->app->offsetUnset(Authenticatable::class);
    }

    /**
     * Boot services.
     */
    public function boot(): void
    {
        //
    }
}
