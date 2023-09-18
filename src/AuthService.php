<?php

namespace Orvital\Auth;

use Illuminate\Auth\AuthManager;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\Authenticatable;

class AuthService
{
    public function __construct(
        protected AuthManager $auth,
    ) {
    }

    /**
     * Retrieve the user provider implementation.
     */
    public function provider(): EloquentUserProvider
    {
        return $this->auth->getProvider();
    }

    /**
     * Retrieve the currently authenticated user.
     */
    public function current(): ?Authenticatable
    {
        return $this->auth->user();
    }

    /**
     * Retrieve user by id.
     */
    public function findById($identifier): ?Authenticatable
    {
        return $this->provider()->retrieveById($identifier);
    }

    /**
     * Retrieve user by credentials.
     */
    public function findByCredentials(array $credentials): ?Authenticatable
    {
        $user = $this->provider()->retrieveByCredentials($credentials);

        // TODO: Set $credentials['password'] as null if not provided, required to avoid exception.
        $validated = $user && $this->provider()->validateCredentials($user, $credentials);

        return $validated ? $user : null;
    }

    /**
     * Authenticate user credentials.
     */
    public function authenticate(array $credentials = [], bool $remember = false): Authenticatable|false
    {
        // TODO: Observe events to handle rate limits and others
        $success = $this->auth->attempt($credentials, $remember);

        if ($success) {
            $this->auth->getSession()?->regenerate();

            return $this->current();
        }

        return false;
    }

    /**
     * Authenticate user instance or identifier.
     */
    public function login(Authenticatable|string $identifier, bool $remember = false): Authenticatable|false
    {
        if (is_string($identifier)) {
            return $this->auth->loginUsingId($identifier, $remember);
        }

        $this->auth->login($identifier, $remember);

        return $this->current();
    }

    /**
     * Register user credentials.
     */
    public function register(array $credentials, bool $login = false, bool $remember = false): Authenticatable
    {
        $user = tap($this->provider()->createModel()->fill($credentials))->save();

        event(new Registered($user));

        return $login ? $this->login($user, $remember) : $user;
    }
}
