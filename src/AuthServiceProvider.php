<?php

namespace Orvital\Auth;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
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

        /**
         * Define default password rules
         */
        Password::defaults(function () {
            $rule = Password::min(8);

            if ($this->app->environment('production')) {
                $rule = $rule->mixedCase()->numbers()->symbols()->uncompromised();
            }

            return $rule;
        });
    }
}
