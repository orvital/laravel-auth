<?php

namespace Orvital\Auth;

use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Orvital\Auth\Concerns\Authenticatable;
use Orvital\Auth\Concerns\CanResetPassword;
use Orvital\Auth\Concerns\MustVerifyEmail;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable; // Custom
    use Authorizable; // External
    use CanResetPassword; // Custom
    use MustVerifyEmail; // Custom
    use Notifiable; // External - Required by CanResetPassword and MustVerifyEmail
}
