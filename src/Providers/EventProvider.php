<?php

namespace Orvital\Auth\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;

class EventProvider extends EventServiceProvider
{
    protected $listen = [
        'Illuminate\Auth\Events\Registered' => [
            'Orvital\Auth\Listeners\SendEmailVerificationNotification',
        ],
        'Illuminate\Auth\Events\Attempting' => [],
        'Illuminate\Auth\Events\Authenticated' => [],
        'Illuminate\Auth\Events\Login' => [],
        'Illuminate\Auth\Events\Failed' => [],
        'Illuminate\Auth\Events\Validated' => [],
        'Illuminate\Auth\Events\Verified' => [],
        'Illuminate\Auth\Events\Logout' => [],
        'Illuminate\Auth\Events\CurrentDeviceLogout' => [],
        'Illuminate\Auth\Events\OtherDeviceLogout' => [],
        'Illuminate\Auth\Events\Lockout' => [],
        'Illuminate\Auth\Events\PasswordReset' => [],
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
