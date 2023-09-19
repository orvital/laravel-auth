<?php

namespace Orvital\Auth;

use Illuminate\Auth\AuthManager;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Validator;

class AuthService
{
    /**
     * Undocumented function
     *
     * @param  \Illuminate\Auth\SessionGuard  $auth
     */
    public function __construct(
        protected AuthManager $auth,
    ) {
    }

    /**
     * Retrieve the currently authenticated user.
     */
    public function current(): ?Authenticatable
    {
        return $this->auth->user();
    }

    /**
     * Retrieve user by credentials.
     */
    public function retrieve(array $credentials): Authenticatable|false
    {
        if ($this->auth->validate($credentials)) {
            return $this->auth->getLastAttempted();
        }

        return false;
    }

    /**
     * Register user credentials.
     */
    public function register(array $credentials, bool $login = false, bool $remember = false): Authenticatable
    {
        $user = tap($this->provider()->createModel()->fill($credentials))->save();

        event(new Registered($user));

        if ($login) {
            $this->auth->login($user, $remember);
        }

        return $user;
    }

    /**
     * Retrieve the user provider implementation.
     */
    public function provider(): EloquentUserProvider
    {
        return $this->auth->getProvider();
    }
}
