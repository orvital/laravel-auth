<?php

namespace Orvital\Auth\Limiters;

use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LoginLimiter
{
    public function __construct(
        protected RateLimiter $limiter,
        protected mixed $key = null
    ) {
    }

    /**
     * Set the key from the given request.
     */
    public function for(Request $request): self
    {
        $this->key = Str::transliterate(Str::lower($request->input('email')).'|'.$request->ip());

        return $this;
    }

    /**
     * Get the number of attempts for the given key.
     */
    public function attempts(): mixed
    {
        return $this->limiter->attempts($this->key);
    }

    /**
     * Determine if the user has too many failed login attempts.
     */
    public function tooManyAttempts(): bool
    {
        return $this->limiter->tooManyAttempts($this->key, 5);
    }

    /**
     * Increment the login attempts for the user.
     */
    public function increment(): void
    {
        $this->limiter->hit($this->key, 60);
    }

    /**
     * Determine the number of seconds until logging in is available again.
     */
    public function availableIn(): int
    {
        return $this->limiter->availableIn($this->key);
    }

    /**
     * Clear the login locks for the given user credentials.
     */
    public function clear(): void
    {
        $this->limiter->clear($this->key);
    }
}
