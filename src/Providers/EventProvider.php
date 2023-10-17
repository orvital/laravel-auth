<?php

namespace Orvital\Auth\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;

/**
 * @property-read \Illuminate\Foundation\Application $app
 */
class EventProvider extends EventServiceProvider
{
    protected $listen = [
        // fired by Session guard
        'Illuminate\Auth\Events\Attempting' => [],
        'Illuminate\Auth\Events\Authenticated' => [],
        'Illuminate\Auth\Events\CurrentDeviceLogout' => [],
        'Illuminate\Auth\Events\Failed' => [],
        'Illuminate\Auth\Events\Login' => [],
        'Illuminate\Auth\Events\Logout' => [],
        'Illuminate\Auth\Events\OtherDeviceLogout' => [],
        'Illuminate\Auth\Events\Validated' => [],
        // fired by Sanctum guard
        'Orvital\Sanctum\Events\TokenAuthenticated' => [],
        // not fired
        'Illuminate\Auth\Events\Lockout' => [],
        'Illuminate\Auth\Events\PasswordReset' => [],
        'Illuminate\Auth\Events\Registered' => [
            'Orvital\Auth\Listeners\SendEmailVerificationNotification',
        ],
        'Illuminate\Auth\Events\Verified' => [],

    ];

    protected $subscribe = [];

    protected $observers = [];

    public function boot(): void
    {
        //
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
