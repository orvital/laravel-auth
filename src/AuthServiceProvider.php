<?php

namespace Orvital\Auth;

use Illuminate\Support\AggregateServiceProvider;
use Orvital\Auth\Providers\EventProvider;
use Orvital\Auth\Providers\PasswordProvider;

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
