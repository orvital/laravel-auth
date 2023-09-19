<?php

namespace Orvital\Auth;

use Illuminate\Auth\AuthManager;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Validator;

class AuthService
{
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
        if (! isset($credentials['email']) || ! isset($credentials['password'])) {
            return null;
        }

        $user = $this->provider()->retrieveByCredentials($credentials);

        $validated = $user && $this->provider()->validateCredentials($user, $credentials);

        return $validated ? $user : null;
    }

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
     * Log the user out of the application.
     */
    public function logout(): void
    {
        // Logout event
        $this->auth->logout();

        $this->auth->getSession()?->invalidate();

        $this->auth->getSession()?->regenerateToken();
    }

    /**
     * Validate credentials.
     */
    public function validate(array $credentials = []): bool
    {
        $validator = Validator::make($credentials, [
            'email' => ['required', 'max:192', 'email'],
            'password' => ['required', 'max:192'],
        ]);

        $validator->after(function ($validator) {
            if (! $this->auth->validate($validator->validated())) {
                $validator->errors()->add('email', trans('auth.failed'));
            }
        });

        return $validator->passes();
    }

    /**
     * Retrieve the user provider implementation.
     */
    public function provider(): EloquentUserProvider
    {
        return $this->auth->getProvider();
    }
}
