<?php

namespace Orvital\Auth\Middlewares;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class DenyAuthenticated
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
     */
    public function handle(Request $request, Closure $next, string ...$guards): mixed
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                return $request->expectsJson()
                    ? throw new AuthorizationException('This action is unauthorized for authenticated users.')
                    : Redirect::to('/home');
            }
        }

        return $next($request);
    }
}
