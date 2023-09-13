<?php

namespace Orvital\Auth\Middlewares;

use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class EnsureEmailIsVerified
{
    /**
     * Specify the redirect route for the middleware.
     */
    public static function redirectTo(string $route): string
    {
        return static::class.':'.$route;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $redirectToRoute = null): mixed
    {
        if (! $request->user() ||
            ($request->user() instanceof MustVerifyEmail && ! $request->user()->hasVerifiedEmail())) {
            return $request->expectsJson()
                    ? App::abort(403, 'Your email address is not verified.')
                    : Redirect::guest(URL::route($redirectToRoute ?: 'verification.notice'));
        }

        return $next($request);
    }
}
