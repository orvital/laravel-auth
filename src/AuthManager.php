<?php

namespace Orvital\Auth;

use Illuminate\Auth\AuthManager as BaseAuthManager;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;

/**
 * Auth Manager Decorator / Extender
 *
 * `Guards` define how users are authenticated for each request.
 * `Providers` define how users are retrieved from the persistent storage.
 *
 * when retrieving a user by credentials with `retrieveByCredentials($credentials)`
 * all the provided attributes are used in the query except for the `password` attribute!
 * the retrieved user is then validated cheking the hashed password against the provided plain password.
 */
class AuthManager extends BaseAuthManager
{
    protected Dispatcher $dispatcher;

    public function __construct($app)
    {
        parent::__construct($app);

        $this->dispatcher = $app->make(Dispatcher::class);
    }

    /**
     * Fire the registered event.
     */
    public function fireRegisteredEvent(Authenticatable $user): void
    {
        $this->dispatcher->dispatch(new Registered($user));
    }

    /**
     * Fire the verified event.
     */
    public function fireVerifiedEvent(MustVerifyEmail $user): void
    {
        $this->dispatcher->dispatch(new Verified($user));
    }

    /**
     * Fire the password reset event.
     */
    public function firePasswordResetEvent(Authenticatable $user): void
    {
        $this->dispatcher->dispatch(new PasswordReset($user));
    }

    /**
     * Fire the lockout event.
     */
    public function fireLockoutEvent(Request $request): void
    {
        $this->dispatcher->dispatch(new Lockout($request));
    }
}
