<?php

namespace Orvital\Auth\Passwords;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Orvital\Auth\Passwords\PasswordBrokerManager;

/**
 * @property-read \Illuminate\Foundation\Application $app
 *
 * @see \Illuminate\Auth\Passwords\PasswordResetServiceProvider
 */
class PasswordProvider extends ServiceProvider
{
    public function register()
    {
        // Singleton / Deferred
        $this->app->extend('auth.password', function ($instance, $app) {
            return new PasswordBrokerManager($app);
        });
    }

    public function boot(): void
    {
        Password::defaults(function () {
            $rule = Password::min(8);

            if ($this->app->environment('production')) {
                $rule = $rule->mixedCase()->numbers()->symbols()->uncompromised();
            }

            return $rule;
        });
    }
}
