<?php

namespace Orvital\Auth;

use Illuminate\Support\AggregateServiceProvider;
use Orvital\Auth\Passwords\PasswordProvider;
use Orvital\Auth\Providers\EventProvider;

class AuthServiceProvider extends AggregateServiceProvider
{
    protected $providers = [
        EventProvider::class,
        PasswordProvider::class,
    ];

    /**
     * Boot services.
     */
    public function boot(): void
    {
        //
    }
}
