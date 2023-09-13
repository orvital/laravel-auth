<?php

namespace Orvital\Auth;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Orvital\Auth\Listeners\SendEmailVerificationNotification;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register bindings.
     */
    public function register(): void
    {
    }

    /**
     * Boot services.
     */
    public function boot(): void
    {
        Event::listen(Registered::class, [
            SendEmailVerificationNotification::class, 'handle',
        ]);
    }
}
