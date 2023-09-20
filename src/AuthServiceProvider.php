<?php

namespace Orvital\Auth;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Orvital\Auth\Providers\EventProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The provider class names.
     */
    protected array $providers = [
        EventProvider::class,
    ];

    /**
     * Register bindings.
     */
    public function register(): void
    {
        // Register service providers.
        foreach ($this->providers as $provider) {
            $this->app->register($provider);
        }
    }

    /**
     * Boot services.
     */
    public function boot(): void
    {
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
