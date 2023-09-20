<?php

namespace Orvital\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Support\Traits\ForwardsCalls;

class AuthService
{
    use ForwardsCalls;

    /**
     * @param  \Illuminate\Auth\SessionGuard|\Illuminate\Auth\AuthManager  $auth
     */
    public function __construct(
        protected AuthFactory $auth,
    ) {
    }

    /**
     * Retrieve the currently authenticated user.
     */
    // public function current(): ?Authenticatable
    // {
    //     return $this->auth->user();
    // }

    /**
     * Validate credentials.
     */
    // public function validate(array $credentials = []): bool
    // {
    //     return $this->auth->validate($credentials);
    // }

    /**
     * Attempt to authenticate with the given credentials.
     */
    // public function attempt(array $credentials = [], bool $remember = false): bool
    // {
    //     return $this->auth->attempt($credentials, $remember);
    // }

    /**
     * Authenticate user credentials.
     */
    public function authenticate(array $credentials = [], bool $remember = false): Authenticatable|false
    {
        // TODO: Observe events to handle rate limits and others
        if ($this->auth->attempt($credentials, $remember)) {
            $this->auth->getSession()?->regenerate();

            return $this->current();
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
     * Remove user from session, cookies and memory.
     */
    // public function logout(): void
    // {
    //     $this->auth->logout();
    // }

    /**
     * Retrieve the user provider implementation.
     */
    public function provider(): EloquentUserProvider
    {
        return $this->auth->getProvider();
    }

    /**
     * Handle calls to nonexistent or inaccessible methods.
     */
    public function __call($method, $parameters)
    {
        return $this->forwardCallTo($this->auth, $method, $parameters);

        // if (in_array($method, ['list', 'find', 'create', 'update', 'findByEmail'])) {
        //     return $this->forwardDecoratedCallTo($this->repository, $method, $parameters);
        // }
    }
}
