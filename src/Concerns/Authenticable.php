<?php

namespace Orvital\Auth\Concerns;

/**
 * @property-read \Illuminate\Contracts\Events\Dispatcher $dispatcher
 *
 * @mixin \Orvital\Core\Database\Eloquent\Model
 */
trait Authenticable
{
    /**
     * Bootstrap called on each model class boot.
     */
    public static function bootAuthenticable()
    {
        static::$dispatcher->listen(\Illuminate\Auth\Events\Authenticated::class, function ($event) {
            static::$dispatcher->dispatch('eloquent.authenticated: '.static::class, $event->user, true);
        });
    }

    /**
     * Initializer called on each new model instance.
     */
    public function initializeAuthenticable()
    {
        $this->addObservableEvents(['authenticated']);
    }

    /**
     * Register a authenticated model event with the dispatcher.
     *
     * @param  \Illuminate\Events\QueuedClosure|Closure|string|array  $callback
     */
    public static function authenticated($callback): void
    {
        static::registerModelEvent('authenticated', $callback);
    }
}
