<?php

namespace Orvital\Auth;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;

/**
 * @property-read \Illuminate\Foundation\Application $app
 */
class EventProvider extends EventServiceProvider
{
    // The SerializesModels trait used by the event will gracefully serialize any Eloquent models
    // if the event object is serialized using PHP's serialize function,
    // such as when utilizing queued listeners.
    protected $listen = [
        // Fired by Session Guard. all events include the `$guard` name
        'Illuminate\Auth\Events\Attempting' => [],              // --$user,     --SerializesModels  OK
        'Illuminate\Auth\Events\Authenticated' => [],           // $user,       SerializesModels    OK
        'Illuminate\Auth\Events\Validated' => [],               // $user,       SerializesModels    OK
        'Illuminate\Auth\Events\Login' => [],                   // $user,       SerializesModels    OK
        'Illuminate\Auth\Events\Logout' => [],                  // $user,       SerializesModels    OK
        'Illuminate\Auth\Events\Failed' => [],                  // $user,       --SerializesModels  ??
        'Illuminate\Auth\Events\CurrentDeviceLogout' => [],     // $user,       SerializesModels    OK
        'Illuminate\Auth\Events\OtherDeviceLogout' => [],       // $user,       SerializesModels    OK
        // not fired
        'Illuminate\Auth\Events\Lockout' => [],                 // $request,    --SerializesModels  OK
        'Illuminate\Auth\Events\Verified' => [],                // $user,       SerializesModels    OK
        'Illuminate\Auth\Events\PasswordReset' => [],           // $user,       SerializesModels    OK
        'Illuminate\Auth\Events\Registered' => [                // $user,       SerializesModels    OK
            'Illuminate\Auth\Listeners\SendEmailVerificationNotification',
        ],
        // Fired by Sanctum Guard
        'Orvital\Sanctum\Events\TokenAuthenticated' => [],      // $token,      --SerializesModels  OK

    ];

    protected $subscribe = [];

    protected $observers = [];

    public function boot(): void
    {
        //
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
