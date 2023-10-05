<?php

namespace Orvital\Auth\Passwords;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Orvital\Auth\Passwords\PasswordBrokerManager;

class PasswordProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->extend('auth.password', function ($repository, $app) {
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
