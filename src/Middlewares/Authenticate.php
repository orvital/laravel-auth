<?php

namespace Orvital\Auth\Middlewares;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Contracts\Auth\Middleware\AuthenticatesRequests;
use Illuminate\Http\Request;

class Authenticate implements AuthenticatesRequests
{
    /**
     * Create a new middleware instance.
     */
    public function __construct(
        protected Auth $auth
    ) {
    }

    /**
     * Specify the guards for the middleware.
     */
    public static function using(string $guard, string ...$others): string
    {
        return static::class.':'.implode(',', [$guard, ...$others]);
    }

    /**
     * Handle an incoming request.
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle(Request $request, Closure $next, string ...$guards): mixed
    {
        $this->authenticate($request, $guards);

        return $next($request);
    }

    /**
     * Determine if the user is logged in to any of the given guards.
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function authenticate(Request $request, array $guards): mixed
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                return $this->auth->shouldUse($guard);
            }
        }

        $this->unauthenticated($request, $guards);
    }

    /**
     * Handle an unauthenticated user.
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function unauthenticated(Request $request, array $guards): void
    {
        throw new AuthenticationException('Unauthenticated.', $guards, $this->redirectTo($request));
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }
}
