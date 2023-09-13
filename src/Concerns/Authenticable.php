<?php

namespace Orvital\Auth\Concerns;

use Illuminate\Notifications\Notifiable;
use Orvital\Auth\Concerns\Authenticatable;
use Orvital\Auth\Concerns\CanResetPassword;
use Orvital\Auth\Concerns\MustVerifyEmail;

/**
 * Notifiable trait required by MustVerifyEmail and CanResetPassword
 */
trait Authenticable
{
    use Authenticatable;
    use CanResetPassword;
    use MustVerifyEmail;
    use Notifiable;
}
