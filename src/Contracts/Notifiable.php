<?php

namespace Orvital\Auth\Contracts;

interface Notifiable
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $instance
     * @return void
     */
    public function notify($instance);
}
