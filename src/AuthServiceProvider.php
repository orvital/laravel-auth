<?php

namespace Orvital\Auth;

use Illuminate\Support\AggregateServiceProvider;
use Illuminate\Validation\Rules\Password;
use Orvital\Auth\Providers\EventProvider;

class AuthServiceProvider extends AggregateServiceProvider
{
    protected $providers = [
        EventProvider::class,
    ];

    /**
     * Boot services.
     */
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
