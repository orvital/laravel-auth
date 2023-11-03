<?php

namespace Orvital\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;

class AuthService
{
    public function __construct(
        protected Dispatcher $dispatcher,
    ) {
    }

    /**
     * Fire the registered event.
     */
    protected function fireRegisteredEvent(Authenticatable $user): void
    {
        $this->dispatcher->dispatch(new Registered($user));
    }

    /**
     * Fire the registered event.
     */
    protected function fireVerifiedEvent(MustVerifyEmail $user): void
    {
        $this->dispatcher->dispatch(new Verified($user));
    }

    /**
     * Fire the password reset event.
     */
    protected function firePasswordResetEvent(Authenticatable $user): void
    {
        $this->dispatcher->dispatch(new PasswordReset($user));
    }

    /**
     * Fire the lockout event.
     */
    protected function fireLockoutEvent(Request $request): void
    {
        $this->dispatcher->dispatch(new Lockout($request));
    }
}
