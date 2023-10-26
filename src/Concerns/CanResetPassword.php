<?php

namespace Orvital\Auth\Concerns;

use Orvital\Auth\Notifications\ResetPassword;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 *
 * @see \Illuminate\Contracts\Auth\CanResetPassword
 */
trait CanResetPassword
{
    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset()
    {
        return $this->email;
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }
}
